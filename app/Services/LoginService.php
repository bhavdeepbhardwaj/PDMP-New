<?php

namespace App\Services;

use App\Constants\LoginStatus;
use App\Models\LoginLog;
use App\Models\User;
use App\Support\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\RsaLoginService;

class LoginService
{

    public function __construct(
        private readonly RsaLoginService $rsaLoginService
    ) {
    }

    /**
     * Authenticate Employee
     */
    public function login(
        array $credentials,
        bool $remember,
        Request $request
    ): array {
        try {

            /*
            |--------------------------------------------------------------------------
            | Rate Limiting Key
            |--------------------------------------------------------------------------
            */

            $key = 'login|' . $request->ip() . '|' . $credentials['employee_code'];

            /*
            |--------------------------------------------------------------------------
            | Rate Limit Check
            |--------------------------------------------------------------------------
            */

            if (RateLimiter::tooManyAttempts($key, 5)) {

                $seconds = RateLimiter::availableIn($key);

                return [
                    'status' => false,
                    'message' => "Too many login attempts. Please try again after {$seconds} seconds.",
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | CAPTCHA Verification
            |--------------------------------------------------------------------------
            */

            $sessionCaptcha = session('captcha_code');

            $submittedCaptcha =
                $credentials['captcha_code'] ?? '';

            if (
                empty($sessionCaptcha) ||
                empty($submittedCaptcha) ||
                ! hash_equals(
                    strtolower($sessionCaptcha),
                    strtolower($submittedCaptcha)
                )
            ) {

                $this->saveLoginLog(
                    null,
                    $credentials['employee_code'],
                    LoginStatus::FAILED_CAPTCHA,
                    $request
                );

                session()->forget('captcha_code');

                RateLimiter::hit($key, 900);

                return [
                    'status' => false,
                    'message' => 'The CAPTCHA code does not match.',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Consume CAPTCHA
            |--------------------------------------------------------------------------
            */

            session()->forget('captcha_code');

            /*
            |--------------------------------------------------------------------------
            | Find Employee
            |--------------------------------------------------------------------------
            */

            $user = User::query()
                ->where(
                    'employee_code',
                    $credentials['employee_code']
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Employee Not Found
            |--------------------------------------------------------------------------
            */

            if (! $user) {

                $this->saveLoginLog(
                    null,
                    $credentials['employee_code'],
                    LoginStatus::FAILED_EMPLOYEE,
                    $request
                );

                RateLimiter::hit($key, 900);

                return [
                    'status' => false,
                    'message' => 'Invalid Employee Code or Password.',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Account Inactive
            |--------------------------------------------------------------------------
            */

            if ((int) $user->status !== 1) {

                $this->saveLoginLog(
                    $user->id,
                    $user->employee_code,
                    LoginStatus::FAILED_INACTIVE,
                    $request
                );

                RateLimiter::hit($key, 900);

                return [
                    'status' => false,
                    'message' => 'Your account is inactive. Please contact administrator.',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | RSA Password Decryption
            |--------------------------------------------------------------------------
            |
            | The browser sends the password as RSA-OAEP encrypted
            | Base64 data.
            |
            | Plain password exists only in server memory after
            | successful decryption.
            |
            */

            try {

                $plainPassword = $this->rsaLoginService->decrypt(
                    $credentials['password']
                );

            } catch (\Throwable $e) {

                report($e);

                $this->saveLoginLog(
                    $user->id,
                    $user->employee_code,
                    LoginStatus::FAILED_PASSWORD,
                    $request
                );

                RateLimiter::hit($key, 900);

                return [
                    'status' => false,
                    'message' => 'Invalid Employee Code or Password.',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Password Verification
            |--------------------------------------------------------------------------
            |
            | Supports:
            |
            | 1. Legacy BCrypt
            | 2. Current Argon2id
            |
            */

            $passwordValid = false;

            try {

                $passwordValid = $this->checkPassword(
                    $plainPassword,
                    $user->password
                );

            } catch (\Throwable $e) {

                report($e);

                $passwordValid = false;
            }

            /*
            |--------------------------------------------------------------------------
            | Invalid Password
            |--------------------------------------------------------------------------
            */

            if (! $passwordValid) {

                $this->saveLoginLog(
                    $user->id,
                    $user->employee_code,
                    LoginStatus::FAILED_PASSWORD,
                    $request
                );

                RateLimiter::hit($key, 900);

                return [
                    'status' => false,
                    'message' => 'Invalid Employee Code or Password.',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Transparent BCrypt → Argon2id Migration
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with($user->password, '$2y$') ||
                str_starts_with($user->password, '$2a$') ||
                str_starts_with($user->password, '$2b$')
            ) {

                try {

                    $user->forceFill([
                        'password' => Hash::make($plainPassword),
                    ])->save();

                } catch (\Throwable $e) {

                    /*
                    |--------------------------------------------------------------------------
                    | Do not block a valid login if migration fails.
                    |--------------------------------------------------------------------------
                    */

                    report($e);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Login
            |--------------------------------------------------------------------------
            */

            Auth::guard('web')->login(
                $user,
                $remember
            );

            /*
            |--------------------------------------------------------------------------
            | Prevent Session Fixation
            |--------------------------------------------------------------------------
            */

            $request->session()->regenerate();

            /*
            |--------------------------------------------------------------------------
            | Clear Login Attempts
            |--------------------------------------------------------------------------
            */

            RateLimiter::clear($key);

            /*
            |--------------------------------------------------------------------------
            | Save Login Log
            |--------------------------------------------------------------------------
            */

            $this->saveLoginLog(
                $user->id,
                $user->employee_code,
                LoginStatus::SUCCESS,
                $request
            );

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return ServiceResponse::success(
                LoginStatus::SUCCESS,
                [
                    'user' => $user,
                    'force_password_change' =>
                        (bool) $user->force_password_change,
                ]
            );

        } catch (\Throwable $e) {

            report($e);

            return [
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ];
        }
    }

    /**
     * Logout User
     */
    public function logout(Request $request): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->updateLogoutTime(Auth::id());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    /**
     * Verify Password
     *
     * Supports BCrypt and Argon2id.
     */
    private function checkPassword(
        string $plainPassword,
        string $storedHash
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Legacy BCrypt
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with($storedHash, '$2y$') ||
            str_starts_with($storedHash, '$2a$') ||
            str_starts_with($storedHash, '$2b$')
        ) {

            return Hash::driver('bcrypt')->check(
                $plainPassword,
                $storedHash
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Argon2id
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with($storedHash, '$argon2id$')
        ) {

            return Hash::check(
                $plainPassword,
                $storedHash
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Unknown Hash Algorithm
        |--------------------------------------------------------------------------
        */

        report(
            new \RuntimeException(
                'Unsupported password hash algorithm.'
            )
        );

        return false;
    }

    /**
     * Save Login Log
     */
    private function saveLoginLog(
        ?int $userId,
        string $employeeCode,
        string $status,
        Request $request
    ): void {

        try {

            LoginLog::create([
                'user_id' => $userId,
                'employee_code' => $employeeCode,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => $status,
                'login_at' => now(),
            ]);

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Logging failure should never stop authentication
            |--------------------------------------------------------------------------
            */

            report($e);
        }
    }

    /**
     * Update Logout Time
     */
    private function updateLogoutTime(int $userId): void
    {
        try {

            $log = LoginLog::where(
                'user_id',
                $userId
            )
                ->whereNull('logout_at')
                ->latest()
                ->first();

            if ($log) {

                $log->update([
                    'logout_at' => now(),
                ]);
            }

        } catch (\Throwable $e) {

            report($e);
        }
    }
}

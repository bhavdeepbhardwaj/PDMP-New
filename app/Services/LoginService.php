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

class LoginService
{
    /**
     * Authenticate Employee
     */
    public function login(array $credentials, bool $remember, Request $request): array
    {
        try {

            $key = 'login|' . $request->ip() . '|' . $credentials['employee_code'];

            if (RateLimiter::tooManyAttempts($key, 5)) {

                $seconds = RateLimiter::availableIn($key);

                return [
                    'status' => false,
                    'message' => "Too many login attempts. Please try again after {$seconds} seconds.",
                ];
            }

            $user = User::query()
                ->where('employee_code', $credentials['employee_code'])
                ->first();

            // Employee Not Found
            if (!$user) {

                $this->saveLoginLog(
                    null,
                    $credentials['employee_code'],
                    LoginStatus::FAILED_EMPLOYEE,
                    $request
                );

                RateLimiter::hit($key, 900);

                return [
                    'status'  => false,
                    'message' => 'Invalid Employee Code or Password.',
                ];
            }

            // Account Inactive
            if ((int) $user->status !== 1) {

                $this->saveLoginLog(
                    $user->id,
                    $user->employee_code,
                    LoginStatus::FAILED_INACTIVE,
                    $request
                );

                RateLimiter::hit($key, 900);

                return [
                    'status'  => false,
                    'message' => 'Your account is inactive. Please contact administrator.',
                ];
            }

            // Password Verification
            if (!Hash::check($credentials['password'], $user->password)) {

                $this->saveLoginLog(
                    $user->id,
                    $user->employee_code,
                    LoginStatus::FAILED_PASSWORD,
                    $request
                );

                RateLimiter::hit($key, 900);

                return [
                    'status'  => false,
                    'message' => 'Invalid Employee Code or Password.',
                ];
            }

            // Login
            Auth::guard('web')->login($user, $remember);

            // Prevent Session Fixation
            $request->session()->regenerate();

            // Clear Login Attempts
            RateLimiter::clear($key);

            // Save Login Log
            $this->saveLoginLog(
                $user->id,
                $user->employee_code,
                LoginStatus::SUCCESS,
                $request
            );

            // return [

            //     'status' => true,

            //     'message' => 'Login Successful.',

            //     'user' => $user,

            //     'force_password_change' => (bool) $user->force_password_change,

            // ];
            return ServiceResponse::success(

                LoginStatus::SUCCESS,

                [
                    'user' => $user,
                    'force_password_change' => (bool)$user->force_password_change

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
        if (!Auth::check()) {
            return;
        }

        $this->updateLogoutTime(Auth::id());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
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

                'user_id'       => $userId,

                'employee_code' => $employeeCode,

                'ip_address'    => $request->ip(),

                'user_agent'    => $request->userAgent(),

                'status'        => $status,

                'login_at'      => now(),

            ]);
        } catch (\Throwable $e) {

            // Logging failure should never stop authentication
            report($e);
        }
    }

    /**
     * Update Logout Time
     */
    private function updateLogoutTime(int $userId): void
    {
        try {

            $log = LoginLog::where('user_id', $userId)
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

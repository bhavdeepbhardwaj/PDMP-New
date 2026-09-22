<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\PasswordHistory;
use App\Models\User;
use App\Support\ServiceResponse;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Constants\LoginStatus;

class PasswordService
{
    /**
     * Change User Password
     */
    public function changePassword(User $user, array $data): bool
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Verify Current Password
            |--------------------------------------------------------------------------
            |
            | Supports both:
            | - Existing BCrypt hashes
            | - New Argon2id hashes
            |
            */

            if (! $this->checkPassword(
                $data['current_password'],
                $user->password
            )) {

                throw ValidationException::withMessages([
                    'current_password' => 'Current password is incorrect.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Same Password
            |--------------------------------------------------------------------------
            */

            if ($this->checkPassword(
                $data['password'],
                $user->password
            )) {

                throw ValidationException::withMessages([
                    'password' => 'New password must be different from the current password.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Last 5 Passwords
            |--------------------------------------------------------------------------
            */

            $this->checkPasswordHistory(
                $user,
                $data['password']
            );

            /*
            |--------------------------------------------------------------------------
            | Save Current Password To History
            |--------------------------------------------------------------------------
            */

            $this->savePasswordHistory($user);

            /*
            |--------------------------------------------------------------------------
            | Update Password
            |--------------------------------------------------------------------------
            |
            | Hash::make() now uses Argon2id.
            |
            */

            $this->updatePassword(
                $user,
                $data['password']
            );

            DB::commit();

            return true;

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Send Password Reset Link
     */
    public function sendResetLink(array $data): array
    {
        $user = User::query()
            ->where('employee_code', $data['employee_code'])
            ->orWhere('email', $data['employee_code'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Generic Response
        |--------------------------------------------------------------------------
        |
        | Do not disclose whether an employee/email exists.
        |
        */

        if (! $user) {

            return ServiceResponse::error(
                'If the account exists, a password reset link has been sent to the registered email address.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Inactive Account
        |--------------------------------------------------------------------------
        */

        if ((int) $user->status !== 1) {

            return ServiceResponse::error(
                'If the account exists, a password reset link has been sent to the registered email address.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Send Reset Link
        |--------------------------------------------------------------------------
        */

        $status = Password::sendResetLink([
            'email' => $user->email,
        ]);

        return [
            'status' => $status === Password::RESET_LINK_SENT,
            'message' => __($status),
        ];
    }

    /**
     * Reset User Password
     */
    public function resetPassword(array $data): array
    {
        DB::beginTransaction();

        try {

            $status = Password::reset(
                [
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'password_confirmation' => $data['password_confirmation'],
                    'token' => $data['token'],
                ],

                function (User $user, string $password) {

                    /*
                    |--------------------------------------------------------------------------
                    | Check Last 5 Passwords
                    |--------------------------------------------------------------------------
                    */

                    $this->checkPasswordHistory(
                        $user,
                        $password
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Save Current Password
                    |--------------------------------------------------------------------------
                    */

                    $this->savePasswordHistory($user);

                    /*
                    |--------------------------------------------------------------------------
                    | Update Password
                    |--------------------------------------------------------------------------
                    |
                    | Hash::make() → Argon2id
                    |
                    */

                    $this->updatePassword(
                        $user,
                        $password,
                        true
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Password Reset Event
                    |--------------------------------------------------------------------------
                    */

                    event(new PasswordReset($user));

                    /*
                    |--------------------------------------------------------------------------
                    | Login Audit Log
                    |--------------------------------------------------------------------------
                    */

                    $this->createLoginLog(
                        $user,
                        LoginLog::PASSWORD_RESET_SUCCESS
                    );
                    
                }
            );

            /*
            |--------------------------------------------------------------------------
            | Reset Failed
            |--------------------------------------------------------------------------
            */

            if ($status !== Password::PASSWORD_RESET) {

                DB::rollBack();

                return [
                    'status' => false,
                    'message' => __($status),
                ];
            }

            DB::commit();

            return [
                'status' => true,
                'message' => 'Password reset successfully.',
            ];

        } catch (ValidationException $e) {

            DB::rollBack();

            throw $e;

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return [
                'status' => false,
                'message' => 'Unable to reset password.',
            ];
        }
    }

    /**
     * Verify Password
     *
     * Supports:
     * - BCrypt
     * - Argon2id
     */
    private function checkPassword(
        string $plainPassword,
        string $storedHash
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Existing BCrypt Password
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
        | Argon2id Password
        |--------------------------------------------------------------------------
        */

        if (str_starts_with($storedHash, '$argon2id$')) {

            return Hash::check(
                $plainPassword,
                $storedHash
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Unsupported / Invalid Hash
        |--------------------------------------------------------------------------
        */

        return false;
    }

    /**
     * Check Last 5 Passwords
     */
    private function checkPasswordHistory(
        User $user,
        string $password
    ): void {

        $histories = PasswordHistory::where(
                'user_id',
                $user->id
            )
            ->latest('changed_at')
            ->take(5)
            ->get();

        foreach ($histories as $history) {

            if ($this->checkPassword(
                $password,
                $history->password
            )) {

                throw ValidationException::withMessages([
                    'password' => 'You cannot reuse your last 5 passwords.',
                ]);
            }
        }
    }

    /**
     * Save Current Password To History
     */
    private function savePasswordHistory(User $user): void
    {
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
            'changed_at' => now(),
        ]);
    }

    /**
     * Update User Password
     *
     * Hash::make() uses configured Argon2id driver.
     */
    private function updatePassword(
        User $user,
        string $password,
        bool $refreshRememberToken = false
    ): void {

        $attributes = [
            'password' => Hash::make($password),

            'force_password_change' => false,

            'password_changed_at' => now(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Refresh Remember Token During Password Reset
        |--------------------------------------------------------------------------
        */

        if ($refreshRememberToken) {

            $attributes['remember_token'] = Str::random(60);
        }

        $user->forceFill($attributes)->save();
    }

    /**
     * Create Password Reset Login Log
     */
    private function createLoginLog(
        User $user,
        string $status
    ): void {

        LoginLog::create([
            'user_id' => $user->id,

            'employee_code' => $user->employee_code,

            'status' => $status,

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),

            'login_at' => now(),
        ]);
    }
}

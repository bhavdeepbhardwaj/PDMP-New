<?php

namespace App\Services;

use App\Models\PasswordHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use App\Models\LoginLog;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            */

            if (!Hash::check($data['current_password'], $user->password)) {

                throw ValidationException::withMessages([
                    'current_password' => 'Current password is incorrect.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Same Password
            |--------------------------------------------------------------------------
            */

            if (Hash::check($data['password'], $user->password)) {

                throw ValidationException::withMessages([
                    'password' => 'New password must be different from the current password.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Last 5 Passwords
            |--------------------------------------------------------------------------
            */

            $this->checkPasswordHistory($user, $data['password']);

            /*
            |--------------------------------------------------------------------------
            | Save Current Password to History
            |--------------------------------------------------------------------------
            */

           $this->savePasswordHistory($user);

            /*
            |--------------------------------------------------------------------------
            | Update Password
            |--------------------------------------------------------------------------
            */

            $this->updatePassword($user, $data['password']);

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

        if (! $user) {

            // return [

            //     'status' => false,

            //     'message' => 'Employee not found.',

            // ];

            return ServiceResponse::error(

            'Employee not found.'

            );
        }

        if ((int) $user->status !== 1) {

            return [

                'status' => false,

                'message' => 'Your account is inactive.',

            ];
        }

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

                    $this->checkPasswordHistory($user, $password);

                    /*
                    |--------------------------------------------------------------------------
                    | Save Current Password
                    |--------------------------------------------------------------------------
                    */

                    $this->savePasswordHistory($user);

                    /*
                    |--------------------------------------------------------------------------
                    | Update User Password
                    |--------------------------------------------------------------------------
                    */

                    $this->updatePassword($user, $password, true);

                    /*
                    |--------------------------------------------------------------------------
                    | Password Reset Event
                    |--------------------------------------------------------------------------
                    */

                    event(new PasswordReset($user));

                    /*
                    |--------------------------------------------------------------------------
                    | Login Log
                    |--------------------------------------------------------------------------
                    */

                    $this->createLoginLog($user, LoginLog::PASSWORD_RESET_SUCCESS);

                    /*
                    |--------------------------------------------------------------------------
                    | Optional Auto Login
                    |--------------------------------------------------------------------------
                    */

                    Auth::login($user);
                }

            );

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

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return [

                'status' => false,

                'message' => 'Unable to reset password.',

            ];
        }
    }

    private function checkPasswordHistory(User $user, string $password): void
    {
        $histories = PasswordHistory::where('user_id', $user->id)
            ->latest('changed_at')
            ->take(5)
            ->get();

        foreach ($histories as $history) {

            if (Hash::check($password, $history->password)) {

                throw ValidationException::withMessages([
                    'password' => 'You cannot reuse your last 5 passwords.',
                ]);
            }
        }
    }

    private function savePasswordHistory(User $user): void
    {
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
            'changed_at' => now(),
        ]);
    }

    private function updatePassword(User $user, string $password, bool $refreshRememberToken = false): void
    {
        $attributes = [
        'password' => Hash::make($password),
        'force_password_change' => false,
        'password_changed_at' => now(),
        ];

        if ($refreshRememberToken) {
            $attributes['remember_token'] = Str::random(60);
        }

        $user->forceFill($attributes)->save();
    }

    private function createLoginLog(User $user, string $status): void
    {
        LoginLog::create([
            'user_id'       => $user->id,
            'employee_code' => $user->employee_code,
            'status'        => $status,
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'login_at'      => now(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Services\PasswordService;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{
    public function __construct(
        protected PasswordService $passwordService
    ) {}

    /**
     * Show Change Password Page
     */
    public function index()
    {
        return view('auth.change-password');
    }

    /**
     * Update Password
     */
    public function update(ChangePasswordRequest $request)
    {
        try {

            $this->passwordService->changePassword(
                auth()->user(),
                $request->validated()
            );

            $request->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Password changed successfully.');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput(
                    $request->except([
                        'current_password',
                        'password',
                        'password_confirmation',
                    ])
                );
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput(
                    $request->except([
                        'current_password',
                        'password',
                        'password_confirmation',
                    ])
                )
                ->withErrors([
                    'password' => 'Unable to change password. Please try again.',
                ]);
        }
    }
}

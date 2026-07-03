<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Services\PasswordService;

class ResetPasswordController extends Controller
{
    /**
     * Constructor
     */
    public function __construct(
        protected PasswordService $passwordService
    ) {}

    /**
     * Show Reset Password Form
     */
    public function index(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request()->query('email'),
        ]);
    }

    /**
     * Reset User Password
     */
    public function update(ResetPasswordRequest $request)
    {
        try {

            $result = $this->passwordService->resetPassword(
                $request->validated()
            );

            if (! $result['status']) {

                return back()
                    ->withInput($request->except([
                        'password',
                        'password_confirmation',
                    ]))
                    ->withErrors([
                        'email' => $result['message'],
                    ]);
            }

            return redirect()
                ->route('login')
                ->with('success', $result['message']);

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->withErrors([
                    'email' => 'Unable to reset password. Please try again.',
                ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Services\PasswordService;

class ForgotPasswordController extends Controller
{
    /**
     * Constructor
     */
    public function __construct(
        protected PasswordService $passwordService
    ) {}

    /**
     * Show Forgot Password Page
     */
    public function index()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send Reset Link
     */
    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $result = $this->passwordService->sendResetLink(
            $request->validated()
        );

        if (! $result['status']) {

            return back()
                ->withInput()
                ->withErrors([
                    'employee_code' => $result['message'],
                ]);
        }

        return back()->with(
            'success',
            $result['message']
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected LoginService $loginService
    ) {}

    /**
     * Show Login Page
     */
    public function index()
    {
        $publicKeyPath = config('rsa.login.public_key');

        if (! is_file($publicKeyPath)) {
            report(
                new \RuntimeException(
                    'Login RSA public key not found.'
                )
            );

            abort(
                500,
                'Login security configuration error.'
            );
        }

        $rsaPublicKey = file_get_contents($publicKeyPath);

        return view('auth.login', [
            'rsaPublicKey' => $rsaPublicKey,
        ]);
    }

    /**
     * Authenticate User
     */
    public function login(LoginRequest $request)
    {
        $result = $this->loginService->login(
            $request->only('employee_code', 'password', 'captcha_code'),
            $request->boolean('remember'),
            $request
        );

        if (! $result['status']) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'employee_code' => $result['message'],
                ]);
        }

        if ($result['force_password_change']) {
            return redirect()->route('password.change');
        }

        return redirect()->route('dashboard')
            ->with('success', $result['message']);
    }

    /**
     * Logout User
     */
    public function logout(Request $request)
    {
        $this->loginService->logout($request);

        return redirect()
            ->route('login')
            ->with('success', 'Logged out successfully.');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        return view('auth.login');
    }

    /**
     * Authenticate User
     */
    public function login(LoginRequest $request)
    {
        $result = $this->loginService->login(
            $request->only('employee_code', 'password'),
            $request->boolean('remember'),
            $request
        );

        if (!$result['status']) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'employee_code' => $result['message']
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

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:8'
            ],
        ]);

        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->password),
            'force_password_change' => 0,
            'password_changed_at' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Password changed successfully.');
    }
}

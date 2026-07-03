<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

// Route::get('/test-mail', function () {

//     Mail::raw('This is a test email from Laravel.', function ($message) {
//         $message->to('bhavdeepbhardwaj555@gmail.com')
//             ->subject('Laravel Mail Test');
//     });

//     return 'Mail Sent';
// });

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'index'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'index'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'update'])
        ->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/change-password', [AuthController::class, 'showChangePassword'])
        ->name('password.change');

    Route::post('/change-password', [AuthController::class, 'changePassword'])
        ->name('password.update');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware([
    'auth',
    'employee.status',
    'force.password',
])->group(function () {

    Route::get('/change-password', [ChangePasswordController::class, 'index'])
        ->name('password.change');

    Route::post('/change-password', [ChangePasswordController::class, 'update'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Default Route
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

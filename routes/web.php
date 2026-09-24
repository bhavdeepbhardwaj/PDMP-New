<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Mail;
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

// Route::middleware([
//     'auth',
//     'employee.status',
// ])
//     ->prefix('employees')
//     ->name('employees.')
//     ->group(function () {

//         /*
//         |--------------------------------------------------------------------------
//         | Employee Index
//         |--------------------------------------------------------------------------
//         */

//         Route::get('/', [EmployeeController::class, 'index'])
//             ->middleware(
//                 'role.permission:' .
//                     'SUPERADMIN,' .
//                     'MINISTRY_NODAL_OFFICER,' .
//                     'STATE_MARITIME_BOARD_NODAL_OFFICER,' .
//                     'PORT_NODAL_OFFICER,' .
//                     'PORT_MANAGER,' .
//                     'DATA_ENTRY_OFFICER,' .
//                     'NIC'
//             )
//             ->name('index');


//         /*
//         |--------------------------------------------------------------------------
//         | Create
//         |--------------------------------------------------------------------------
//         */

//         Route::get('/create', [EmployeeController::class, 'create'])
//          ->middleware('role.permission:SUPERADMIN')
//             ->name('create');

//         Route::post('/', [EmployeeController::class, 'store'])
//          ->middleware('role.permission:SUPERADMIN')
//             ->name('store');


//         /*
//         |--------------------------------------------------------------------------
//         | Employee Record
//         |--------------------------------------------------------------------------
//         */

//         Route::get('/{employee}', [EmployeeController::class, 'show'])
//             ->middleware('user.access:employee')
//             ->name('show');

//         Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])
//             ->middleware('user.access:employee')
//             ->name('edit');

//         Route::put('/{employee}', [EmployeeController::class, 'update'])
//             ->middleware('user.access:employee')
//             ->name('update');

//         Route::delete('/{employee}', [EmployeeController::class, 'destroy'])
//             ->middleware('user.access:employee')
//             ->name('destroy');

//         Route::patch('/{employee}/status', [EmployeeController::class, 'changeStatus'])
//             ->middleware('user.access:employee')
//             ->name('status');
//     });


Route::get('/captcha/image', [CaptchaController::class, 'image'])
    ->name('captcha.image');

Route::middleware([
    'auth',
    'employee.status',
])
    ->prefix('employees')
    ->name('employees.')
    ->group(function () {

        /*
    |--------------------------------------------------------------------------
    | Employee Listing - VIEW
    |--------------------------------------------------------------------------
    */

        Route::get('/', [EmployeeController::class, 'index'])
            ->middleware(
                'role.permission:' .
                    'SUPERADMIN,' .
                    'MINISTRY_NODAL_OFFICER,' .
                    'STATE_MARITIME_BOARD_NODAL_OFFICER,' .
                    'PORT_NODAL_OFFICER,' .
                    'PORT_MANAGER,' .
                    'DATA_ENTRY_OFFICER,' .
                    'NIC'
            )
            ->name('index');

<<<<<<< HEAD
=======
            Route::get('/', [EmployeeController::class, 'indexV2'])
            ->middleware(
                'role.permission:' .
                    'SUPERADMIN,' .
                    'MINISTRY_NODAL_OFFICER,' .
                    'STATE_MARITIME_BOARD_NODAL_OFFICER,' .
                    'PORT_NODAL_OFFICER,' .
                    'PORT_MANAGER,' .
                    'DATA_ENTRY_OFFICER,' .
                    'NIC'
            )
            ->name('indexV2');

>>>>>>> 08427ed (V1.5)

        /*
    |--------------------------------------------------------------------------
    | Create - SUPERADMIN ONLY
    |--------------------------------------------------------------------------
    */

        Route::get('/create', [EmployeeController::class, 'create'])
            ->middleware('role.permission:SUPERADMIN')
            ->name('create');

        Route::post('/', [EmployeeController::class, 'store'])
            ->middleware('role.permission:SUPERADMIN')
            ->name('store');


        /*
    |--------------------------------------------------------------------------
    | Show - Scope Based
    |--------------------------------------------------------------------------
    */

        Route::get('/{employee}', [EmployeeController::class, 'show'])
            ->middleware('user.access:employee')
            ->name('show');


        /*
    |--------------------------------------------------------------------------
    | Edit - SUPERADMIN OR SELF
    |--------------------------------------------------------------------------
    */

        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])
            ->middleware('employee.action:edit')
            ->name('edit');


        /*
    |--------------------------------------------------------------------------
    | Update - SUPERADMIN OR SELF
    |--------------------------------------------------------------------------
    */

        Route::put('/{employee}', [EmployeeController::class, 'update'])
            ->middleware('employee.action:update')
            ->name('update');


        /*
    |--------------------------------------------------------------------------
    | Delete - SUPERADMIN ONLY
    |--------------------------------------------------------------------------
    */

        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])
            ->middleware('role.permission:SUPERADMIN')
            ->name('destroy');


        /*
    |--------------------------------------------------------------------------
    | Change Status - SUPERADMIN ONLY
    |--------------------------------------------------------------------------
    */

        Route::patch('/{employee}/status', [EmployeeController::class, 'changeStatus'])
            ->middleware('role.permission:SUPERADMIN')
            ->name('status');
    });



Route::middleware([
    'auth',
    'employee.status',
])->prefix('ajax/master')
    ->name('master.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Organization
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/organizations',
            [MasterDataController::class, 'organizations']
        )->name('organizations');

        Route::get(
            '/organizations/{organization}',
            [MasterDataController::class, 'organization']
        )->name('organization');

        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/departments',
            [MasterDataController::class, 'departments']
        )->name('departments');

        Route::get(
            '/departments/{department}',
            [MasterDataController::class, 'department']
        )->name('department');

        Route::get(
            '/organizations/{organization}/departments',
            [MasterDataController::class, 'departmentsByOrganization']
        )->name('departments.organization');

        /*
        |--------------------------------------------------------------------------
        | Role
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/roles',
            [MasterDataController::class, 'roles']
        )->name('roles');

        Route::get(
            '/roles/{role}',
            [MasterDataController::class, 'role']
        )->name('role');

        /*
        |--------------------------------------------------------------------------
        | Port Category
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/port-categories',
            [MasterDataController::class, 'portCategories']
        )->name('port-categories');

        Route::get(
            '/port-categories/{portCategory}',
            [MasterDataController::class, 'portCategory']
        )->name('port-category');

        /*
        |--------------------------------------------------------------------------
        | State
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/states',
            [MasterDataController::class, 'states']
        )->name('states');

        Route::get(
            '/states/{state}',
            [MasterDataController::class, 'state']
        )->name('state');

        /*
        |--------------------------------------------------------------------------
        | State Board
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/state-boards',
            [MasterDataController::class, 'stateBoards']
        )->name('state-boards');

        Route::get(
            '/state-boards/{stateBoard}',
            [MasterDataController::class, 'stateBoard']
        )->name('state-board');

        Route::get(
            '/states/{state}/state-boards',
            [MasterDataController::class, 'stateBoardsByState']
        )->name('state.state-boards');

        /*
        |--------------------------------------------------------------------------
        | Port
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/ports',
            [MasterDataController::class, 'ports']
        )->name('ports');

        Route::get(
            '/ports/{port}',
            [MasterDataController::class, 'port']
        )->name('port');

        Route::get(
            '/port-categories/{portType}/ports',
            [MasterDataController::class, 'portsByCategory']
        )->name('port-category.ports');

        Route::get(
            '/state-boards/{stateBoard}/ports',
            [
                MasterDataController::class,
                'portsByStateBoard'
            ]
        )->name('state-board.ports');

        /*
        |--------------------------------------------------------------------------
        | Reporting Officers
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reporting-officers',
            [MasterDataController::class, 'reportingOfficers']
        )->name('reporting-officers');
    });

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

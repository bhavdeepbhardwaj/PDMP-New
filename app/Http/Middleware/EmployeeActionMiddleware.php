<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Allowed usage:
     *
     * employee.action:edit
     * employee.action:update
     */
    public function handle(
        Request $request,
        Closure $next,
        string $action
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Please login to continue.'
                );
        }

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | User Status
        |--------------------------------------------------------------------------
        */

        if (
            !$user->status ||
            $user->is_deleted
        ) {
            abort(
                403,
                'Your account is inactive or unavailable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Role
        |--------------------------------------------------------------------------
        */

        $role = $user->role;

        if (!$role) {
            abort(
                403,
                'No role is assigned to your account.'
            );
        }

        if (
            !$role->status ||
            $role->is_deleted
        ) {
            abort(
                403,
                'Your assigned role is inactive.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Allowed Actions
        |--------------------------------------------------------------------------
        */

        $allowedActions = [
            'edit',
            'update',
        ];

        if (!in_array($action, $allowedActions, true)) {
            abort(
                403,
                'Invalid employee action.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Route Employee
        |--------------------------------------------------------------------------
        */

        $employee = $request->route('employee');

        if (!$employee) {
            abort(
                403,
                'Employee record was not specified.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Route Model Binding Safety
        |--------------------------------------------------------------------------
        */

        if (!$employee instanceof User) {

            $employee = User::query()
                ->whereKey($employee)
                ->where('is_deleted', false)
                ->first();

            if (!$employee) {
                abort(
                    404,
                    'Employee not found.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SUPERADMIN
        |--------------------------------------------------------------------------
        |
        | SUPERADMIN can edit/update anyone.
        |
        */

        $roleCode = strtoupper(
            trim((string) $role->role_code)
        );

        if ($roleCode === 'SUPERADMIN') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Other Roles
        |--------------------------------------------------------------------------
        |
        | Other roles can edit/update ONLY themselves.
        |
        */

        if ((int) $employee->id !== (int) $user->id) {

            abort(
                403,
                'You can only edit or update your own profile.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Self Access Allowed
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}

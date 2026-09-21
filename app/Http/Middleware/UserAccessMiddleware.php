<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * This middleware verifies whether the logged-in user
     * is allowed to access the requested employee record.
     *
     * Example:
     *
     * ->middleware('user.access:employee')
     */
    public function handle(
        Request $request,
        Closure $next,
        string $type = 'employee'
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login to continue.');
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
        | User Role
        |--------------------------------------------------------------------------
        */

        $role = $user->role;

        if (!$role) {
            abort(
                403,
                'No role is assigned to your account.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Role Status
        |--------------------------------------------------------------------------
        */

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
        | Only Employee Access For Now
        |--------------------------------------------------------------------------
        */

        if ($type !== 'employee') {
            abort(
                403,
                'Invalid access type.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Requested Employee
        |--------------------------------------------------------------------------
        |
        | Employee routes use:
        |
        | /employees/{employee}
        |
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
                abort(404, 'Employee not found.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Self Access
        |--------------------------------------------------------------------------
        */

        if ((int) $employee->id === (int) $user->id) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Access Scope
        |--------------------------------------------------------------------------
        */

        switch ($role->access_scope) {

            /*
            |--------------------------------------------------------------------------
            | ALL ACCESS
            |--------------------------------------------------------------------------
            */

            case 'ALL':

                return $next($request);


                /*
            |--------------------------------------------------------------------------
            | STATE BOARD ACCESS
            |--------------------------------------------------------------------------
            */

            case 'STATE_BOARD':

                if (
                    empty($user->state_board_id) ||
                    empty($employee->state_board_id)
                ) {
                    abort(
                        403,
                        'State Board access is not configured.'
                    );
                }

                if (
                    (int) $user->state_board_id !==
                    (int) $employee->state_board_id
                ) {
                    abort(
                        403,
                        'You are not authorized to access this employee.'
                    );
                }

                /*
                | Optional additional protection:
                | employee should have a valid assigned port.
                */

                if (
                    !$this->hasAssignedPortAccess(
                        $user,
                        $employee
                    )
                ) {
                    abort(
                        403,
                        'You are not authorized to access this employee.'
                    );
                }

                return $next($request);


                /*
            |--------------------------------------------------------------------------
            | PORT ACCESS
            |--------------------------------------------------------------------------
            */

            case 'PORT':

                if (
                    !$this->hasAssignedPortAccess(
                        $user,
                        $employee
                    )
                ) {
                    abort(
                        403,
                        'You are not authorized to access this employee.'
                    );
                }

                return $next($request);


                /*
            |--------------------------------------------------------------------------
            | CUSTOM ACCESS
            |--------------------------------------------------------------------------
            */

            case 'CUSTOM':

                /*
                |--------------------------------------------------------------------------
                | CUSTOM scope will be implemented when the
                | application's custom permission rules are defined.
                |--------------------------------------------------------------------------
                */

                abort(
                    403,
                    'Custom access rules are not configured.'
                );


                /*
            |--------------------------------------------------------------------------
            | Unknown Scope
            |--------------------------------------------------------------------------
            */

            default:

                abort(
                    403,
                    'Invalid access scope.'
                );
        }
    }

    /**
     * Check whether the employee belongs to a port
     * assigned to the logged-in user.
     */
    private function hasAssignedPortAccess(
        User $user,
        User $employee
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Employee must have a port
        |--------------------------------------------------------------------------
        */

        if (empty($employee->port_id)) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Direct Single Port Assignment
        |--------------------------------------------------------------------------
        |
        | Used by PORT roles.
        |
        */

        if (
            !empty($user->port_id) &&
            (int) $user->port_id ===
            (int) $employee->port_id
        ) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | Multiple Port Assignment
        |--------------------------------------------------------------------------
        |
        | Used by STATE_BOARD roles.
        |
        */

        return $user->assignedPorts()
            ->where('ports.id', $employee->port_id)
            ->wherePivot('status', true)
            ->wherePivot('is_deleted', false)
            ->exists();
    }
}

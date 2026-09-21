<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePermission
{
    /**
     * Handle an incoming request.
     *
     * Usage:
     *
     * ->middleware('role.permission:SUPERADMIN,MINISTRY_NODAL_OFFICER')
     *
     * Only the specified roles will be allowed.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$allowedRoles
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Authentication Check
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {

            return redirect()
                ->route('login')
                ->with('error', 'Please login to continue.');
        }

        /*
        |--------------------------------------------------------------------------
        | Current User
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Employee Status Check
        |--------------------------------------------------------------------------
        |
        | employee.status middleware already handles this globally,
        | but keeping authorization safe here as well.
        |
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
        | Role Check
        |--------------------------------------------------------------------------
        */

        $userRole = $user->role;

        if (!$userRole) {

            abort(
                403,
                'No role is assigned to your account.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Active Role Check
        |--------------------------------------------------------------------------
        */

        if (
            !$userRole->status ||
            $userRole->is_deleted
        ) {

            abort(
                403,
                'Your assigned role is inactive.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | No Roles Configured
        |--------------------------------------------------------------------------
        |
        | If middleware is used without specifying roles, deny access.
        | This prevents accidental unrestricted access.
        |
        */

        if (empty($allowedRoles)) {

            abort(
                403,
                'Access role is not configured.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize Allowed Roles
        |--------------------------------------------------------------------------
        */

        $allowedRoles = collect($allowedRoles)

            ->map(
                fn($role) => strtoupper(trim($role))
            )

            ->filter()

            ->values();

        /*
        |--------------------------------------------------------------------------
        | Current Role Code
        |--------------------------------------------------------------------------
        */

        $currentRole = strtoupper(
            trim((string) $userRole->role_code)
        );

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if (
            !$allowedRoles->contains($currentRole)
        ) {

            abort(
                403,
                'You are not authorized to access this resource.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Continue Request
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}

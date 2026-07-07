<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\User;

class AuditLogService
{
    public function login(

        ?User $user,

        string $employeeCode,

        string $status

    ): void {

        LoginLog::create([

            'user_id'=>$user?->id,

            'employee_code'=>$employeeCode,

            'status'=>$status,

            'ip_address'=>request()->ip(),

            'user_agent'=>request()->userAgent(),

            'login_at'=>now(),

        ]);

    }
}

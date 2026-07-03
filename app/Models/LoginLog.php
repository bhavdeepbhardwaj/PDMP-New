<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Login Status Constants
    |--------------------------------------------------------------------------
    */

    public const SUCCESS = 'Login Successful';

    public const FAILED_EMPLOYEE = 'Failed - Employee Not Found';

    public const FAILED_PASSWORD = 'Failed - Wrong Password';

    public const FAILED_INACTIVE = 'Failed - Account Inactive';

    public const FAILED_CAPTCHA = 'Failed - Wrong Captcha';

    public const LOGOUT = 'Logged Out';

    public const PASSWORD_RESET_SUCCESS = 'Password Reset Successful';

    /**
     * Table Name
     */
    protected $table = 'login_logs';

    /**
     * Fillable
     */
    protected $fillable = [
        'user_id',
        'employee_code',
        'ip_address',
        'user_agent',
        'status',
        'login_at',
        'logout_at',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'login_at'   => 'datetime',
        'logout_at'  => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope : Successful Logins
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::SUCCESS);
    }

    /**
     * Scope : Failed Logins
     */
    public function scopeFailed($query)
    {
        return $query->where('status', '!=', self::SUCCESS);
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [

        'employee_code',

        'title',

        'name',

        'first_name',

        'middle_name',

        'last_name',

        'state_id',

        'port_type',

        'state_board',

        'port_id',

        'role_id',

        'dep_id',

        'organization_id',

        'status',

        'report_to',

        'extra_module',

        'email',

        'username',

        'mobile_number',

        'official_address',

        'password',

        'force_password_change',

        'password_changed_at',

        'created_by',

        'updated_by',

        'is_deleted',

        'remember_token',

    ];

    protected $casts = [

        'email_verified_at' => 'datetime',

        'password_changed_at' => 'datetime',

        'extra_module' => 'array',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Password History
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }
}

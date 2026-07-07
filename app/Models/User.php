<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Organization;
use App\Models\Department;
use App\Models\Role;
use App\Models\State;
use App\Models\StateBoard;
use App\Models\Port;

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

        'port_type_id',

        'state_board_id',

        'port_id',

        'role_id',

        'department_id',

        'organization_id',

        'status',

        'report_to_user_id',

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

        'password' => 'hashed',

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

    /**
     * Organization Relationship
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Department Relationship
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Role Relationship
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * State Relationship
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * State Board Relationship
     */
    public function stateBoard()
    {
        return $this->belongsTo(StateBoard::class, 'state_board_id');
    }

    /**
     * Port Relationship
     */
    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    /**
     * Reporting Officer Relationship
     */
    public function reportingOfficer()
    {
        return $this->belongsTo(User::class, 'report_to_user_id');
    }

    /**
     * Employee ka full name {{ $employee->full_name }}
     */
    public function getFullNameAttribute(): string
    {
        return trim(
            "{$this->first_name} {$this->middle_name} {$this->last_name}"
        );
    }
}

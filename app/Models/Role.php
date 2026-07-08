<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Access Scope Constants
    |--------------------------------------------------------------------------
    */

    public const ACCESS_ALL = 'ALL';

    public const ACCESS_STATE_BOARD = 'STATE_BOARD';

    public const ACCESS_PORT = 'PORT';

    public const ACCESS_CUSTOM = 'CUSTOM';

    /*
    |--------------------------------------------------------------------------
    | Assignment Type Constants
    |--------------------------------------------------------------------------
    */

    public const ASSIGN_ALL = 'ALL';

    public const ASSIGN_SINGLE = 'SINGLE';

    public const ASSIGN_MULTIPLE = 'MULTIPLE';

    public const ASSIGN_CUSTOM = 'CUSTOM';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'role_code',

        'role_name',

        'access_scope',

        'assignment_type',

        'description',

        'status',

        'is_deleted',

    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'status' => 'boolean',

        'is_deleted' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Employees
     */
    public function users()
    {
        return $this->hasMany(
            User::class,
            'role_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check Full Access
     */
    public function hasFullAccess(): bool
    {
        return $this->access_scope === self::ACCESS_ALL;
    }

    /**
     * Check State Board Access
     */
    public function hasStateBoardAccess(): bool
    {
        return $this->access_scope === self::ACCESS_STATE_BOARD;
    }

    /**
     * Check Port Access
     */
    public function hasPortAccess(): bool
    {
        return $this->access_scope === self::ACCESS_PORT;
    }

    /**
     * Check Single Assignment
     */
    public function isSingleAssignment(): bool
    {
        return $this->assignment_type === self::ASSIGN_SINGLE;
    }

    /**
     * Check Multiple Assignment
     */
    public function isMultipleAssignment(): bool
    {
        return $this->assignment_type === self::ASSIGN_MULTIPLE;
    }

    /**
     * Check All Assignment
     */
    public function isAllAssignment(): bool
    {
        return $this->assignment_type === self::ASSIGN_ALL;
    }

    /**
     * Active Scope
     */
    public function scopeActive($query)
    {
        return $query
            ->where('status', true)
            ->where('is_deleted', false);
    }

    /**
     * Order Scope
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('role_name');
    }
}

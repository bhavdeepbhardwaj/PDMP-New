<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model
{
    /**
     * Table Name
     */
    protected $table = 'password_histories';

    /**
     * Fillable
     */
    protected $fillable = [
        'user_id',
        'password',
        'changed_at',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'changed_at' => 'datetime',
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
     * Scope : Latest Password History
     */
    public function scopeLatestHistory($query)
    {
        return $query->latest('changed_at');
    }

    /**
     * Scope : By User
     */
    public function scopeOfUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}

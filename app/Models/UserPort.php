<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPort extends Model
{
    protected $fillable = [

        'user_id',

        'port_id',

        'is_primary',

        'status',

        'is_deleted',

        'created_by',

        'updated_by',

    ];

    protected $casts = [

        'is_primary' => 'boolean',

        'status' => 'boolean',

        'is_deleted' => 'boolean',

    ];

    /**
     * User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Port
     */
    public function port()
    {
        return $this->belongsTo(Port::class);
    }
}

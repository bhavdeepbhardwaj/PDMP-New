<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    protected $fillable = [

        'state_id',

        'state_board_id',

        'port_type_id',

        'port_name',

        'port_code',

        'port_data_code',

        'description',

        'status',

        'is_deleted',

    ];

    /**
     * Users
     */
    public function users()
    {
        return $this->belongsToMany(

            User::class,

            'user_ports',

            'port_id',

            'user_id'

        )
            ->withPivot([

                'is_primary',

                'status',

                'is_deleted',

            ])
            ->withTimestamps();
    }
}

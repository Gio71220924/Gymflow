<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    protected $table = 'gym_classes';

    protected $fillable = [
        'title',
        'description',
        'level',
        'capacity',
        'location',
        'start_at',
        'end_at',
        'type',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];
}

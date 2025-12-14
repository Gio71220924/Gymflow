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
        'photo',
        'status',
        'trainer_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    public function bookings()
    {
        return $this->hasMany(ClassBooking::class, 'class_id');
    }
}

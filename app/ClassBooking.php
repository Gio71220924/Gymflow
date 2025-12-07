<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassBooking extends Model
{
    protected $table = 'class_bookings';

    protected $fillable = [
        'class_id',
        'member_id',
        'status',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    public function member()
    {
        return $this->belongsTo(Member_Gym::class, 'member_id');
    }

    public function isActive()
    {
        return in_array($this->status, ['booked', 'attended', 'no_show'], true);
    }
}

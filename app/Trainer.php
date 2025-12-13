<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'photo_url',
        'experience_years',
        'rating_avg',
        'hourly_rate',
        'status',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OneOnOneRequest extends Model
{
    protected $table = 'one_on_one_requests';

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'member_id',
        'trainer_id',
        'preferred_date',
        'preferred_time',
        'location',
        'note',
        'status',
        'admin_note',
        'approved_by',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member_Gym::class, 'member_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

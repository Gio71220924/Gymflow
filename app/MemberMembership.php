<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Member_Gym;
use App\MembershipPlan;
use App\Invoice;

class MemberMembership extends Model
{
    protected $table = 'member_memberships';

    protected $fillable = [
        'member_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'pembayaran_status',
        'catatan',
    ];

    public function member()
    {
        return $this->belongsTo(Member_Gym::class, 'member_id');
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'member_membership_id');
    }
}

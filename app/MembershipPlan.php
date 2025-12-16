<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MemberMembership;

class MembershipPlan extends Model
{
    protected $table = 'membership_plans';

    protected $fillable = [
        'nama',
        'harga',
        'durasi_bulan',
        'benefit',
        'status',
    ];

    public function memberMemberships()
    {
        return $this->hasMany(MemberMembership::class, 'plan_id');
    }
}

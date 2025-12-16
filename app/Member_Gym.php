<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\MemberMembership;

class Member_Gym extends Model
{
    //
    protected $table = 'member_gym';

    protected $fillable = [
    'user_id',
    'id_member',
    'nama_member',
    'email_member',
    'nomor_telepon_member',
    'tanggal_lahir',
    'gender',
    'tanggal_join',
    'membership_plan',
    'durasi_plan',
    'start_date',
    'end_date',
    'status_membership',
    'notes',
    'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function memberMemberships()
    {
        return $this->hasMany(MemberMembership::class, 'member_id');
    }

    public function activeMembership()
    {
        return $this->hasOne(MemberMembership::class, 'member_id')->where('status', 'aktif')->latest('id');
    }

    public function latestMembership()
    {
        return $this->hasOne(MemberMembership::class, 'member_id')->latest('id');
    }
}

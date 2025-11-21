<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

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
}

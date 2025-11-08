<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Member_Gym extends Model
{
    //
    protected $table = 'member_Gym';

    protected $fillable = [
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

}

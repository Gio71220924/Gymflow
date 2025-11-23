<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MemberMembership;
use App\Payment;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'member_membership_id',
        'nomor_invoice',
        'due_date',
        'total_tagihan',
        'diskon',
        'pajak',
        'status',
        'catatan',
    ];

    public function memberMembership()
    {
        return $this->belongsTo(MemberMembership::class, 'member_membership_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}

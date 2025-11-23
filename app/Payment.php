<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invoice;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'invoice_id',
        'amount',
        'method',
        'paid_at',
        'status',
        'bukti_bayar',
        'reference_no',
        'catatan',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}

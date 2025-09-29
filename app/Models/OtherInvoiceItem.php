<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'other_invoice_id',
        'detail',
        'amount',
    ];

    public function otherInvoice()
    {
        return $this->belongsTo(OtherInvoice::class);
    }
}

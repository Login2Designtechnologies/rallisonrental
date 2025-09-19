<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherInvoiceDetail extends Model
{
    protected $fillable = ['other_invoice_id', 'item', 'qty', 'price', 'line_total'];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(OtherInvoice::class, 'other_invoice_id');
    }
}

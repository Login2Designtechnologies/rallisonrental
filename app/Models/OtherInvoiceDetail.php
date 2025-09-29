<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherInvoiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'other_invoice_id',
        'item',
        'qty',
        'price',
        'line_total',
    ];
    
    public function invoice()
    {
        return $this->belongsTo(OtherInvoice::class, 'other_invoice_id');
    }
}

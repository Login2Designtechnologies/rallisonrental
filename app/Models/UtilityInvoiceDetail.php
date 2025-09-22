<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityInvoiceDetail extends Model
{
    protected $fillable = [
        'invoice_id',
        'tenant_id',
        'property_utility_id',
        'category',
        'amount',
        'start_date',
        'end_date'
    ];

    public function invoice()
    {
        return $this->belongsTo(UtilityInvoice::class, 'invoice_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function propertyUtility()
    {
        return $this->belongsTo(PropertyUtility::class);
    }
}

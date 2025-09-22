<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantContract extends Model
{
    protected $fillable = [
        'tenant_id',
        'property_id',
        'owner_id',
        'contract_renewal_amount',
        'contract_doc',
        'contract_renewal_month',

        // newly added fields
        'start_date',
        'end_date',
        'standard_rent',
        'late_fee',
        'security_deposit',
        'notice_period_months',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'standard_rent' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'contract_renewal_amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class);
    }
    public function owner()
    {
        return $this->belongsTo(\App\Models\Owner::class);
    }
}

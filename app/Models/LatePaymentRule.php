<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatePaymentRule extends Model
{
    protected $fillable = [
        'tenant_contract_id',
        'tier',
        'grace_days',
        'time',
        'amount',
    ];

    public function contract()
    {
        return $this->belongsTo(TenantContract::class, 'tenant_contract_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractRenewal extends Model
{
    protected $fillable = [
        'tenant_contract_id',
        'amount_increase',
        'start_month',
        'end_month',
    ];

    public function contract()
    {
        return $this->belongsTo(TenantContract::class, 'tenant_contract_id');
    }
}

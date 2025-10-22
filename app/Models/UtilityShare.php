<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityShare extends Model
{
    use HasFactory;

    protected $table = 'utility_shares';
    protected $fillable = [
        'property_id', 
        'utility_id', 
        'tenant_id',
        'invoice_month', 
        'price', 
        'percentage', 
        'amount',
    ];
}

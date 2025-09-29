<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no', 
        'property_id', 
        'owner_id', 
        'tenant_id',
        'subject', 
        'invoice_date', 
        'due_date', 
        'terms',
        'status', 
        'amount', 
        'pay_url'
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];


    public static function generateInvoiceNo() {
        return 'INV-' . mt_rand(100000, 999999);
    }

    public function tenant() {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function property() {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }   

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OtherInvoiceDetail::class, 'other_invoice_id');
    }

}

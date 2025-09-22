<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityInvoice extends Model
{
    protected $table = 'utility_invoices';
    protected $fillable = [
        'property_id','owner_id','tenant_id',
        'invoice_number','invoice_date','invoice_month','due_date',
        'amount','status'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'amount'       => 'decimal:2',
    ];

    // Relationships
    public function property() { return $this->belongsTo(Property::class); }
    public function owner()    { return $this->belongsTo(Owner::class); }
    public function tenant()   { return $this->belongsTo(Tenant::class); }
    public function details() {  return $this->hasMany(UtilityInvoiceDetail::class, 'invoice_id');}

    // Scopes
    public function scopeFilters($q, array $filters)
    {
        return $q
            ->when($filters['status'] ?? null, fn($qq,$v) => $qq->where('status',$v))
            ->when($filters['property_id'] ?? null, fn($qq,$v) => $qq->where('property_id',$v))
            ->when($filters['tenant_id'] ?? null, fn($qq,$v) => $qq->where('tenant_id',$v))
            ->when($filters['month'] ?? null, fn($qq,$v) => $qq->where('invoice_month',$v))
            ->when($filters['q'] ?? null, fn($qq,$v) => $qq->where('invoice_number','like',"%{$v}%"));
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && $this->due_date && now()->toDateString() > $this->due_date->toDateString();
    }
}

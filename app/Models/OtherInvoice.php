<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherInvoice extends Model
{
   protected $fillable = [
        'property_id','owner_id','tenant_id','subject',
        'invoice_date','due_date','terms','status','amount',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'amount'       => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(OtherInvoiceDetail::class);
    }
    public function tenant(){
        return $this->belongsTo(Tenant::class);
    }
    public function owner(){
         return $this->belongsTo(Owner::class);
    }
     public function property(){
         return $this->belongsTo(Property::class);
    }

    // Optional helpers
    public function recalcAmount(): void
    {
        $this->amount = $this->details()->sum('line_total');
        $this->save();
    }
}

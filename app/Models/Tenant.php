<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
     protected $fillable = [
        'user_id','address','state_id','city_id','zip_code',
        'application_document','driving_licence','bank_statement',
        'family_member','lease_start_date','lease_end_date',
        'property_id','property_unit_id',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function state(){ return $this->belongsTo(State::class); }
    public function city(){ return $this->belongsTo(City::class); }
    public function property(){ return $this->belongsTo(Property::class); }
    public function unit(){ return $this->belongsTo(PropertyUnit::class,'property_unit_id'); }
    public function utility_invoices(){ return $this->hasMany(UtilityInvoice::class); }
     public function other_invoices(){
        return $this->hasMany(OtherInvoice::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'package_expire_date',
        'is_active',
        'disable_reason',
        'lang',
        'late_fee_setup'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'package_expire_date' => 'date','late_fee' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
    public function utility_invoices()
    {
        return $this->hasMany(UtilityInvoice::class);
    }
    public function notices(){
        return $this->hasMany(Notice::class);
    }
    public function other_invoices(){
        return $this->hasMany(OtherInvoice::class);
    }
}

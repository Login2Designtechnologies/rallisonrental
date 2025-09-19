<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;


class Property extends Model
{
    // use InteractsWithMedia;
    public function registerMediaCollections(): void
    {
        // Store images as-is on 'public' (or your disk)
        $this->addMediaCollection('images')->useDisk('public');
    }
    protected $fillable = [
        'type',
        'name',
        'owner_id',
        'description',
        'map_link',
        'address',
        'state_id',
        'city_id',
        'zip_code',
        'thumbnail',
        'has_amenities',
        'has_unit',
        'has_utility',
        'is_active',
    ];

    protected $casts = [
        'has_amenities' => 'bool',
        'has_unit' => 'bool',
        'has_utility' => 'bool',
        'is_active' => 'bool',
    ];

    public function units()
    {
        return $this->hasMany(PropertyUnit::class);
    }
    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }
    public function utilities()
    {
        return $this->hasMany(PropertyUtility::class);
    }
     public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
    public function other_invoices(){
        return $this->hasMany(OtherInvoice::class);
    }
}

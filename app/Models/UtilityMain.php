<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityMain extends Model
{
    use HasFactory;

    protected $table = 'utilities_main';
    protected $fillable = [
        'property_id', 
        'user_id', 
        'name', 
        'status'
    ];

    public function subcategories()
    {
        return $this->hasMany(UtilitySub::class, 'utility_main_id');
    }
}

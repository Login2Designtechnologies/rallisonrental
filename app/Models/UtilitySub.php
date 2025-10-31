<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilitySub extends Model
{
    use HasFactory;

    protected $table = 'utilities_sub';
    protected $fillable = ['utility_main_id', 'sub_category_name', 'status'];

    public function main()
    {
        return $this->belongsTo(UtilityMain::class, 'utility_main_id');
    }
}

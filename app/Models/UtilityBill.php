<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'utility_id',
        'invoice_month',
        'file_path',
        'file_name',
        'uploaded_by',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function utility()
    {
        return $this->belongsTo(UtilitiesCatg::class, 'utility_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

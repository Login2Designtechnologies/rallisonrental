<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'random_id',
        'subject',
        'description',
        'status',
        'category',
        'photo',
        'tenant_id',
        'property_id',
    ];
}

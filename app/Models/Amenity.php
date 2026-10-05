<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'category',
        'icon',
        'is_filter',
        'display_order',
    ];

    protected $casts = [
        'is_filter' => 'boolean',
        'display_order' => 'integer',
    ];
}

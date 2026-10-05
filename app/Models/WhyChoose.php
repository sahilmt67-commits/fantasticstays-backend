<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChoose extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}

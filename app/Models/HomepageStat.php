<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageStat extends Model
{
    protected $fillable = [
        'value',
        'label',
        'source',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}

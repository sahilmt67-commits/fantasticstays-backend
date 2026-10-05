<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageFaq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}

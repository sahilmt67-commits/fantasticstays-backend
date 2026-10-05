<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepagePost extends Model
{
    protected $fillable = [
        'published_label',
        'title',
        'excerpt',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}

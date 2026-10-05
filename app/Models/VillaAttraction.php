<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillaAttraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'villa_id',
        'name',
        'distance',
        'category',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }
}

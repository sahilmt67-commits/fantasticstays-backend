<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillaRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'villa_id',
        'room_name',
        'bed_type',
        'image',
        'ensuite_bath',
        'description',
        'display_order',
    ];

    protected $casts = [
        'ensuite_bath' => 'boolean',
        'display_order' => 'integer',
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'villa_id',
        'guest_name',
        'guest_location',
        'guest_avatar',
        'rating',
        'cleanliness_rating',
        'accuracy_rating',
        'communication_rating',
        'location_rating',
        'value_rating',
        'comment',
        'stay_date',
        'is_approved',
        'is_featured',
    ];

    protected $casts = [
        'rating' => 'float',
        'cleanliness_rating' => 'float',
        'accuracy_rating' => 'float',
        'communication_rating' => 'float',
        'location_rating' => 'float',
        'value_rating' => 'float',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }
}

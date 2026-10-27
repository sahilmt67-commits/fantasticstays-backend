<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'villa_id',
        'villa_name',
        'name',
        'email',
        'phone',
        'check_in',
        'check_out',
        'guests',
        'adults',
        'children',
        'infants',
        'pets',
        'preferred_location',
        'message',
        'source',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'guests' => 'integer',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
        'pets' => 'integer',
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }
}

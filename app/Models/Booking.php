<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'villa_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in',
        'check_out',
        'adults',
        'children',
        'total_guests',
        'nights',
        'price_per_night',
        'subtotal',
        'taxes',
        'total_amount',
        'status',
        'payment_status',
        'special_requests',
        'admin_notes',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'total_guests' => 'integer',
        'nights' => 'integer',
        'price_per_night' => 'float',
        'subtotal' => 'float',
        'taxes' => 'float',
        'total_amount' => 'float',
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }
}

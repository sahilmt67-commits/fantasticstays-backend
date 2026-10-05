<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Enquiry;
use App\Models\Villa;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $sunset = Villa::where('slug', 'sunset-villa-luxury')->first();
        $serenity = Villa::where('slug', 'casa-serenity')->first();
        $palmar = Villa::where('slug', 'villa-palmar')->first();

        if ($sunset) {
            Booking::create([
                'booking_number' => 'FS-2026-101',
                'villa_id' => $sunset->id,
                'guest_name' => 'Kunal Kapoor',
                'guest_email' => 'kunal.kapoor@example.com',
                'guest_phone' => '+91 98200 11223',
                'check_in' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'check_out' => Carbon::now()->addDays(9)->format('Y-m-d'),
                'adults' => 6,
                'children' => 2,
                'total_guests' => 8,
                'nights' => 4,
                'price_per_night' => $sunset->price_per_night,
                'subtotal' => $sunset->price_per_night * 4,
                'taxes' => ($sunset->price_per_night * 4) * 0.18,
                'total_amount' => ($sunset->price_per_night * 4) * 1.18,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => 'Airport pickup required for 8 guests with luggage. Private chef for dinner on Day 1.',
                'admin_notes' => 'VIP guests. Arranged Mercedes Sprinter for airport transfer.',
            ]);

            Booking::create([
                'booking_number' => 'FS-2026-102',
                'villa_id' => $sunset->id,
                'guest_name' => 'Meera Nambiar',
                'guest_email' => 'meera.nambiar@example.com',
                'guest_phone' => '+91 98111 44556',
                'check_in' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'check_out' => Carbon::now()->addDays(17)->format('Y-m-d'),
                'adults' => 4,
                'children' => 0,
                'total_guests' => 4,
                'nights' => 3,
                'price_per_night' => $sunset->price_per_night,
                'subtotal' => $sunset->price_per_night * 3,
                'taxes' => ($sunset->price_per_night * 3) * 0.18,
                'total_amount' => ($sunset->price_per_night * 3) * 1.18,
                'status' => 'pending',
                'payment_status' => 'partial',
                'special_requests' => 'Early check-in requested at 11 AM.',
            ]);
        }

        if ($serenity) {
            Booking::create([
                'booking_number' => 'FS-2026-103',
                'villa_id' => $serenity->id,
                'guest_name' => 'Arjun Singhal',
                'guest_email' => 'arjun.singhal@example.com',
                'guest_phone' => '+91 97170 88990',
                'check_in' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'check_out' => Carbon::now()->addDays(24)->format('Y-m-d'),
                'adults' => 8,
                'children' => 2,
                'total_guests' => 10,
                'nights' => 4,
                'price_per_night' => $serenity->price_per_night,
                'subtotal' => $serenity->price_per_night * 4,
                'taxes' => ($serenity->price_per_night * 4) * 0.18,
                'total_amount' => ($serenity->price_per_night * 4) * 1.18,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => 'Family reunion with elderly parents. Ground floor bedrooms required.',
            ]);
        }

        // Sample Enquiries
        Enquiry::create([
            'villa_id' => $sunset?->id,
            'villa_name' => 'Sunset Villa Luxury',
            'name' => 'Tanvi Mehta',
            'email' => 'tanvi.m@gmail.com',
            'phone' => '+91 98330 99881',
            'check_in' => Carbon::now()->addDays(12)->format('Y-m-d'),
            'check_out' => Carbon::now()->addDays(16)->format('Y-m-d'),
            'guests' => 8,
            'message' => 'Hi, looking to book for our anniversary weekend. Can you arrange private poolside dining with candlelight?',
            'source' => 'whatsapp',
            'status' => 'new',
        ]);

        Enquiry::create([
            'villa_id' => $palmar?->id,
            'villa_name' => 'Villa Palmar',
            'name' => 'Devansh Singhania',
            'email' => 'devansh@singhania.co',
            'phone' => '+91 98450 12345',
            'check_in' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'check_out' => Carbon::now()->addDays(28)->format('Y-m-d'),
            'guests' => 6,
            'message' => 'Interested in Villa Palmar. Is high-speed internet reliable for remote work?',
            'source' => 'website',
            'status' => 'contacted',
            'admin_notes' => 'Sent speed test proof over WhatsApp. Guest is ready to confirm.',
        ]);
    }
}

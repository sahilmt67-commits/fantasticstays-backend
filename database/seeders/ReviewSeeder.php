<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Villa;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $sunset = Villa::where('slug', 'sunset-villa-luxury')->first();
        $serenity = Villa::where('slug', 'casa-serenity')->first();

        $reviews = [
            [
                'villa_id' => $sunset?->id ?? 1,
                'guest_name' => 'Aditi & Rohan Sharma',
                'guest_location' => 'Mumbai, India',
                'rating' => 5.0,
                'cleanliness_rating' => 5.0,
                'accuracy_rating' => 5.0,
                'communication_rating' => 5.0,
                'location_rating' => 4.9,
                'value_rating' => 5.0,
                'comment' => 'Our 4-day stay at Sunset Villa was simply unmatched. The staff treated us like royalty, the private pool was heated and crystal clear, and the chef made the best coastal Goan prawns we have ever tasted. We are already planning our next stay!',
                'stay_date' => 'February 2026',
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'villa_id' => $sunset?->id ?? 1,
                'guest_name' => 'Vikram Malhotra',
                'guest_location' => 'Bangalore, India',
                'rating' => 4.9,
                'cleanliness_rating' => 5.0,
                'accuracy_rating' => 4.9,
                'communication_rating' => 5.0,
                'location_rating' => 5.0,
                'value_rating' => 4.8,
                'comment' => 'Assagao is the place to be, and this villa sits right in the quietest, greenest corner. The rooms are grand with high ceilings and linen beds. Superb Wi-Fi allowed me to take a few Zoom calls while the family enjoyed the pool.',
                'stay_date' => 'January 2026',
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'villa_id' => $serenity?->id ?? 2,
                'guest_name' => 'Priya & Kabir Sen',
                'guest_location' => 'Delhi NCR',
                'rating' => 5.0,
                'cleanliness_rating' => 5.0,
                'accuracy_rating' => 5.0,
                'communication_rating' => 5.0,
                'location_rating' => 5.0,
                'value_rating' => 4.9,
                'comment' => 'Casa Serenity lives up to its name. The architecture is pure poetry — laterite stone arches, frangipani blossoms dropping into the pool, and an effortless concierge team that booked our tables at Sublime and Bawri without any fuss.',
                'stay_date' => 'December 2025',
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'villa_id' => $serenity?->id ?? 2,
                'guest_name' => 'Marcus van der Bilt',
                'guest_location' => 'Amsterdam, Netherlands',
                'rating' => 4.8,
                'cleanliness_rating' => 4.9,
                'accuracy_rating' => 4.8,
                'communication_rating' => 4.9,
                'location_rating' => 4.8,
                'value_rating' => 4.7,
                'comment' => 'Outstanding property in North Goa. Quiet, very spacious, and exceptional maintenance. Fantastic Stays concierge organized private airport transfers and motorcycle rentals seamlessly.',
                'stay_date' => 'November 2025',
                'is_approved' => true,
                'is_featured' => true,
            ],
        ];

        foreach ($reviews as $rev) {
            Review::create($rev);
        }
    }
}

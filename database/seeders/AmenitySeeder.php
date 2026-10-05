<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Private swimming pool', 'key' => 'pool', 'category' => 'Outdoor', 'icon' => 'bi-water', 'is_filter' => true, 'display_order' => 1],
            ['name' => 'Beachfront or near beach', 'key' => 'beach', 'category' => 'Location', 'icon' => 'bi-umbrella', 'is_filter' => true, 'display_order' => 2],
            ['name' => 'Pet-friendly villas', 'key' => 'pet', 'category' => 'General', 'icon' => 'bi-heart', 'is_filter' => true, 'display_order' => 3],
            ['name' => 'Chef service', 'key' => 'chef', 'category' => 'Services', 'icon' => 'bi-cup-hot', 'is_filter' => true, 'display_order' => 4],
            ['name' => 'Housekeeping', 'key' => 'hk', 'category' => 'Services', 'icon' => 'bi-stars', 'is_filter' => true, 'display_order' => 5],
            ['name' => 'Family-friendly stays', 'key' => 'family', 'category' => 'General', 'icon' => 'bi-people', 'is_filter' => true, 'display_order' => 6],
            ['name' => 'Event-friendly villas', 'key' => 'event', 'category' => 'General', 'icon' => 'bi-calendar-event', 'is_filter' => true, 'display_order' => 7],
            ['name' => 'Sea view', 'key' => 'sea', 'category' => 'Location', 'icon' => 'bi-compass', 'is_filter' => true, 'display_order' => 8],
            ['name' => 'Instant booking', 'key' => 'instant', 'category' => 'Booking', 'icon' => 'bi-lightning-charge', 'is_filter' => true, 'display_order' => 9],
            ['name' => 'High-Speed Wi-Fi', 'key' => 'wifi', 'category' => 'Comfort', 'icon' => 'bi-wifi', 'is_filter' => false, 'display_order' => 10],
            ['name' => 'Air Conditioning', 'key' => 'ac', 'category' => 'Comfort', 'icon' => 'bi-snow', 'is_filter' => false, 'display_order' => 11],
            ['name' => '100% Power Backup', 'key' => 'backup', 'category' => 'Comfort', 'icon' => 'bi-battery-charging', 'is_filter' => false, 'display_order' => 12],
            ['name' => 'Dedicated Staff', 'key' => 'staff', 'category' => 'Services', 'icon' => 'bi-person-badge', 'is_filter' => false, 'display_order' => 13],
            ['name' => 'Free Private Parking', 'key' => 'parking', 'category' => 'Comfort', 'icon' => 'bi-p-circle', 'is_filter' => false, 'display_order' => 14],
            ['name' => 'BBQ Grill & Bar setup', 'key' => 'bbq', 'category' => 'Outdoor', 'icon' => 'bi-fire', 'is_filter' => false, 'display_order' => 15],
        ];

        foreach ($amenities as $a) {
            Amenity::updateOrCreate(['key' => $a['key']], $a);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Occasion;
use Illuminate\Database\Seeder;

class OccasionSeeder extends Seeder
{
    public function run(): void
    {
        $d = '/images/demo';
        $occasions = [
            [
                'title' => 'Pool Parties',
                'slug' => 'pool-parties',
                'description' => 'Villas with expansive pool decks, cabanas, outdoor speaker zones, and BBQ grills tailored for festive groups.',
                'image' => "{$d}/occ-group.webp",
                'badge' => 'MOST POPULAR',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'Family Vacations',
                'slug' => 'family-vacations',
                'description' => 'Safe, enclosed properties with kids pools, expansive green lawns, chef kitchens, and multi-bedroom private compounds.',
                'image' => "{$d}/guests-relaxing-beside-a-private-villa-pool-in-goa.webp",
                'badge' => 'FAMILY CHOICE',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'title' => 'Romantic Getaways',
                'slug' => 'romantic-getaways',
                'description' => 'Intimate, ultra-private one-to-three bedroom estates with secluded pools, outdoor bathtubs, and sunset ocean vistas.',
                'image' => "{$d}/occ-romantic.webp",
                'badge' => 'FOR COUPLES',
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'title' => 'Corporate Retreats',
                'slug' => 'corporate-retreats',
                'description' => 'Spacious sanctuaries equipped with high-speed fiber internet, projector lounges, and private chef catering for executive offsites.',
                'image' => "{$d}/occ-corporate.webp",
                'badge' => 'OFFSITE',
                'is_active' => true,
                'display_order' => 4,
            ],
            [
                'title' => 'Beachfront Dining & Weddings',
                'slug' => 'beachfront-weddings',
                'description' => 'Exclusive coastal estates with private beach access, sprawling lawns, and event permits for milestone celebrations.',
                'image' => "{$d}/detail-casa-maris-beachfront-goa-villa-with-infinity-pool-ov.jpg",
                'badge' => 'EXCLUSIVE',
                'is_active' => true,
                'display_order' => 5,
            ],
        ];

        foreach ($occasions as $item) {
            Occasion::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}

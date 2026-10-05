<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Assagao',
                'slug' => 'assagao',
                'region' => 'North Goa',
                'image' => '/images/demo/loc-assagao.webp',
                'description' => 'Known as the Beverly Hills of Goa, Assagao is dotted with restored Portuguese mansions, art boutiques, and gourmet cafes surrounded by lush greenery.',
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Anjuna',
                'slug' => 'anjuna',
                'region' => 'North Goa',
                'image' => '/images/demo/loc-anjuna.webp',
                'description' => 'Famed for its vibrant sunset clifftops, bohemian legacy, chic beach clubs, and secluded luxury pool villas.',
                'is_featured' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Candolim',
                'slug' => 'candolim',
                'region' => 'North Goa',
                'image' => '/images/demo/loc-candolim.webp',
                'description' => 'Pristine beach stretch with upscale beachfront estates, seaside restaurants, and quick access to water sports.',
                'is_featured' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'Vagator',
                'slug' => 'vagator',
                'region' => 'North Goa',
                'image' => '/images/demo/loc-vagator.webp',
                'description' => 'Dramatic red cliffs meeting the Arabian Sea, iconic sunset spots, and contemporary hilltop retreats.',
                'is_featured' => true,
                'display_order' => 4,
            ],
            [
                'name' => 'Morjim',
                'slug' => 'morjim',
                'region' => 'North Goa',
                'image' => '/images/demo/loc-morjim.webp',
                'description' => 'A serene sanctuary known for Olive Ridley turtle nesting grounds, tranquil waters, and beachfront luxury.',
                'is_featured' => true,
                'display_order' => 5,
            ],
            [
                'name' => 'Siolim',
                'slug' => 'siolim',
                'region' => 'North Goa',
                'image' => '/images/demo/loc-siolim.webp',
                'description' => 'A picturesque riverside village offering heritage riverside mansions, palm-fringed backwaters, and peaceful charm.',
                'is_featured' => true,
                'display_order' => 6,
            ],
            [
                'name' => 'Calangute',
                'slug' => 'calangute',
                'region' => 'North Goa',
                'image' => '/images/demo/loc-candolim.webp',
                'description' => 'The lively center of North Goa featuring private gated luxury villas tucked away from the main streets.',
                'is_featured' => false,
                'display_order' => 7,
            ],
            [
                'name' => 'Benaulim',
                'slug' => 'benaulim',
                'region' => 'South Goa',
                'image' => '/images/demo/loc-morjim.webp',
                'description' => 'Quiet white sands of South Goa, slow living, and grand seaside villas designed for deep relaxation.',
                'is_featured' => false,
                'display_order' => 8,
            ],
        ];

        foreach ($locations as $loc) {
            Location::updateOrCreate(['slug' => $loc['slug']], $loc);
        }
    }
}

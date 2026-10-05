<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Villa;
use App\Models\VillaAttraction;
use App\Models\VillaRoom;
use Illuminate\Database\Seeder;

class VillaSeeder extends Seeder
{
    public function run(): void
    {
        $d = '/images/demo';
        $living = "{$d}/detail-sunlit-open-plan-living-room-with-cream-linen-sofas-a.jpg";
        $poolDeck = "{$d}/guests-relaxing-beside-a-private-villa-pool-in-goa.webp";
        $dining = "{$d}/detail-indoor-outdoor-dining-pavilion-with-teak-table-and-br.jpg";
        $bedroom = "{$d}/detail-primary-bedroom-with-four-poster-teak-bed-and-sheer-d.jpg";
        $master = "{$d}/detail-master.jpg";
        $detailHero = "{$d}/detail-casa-serenity-villa-exterior-at-dusk-with-glowing-pri.jpg";

        $assagao = Location::where('slug', 'assagao')->first();
        $anjuna = Location::where('slug', 'anjuna')->first();
        $vagator = Location::where('slug', 'vagator')->first();
        $morjim = Location::where('slug', 'morjim')->first();
        $siolim = Location::where('slug', 'siolim')->first();
        $candolim = Location::where('slug', 'candolim')->first();

        $villas = [
            [
                'slug' => 'sunset-villa-luxury',
                'name' => 'Sunset Villa Luxury - 4 Bedroom Villa with Private Pool in Assagao, Goa',
                'location_id' => $assagao?->id,
                'location_name' => 'Assagao',
                'region' => 'North Goa',
                'price_per_night' => 45000,
                'bedrooms' => 4,
                'bathrooms' => 4,
                'guests' => 8,
                'beds' => 4,
                'rating' => 4.90,
                'reviews_count' => 142,
                'badge' => 'FEATURED',
                'property_type' => 'luxe',
                'listing_badges' => ['Guest Favourite', 'Superhost Certified'],
                'amenity_keys' => ['pool', 'chef', 'hk', 'wifi', 'ac', 'backup'],
                'instant_booking' => true,
                'amenities' => ['Private Pool', 'Private Chef on Demand', '24/7 Housekeeping', 'High-Speed Wi-Fi', 'Air Conditioning', '100% Power Backup', 'Dedicated Caretaker'],
                'tags' => ['Private Pool', 'Family-Friendly', 'Luxury Living'],
                'image' => "{$d}/casa-serenity.webp",
                'gallery' => [
                    $detailHero,
                    $living,
                    $poolDeck,
                    $dining,
                    $bedroom,
                    $master
                ],
                'short_description' => 'A breath-taking 4-bedroom luxury sanctuary tucked away in Assagao, boasting an infinity private pool, sunlit balconies, and bespoke concierge hospitality.',
                'description' => "Sunset Villa Luxury is an exquisite private sanctuary in Assagao designed for discerning families and groups seeking high-end privacy. Featuring soaring exposed-beam ceilings, handcrafted teak furnishings, a private swimming pool set in tropical gardens, and an indoor-outdoor open living pavilion.",
                'house_rules' => [
                    'check_in' => 'Check-in from 02:00 PM',
                    'check_out' => 'Check-out until 11:00 AM',
                    'cancellation' => 'Free cancellation up to 14 days before check-in',
                    'deposit' => 'Refundable security deposit of ₹15,000 at check-in',
                    'pets' => 'Pet-friendly with prior confirmation',
                    'parties' => 'No loud music outdoors after 10:00 PM',
                ],
                'check_in_time' => '02:00 PM',
                'check_out_time' => '11:00 AM',
                'address' => 'Badem Road, Near Gunpowder, Assagao, North Goa, 403507',
                'latitude' => 15.5898,
                'longitude' => 73.7742,
                'is_featured' => true,
                'status' => 'active',
                'rooms' => [
                    ['room_name' => 'Master Bedroom Suite 1', 'bed_type' => 'King Bed', 'image' => $bedroom, 'ensuite_bath' => true, 'description' => 'Four-poster teak king bed, private garden balcony, walk-in shower & soaking tub.'],
                    ['room_name' => 'Poolside Guest Suite 2', 'bed_type' => 'King Bed', 'image' => $master, 'ensuite_bath' => true, 'description' => 'Direct patio access to the swimming pool, premium cotton linen, rainfall shower.'],
                    ['room_name' => 'Courtyard Bedroom 3', 'bed_type' => 'Queen Bed', 'image' => $living, 'ensuite_bath' => true, 'description' => 'Courtyard views, study desk, bespoke antique wardrobes, ensuite marble bathroom.'],
                    ['room_name' => 'Garden Bedroom 4', 'bed_type' => 'Twin Beds', 'image' => "{$d}/detail-garden-room.jpg", 'ensuite_bath' => true, 'description' => 'Two plush twin beds convertible to king, garden facing veranda, attached bathroom.'],
                ],
                'attractions' => [
                    ['name' => 'Vagator Beach', 'distance' => '2.5 km', 'category' => 'Beach'],
                    ['name' => 'Thalassa Restaurant', 'distance' => '3.0 km', 'category' => 'Dining'],
                    ['name' => 'Gunpowder Assagao', 'distance' => '0.8 km', 'category' => 'Dining'],
                    ['name' => 'Chapora Fort', 'distance' => '3.1 km', 'category' => 'Sightseeing'],
                    ['name' => 'Manohar International Airport (MOPA)', 'distance' => '28 km', 'category' => 'Transit'],
                ]
            ],
            [
                'slug' => 'casa-serenity',
                'name' => 'Casa Serenity',
                'location_id' => $assagao?->id,
                'location_name' => 'Assagao',
                'region' => 'North Goa',
                'price_per_night' => 38500,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'guests' => 10,
                'beds' => 6,
                'rating' => 4.90,
                'reviews_count' => 127,
                'badge' => 'FEATURED',
                'listing_badges' => ['Guest Favourite', 'Available This Weekend'],
                'amenity_keys' => ['pool', 'chef', 'hk', 'wifi', 'ac'],
                'instant_booking' => false,
                'amenities' => ['Private Pool', 'Chef on Request', 'Housekeeping', 'Wi-Fi', 'Air Conditioning', 'Power Backup'],
                'tags' => ['Private Pool', 'Family-Friendly'],
                'image' => "{$d}/casa-serenity.webp",
                'gallery' => [$detailHero, $living, $poolDeck, $dining, $bedroom],
                'short_description' => 'A serene Indo-Portuguese sanctuary tucked into the leafy lanes of Assagao — where laterite walls, linen drapes and a glowing private pool frame the art of unhurried Goan living.',
                'description' => "Casa Serenity is a five-bedroom private-pool villa set within a walled tropical garden in Assagao, one of North Goa's most coveted villages. Designed as a quiet counterpoint to the bustle of the coast, the property blends restored Indo-Portuguese architecture with contemporary comfort.",
                'house_rules' => [
                    'check_in' => '02:00 PM',
                    'check_out' => '11:00 AM',
                    'cancellation' => 'Strict: 50% refund up to 7 days before check-in',
                    'deposit' => '₹10,000 security deposit',
                ],
                'check_in_time' => '02:00 PM',
                'check_out_time' => '11:00 AM',
                'address' => 'Assagao Village, North Goa',
                'is_featured' => true,
                'status' => 'active',
                'rooms' => [
                    ['room_name' => 'Primary Bedroom', 'bed_type' => 'King Bed', 'image' => $bedroom, 'ensuite_bath' => true, 'description' => 'Ensuite bathroom, four-poster teak bed.'],
                    ['room_name' => 'Second Master Bedroom', 'bed_type' => 'King Bed', 'image' => $master, 'ensuite_bath' => true, 'description' => 'Garden view with outdoor shower.'],
                    ['room_name' => 'Pool View Bedroom', 'bed_type' => 'Queen Bed', 'image' => $dining, 'ensuite_bath' => true, 'description' => 'Direct access to pool deck.'],
                ],
                'attractions' => [
                    ['name' => 'Anjuna Beach', 'distance' => '3.8 km', 'category' => 'Beach'],
                    ['name' => 'Jamun Assagao', 'distance' => '0.5 km', 'category' => 'Dining'],
                ]
            ],
            [
                'slug' => 'villa-palmar',
                'name' => 'Villa Palmar',
                'location_id' => $anjuna?->id,
                'location_name' => 'Anjuna',
                'region' => 'North Goa',
                'price_per_night' => 14500,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'guests' => 6,
                'beds' => 3,
                'rating' => 4.88,
                'reviews_count' => 96,
                'badge' => 'BEST SELLER',
                'listing_badges' => ['Near the Beach', 'Guest Favourite'],
                'amenity_keys' => ['pool', 'beach', 'wifi', 'hk'],
                'instant_booking' => true,
                'amenities' => ['Private Pool', 'Wi-Fi', 'Caretaker', 'Air Conditioning', 'Power Backup'],
                'tags' => ['Private Pool', 'Near Beach', 'Sea View'],
                'image' => "{$d}/villa-palmar.webp",
                'gallery' => ["{$d}/villa-palmar.webp", $living, $dining],
                'short_description' => "Stylish three-bedroom villa near Anjuna's beaches and vibrant cafes.",
                'description' => "Villa Palmar offers a peaceful private pool retreat minutes from Anjuna Beach, perfect for small groups and couples.",
                'house_rules' => ['check_in' => '02:00 PM', 'check_out' => '11:00 AM'],
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'slug' => 'the-leaf-pavilion',
                'name' => 'The Leaf Pavilion',
                'location_id' => $assagao?->id,
                'location_name' => 'Assagao',
                'region' => 'North Goa',
                'price_per_night' => 24000,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'guests' => 10,
                'beds' => 5,
                'rating' => 4.91,
                'reviews_count' => 87,
                'badge' => 'FEATURED',
                'listing_badges' => ['Exclusive', 'Lush Garden'],
                'amenity_keys' => ['pool', 'family', 'hk', 'wifi'],
                'instant_booking' => false,
                'amenities' => ['Private Pool', 'Garden', 'Housekeeping', 'Wi-Fi', 'Chef on Request'],
                'tags' => ['Private Pool', 'Family-Friendly'],
                'image' => "{$d}/the-leaf-pavilion.webp",
                'gallery' => ["{$d}/the-leaf-pavilion.webp", "{$d}/detail-garden-room.jpg", $dining],
                'short_description' => 'Five-bedroom pavilion villa with lush garden and private pool.',
                'description' => "The Leaf Pavilion combines spacious tropical living with Assagao's quiet garden village character.",
                'house_rules' => ['check_in' => '02:00 PM', 'check_out' => '11:00 AM'],
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'slug' => 'sol-e-mar',
                'name' => 'Sol e Mar',
                'location_id' => $siolim?->id,
                'location_name' => 'Siolim',
                'region' => 'North Goa',
                'price_per_night' => 19800,
                'bedrooms' => 4,
                'bathrooms' => 4,
                'guests' => 8,
                'beds' => 4,
                'rating' => 4.85,
                'reviews_count' => 74,
                'badge' => null,
                'listing_badges' => ['Riverside Views'],
                'amenity_keys' => ['pool', 'kitchen', 'parking'],
                'instant_booking' => false,
                'amenities' => ['Private Pool', 'Kitchen', 'Parking', 'Wi-Fi', 'Air Conditioning'],
                'tags' => ['Private Pool'],
                'image' => "{$d}/sol-e-mar.webp",
                'gallery' => ["{$d}/sol-e-mar.webp", $living, "{$d}/detail-pool-room.jpg"],
                'short_description' => 'Sunlit four-bedroom villa in riverside Siolim.',
                'description' => 'Sol e Mar is a bright private-pool villa ideal for families and small groups visiting Siolim riverfront.',
                'house_rules' => ['check_in' => '02:00 PM', 'check_out' => '11:00 AM'],
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'slug' => 'azure-retreat',
                'name' => 'Azure Retreat',
                'location_id' => $vagator?->id,
                'location_name' => 'Vagator',
                'region' => 'North Goa',
                'price_per_night' => 16200,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'guests' => 6,
                'beds' => 3,
                'rating' => 4.80,
                'reviews_count' => 64,
                'badge' => 'BEST SELLER',
                'listing_badges' => ['Exclusive', 'Sea View'],
                'amenity_keys' => ['pool', 'sea', 'chef', 'backup'],
                'instant_booking' => true,
                'amenities' => ['Infinity Pool', 'Outdoor Seating', 'Power Backup', 'Chef on Request'],
                'tags' => ['Private Pool', 'Sea View'],
                'image' => "{$d}/azure-retreat.webp",
                'gallery' => ["{$d}/azure-retreat.webp", "{$d}/detail-villa-azure-contemporary-north-goa-villa-with-large-p.jpg"],
                'short_description' => 'Contemporary villa with large private pool near Vagator cliffs.',
                'description' => "Azure Retreat pairs contemporary design with easy access to Vagator's sunset spots.",
                'house_rules' => ['check_in' => '02:00 PM', 'check_out' => '11:00 AM'],
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'slug' => 'casa-morjim',
                'name' => 'Casa Morjim',
                'location_id' => $morjim?->id,
                'location_name' => 'Morjim',
                'region' => 'North Goa',
                'price_per_night' => 32000,
                'bedrooms' => 6,
                'bathrooms' => 6,
                'guests' => 12,
                'beds' => 7,
                'rating' => 4.92,
                'reviews_count' => 52,
                'badge' => 'FEATURED',
                'listing_badges' => ['Beachfront Luxury', 'Superhost Certified'],
                'amenity_keys' => ['beach', 'pool', 'chef', 'wifi'],
                'instant_booking' => false,
                'amenities' => ['Beachfront', 'Private Pool', 'Chef on Request', 'Direct Beach Access'],
                'tags' => ['Beachfront', 'Private Pool'],
                'image' => "{$d}/casa-morjim.webp",
                'gallery' => ["{$d}/casa-morjim.webp", "{$d}/detail-casa-maris-beachfront-goa-villa-with-infinity-pool-ov.jpg"],
                'short_description' => "Six-bedroom beachfront estate on Morjim's quiet shoreline.",
                'description' => 'Direct beach access, expansive lawns, and panoramic views of the Arabian Sea.',
                'house_rules' => ['check_in' => '02:00 PM', 'check_out' => '11:00 AM'],
                'is_featured' => true,
                'status' => 'active',
            ]
        ];

        foreach ($villas as $data) {
            $rooms = $data['rooms'] ?? [];
            $attractions = $data['attractions'] ?? [];
            unset($data['rooms'], $data['attractions']);

            $villa = Villa::updateOrCreate(['slug' => $data['slug']], $data);

            if (!empty($rooms)) {
                $villa->rooms()->delete();
                foreach ($rooms as $index => $r) {
                    $r['display_order'] = $index + 1;
                    $villa->rooms()->create($r);
                }
            }

            if (!empty($attractions)) {
                $villa->attractions()->delete();
                foreach ($attractions as $index => $a) {
                    $a['display_order'] = $index + 1;
                    $villa->attractions()->create($a);
                }
            }
        }
    }
}

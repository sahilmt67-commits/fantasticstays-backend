<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->string('amenity_eyebrow')->nullable();
            $table->string('amenity_heading')->nullable();
            $table->json('amenity_groups')->nullable();
            $table->string('services_eyebrow')->nullable();
            $table->string('services_heading')->nullable();
            $table->text('services_intro')->nullable();
            $table->json('services')->nullable();
        });

        DB::table('villas')->where('slug', 'casa-serenity')->update([
            'amenity_eyebrow' => 'Amenities',
            'amenity_heading' => 'Thoughtfully appointed throughout',
            'amenity_groups' => json_encode([
                ['title' => 'Popular Amenities', 'items' => ['Private Swimming Pool', 'Air Conditioning', 'High-Speed Wi-Fi', 'Daily Housekeeping', 'Fully Staffed', 'Power Backup']],
                ['title' => 'Kitchen & Dining', 'items' => ['Fully Equipped Kitchen', 'Wood-Fired Pizza Oven', 'Barbecue', 'Dining for 10', 'Tea/Coffee Station', 'Refrigerator']],
                ['title' => 'Bedroom & Bathroom', 'items' => ['Premium Linen', 'En-suite Bathrooms', 'Rain Showers', 'Bath Toiletries', 'Wardrobes', 'Safe in Master Suite']],
                ['title' => 'Entertainment', 'items' => ['Smart TV in Living Room', 'Bluetooth Sound System', 'Board Games', 'Book Library', 'Yoga Deck']],
            ]),
            'services_eyebrow' => 'Private Dining & Services',
            'services_heading' => 'Curated services, included or on request',
            'services_intro' => 'A dedicated team ensures your stay is effortless. Some services are part of your stay, and others can be arranged on request.',
            'services' => json_encode([
                ['name' => 'Local Concierge Support', 'included' => true],
                ['name' => 'Personal Chef', 'included' => false],
                ['name' => 'Grocery Shopping Assistance', 'included' => false],
                ['name' => 'Airport Transfer', 'included' => false],
                ['name' => 'Car & Scooter Rental', 'included' => false],
                ['name' => 'Spa & Wellness Treatments', 'included' => false],
                ['name' => 'Event or Celebration Arrangements', 'included' => false],
                ['name' => 'Butler Service', 'included' => false],
            ]),
        ]);
    }

    public function down(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn([
                'amenity_eyebrow',
                'amenity_heading',
                'amenity_groups',
                'services_eyebrow',
                'services_heading',
                'services_intro',
                'services',
            ]);
        });
    }
};

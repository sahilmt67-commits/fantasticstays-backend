<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villas', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('location_name')->nullable();
            $table->string('region')->default('North Goa'); // 'North Goa' | 'South Goa'
            $table->decimal('price_per_night', 10, 2);
            $table->integer('bedrooms')->default(1);
            $table->integer('bathrooms')->default(1);
            $table->integer('guests')->default(2);
            $table->integer('beds')->default(1);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('reviews_count')->default(0);
            $table->string('badge')->nullable(); // 'FEATURED', 'NEW', 'LUXURY'
            $table->json('listing_badges')->nullable(); // ['Guest Favourite', 'Available This Weekend']
            $table->json('amenity_keys')->nullable(); // ['pool', 'beach', 'pet']
            $table->boolean('instant_booking')->default(false);
            $table->json('amenities')->nullable(); // ['Private Pool', 'Chef on Request']
            $table->json('tags')->nullable(); // ['Private Pool', 'Family-Friendly']
            $table->string('image'); // Primary hero/card image
            $table->json('gallery')->nullable(); // 5+ image URLs
            $table->json('videos')->nullable(); // [{id, title, thumb, embed, source}]
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('house_rules')->nullable(); // Checkin, Checkout, Cancellation, Deposit, Pets, Parties
            $table->string('check_in_time')->default('02:00 PM');
            $table->string('check_out_time')->default('11:00 AM');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('active'); // 'active', 'inactive', 'maintenance'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villas');
    }
};

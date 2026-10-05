<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('why_chooses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->default('sparkles');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('why_chooses')->insert([
            [
                'title' => 'Carefully test Selected Properties',
                'description' => 'Every villa is reviewed for comfort, quality, location and guest experience.',
                'icon' => 'sparkles',
                'is_active' => true,
                'display_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Private and Spacious Stays',
                'description' => 'Enjoy complete privacy, generous living spaces and amenities designed for groups and families.',
                'icon' => 'waves',
                'is_active' => true,
                'display_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Personalised Villa Recommendations',
                'description' => 'Our team helps you choose a villa that suits your group size, occasion, location and budget.',
                'icon' => 'map',
                'is_active' => true,
                'display_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Dedicated Guest Assistance',
                'description' => 'Receive reliable support before arrival and throughout your stay.',
                'icon' => 'headphones',
                'is_active' => true,
                'display_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Transparent Booking Process',
                'description' => 'Get clear information about pricing, inclusions, policies and additional services.',
                'icon' => 'shield',
                'is_active' => true,
                'display_order' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Local Goa Expertise',
                'description' => 'Benefit from local knowledge when planning transportation, dining, activities and special experiences.',
                'icon' => 'chef',
                'is_active' => true,
                'display_order' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('why_chooses');
    }
};

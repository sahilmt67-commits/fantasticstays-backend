<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained('villas')->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('guest_location')->nullable();
            $table->string('guest_avatar')->nullable();
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->decimal('cleanliness_rating', 2, 1)->default(5.0);
            $table->decimal('accuracy_rating', 2, 1)->default(5.0);
            $table->decimal('communication_rating', 2, 1)->default(5.0);
            $table->decimal('location_rating', 2, 1)->default(5.0);
            $table->decimal('value_rating', 2, 1)->default(5.0);
            $table->text('comment');
            $table->string('stay_date')->nullable(); // e.g. "January 2026"
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_attractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained('villas')->cascadeOnDelete();
            $table->string('name'); // e.g. "Vagator Beach", "Thalassa Restaurant"
            $table->string('distance'); // e.g. "2.5 km", "10 mins drive"
            $table->string('category')->default('Beach'); // Beach, Restaurant, Nightlife, Transit, Sightseeing
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_attractions');
    }
};

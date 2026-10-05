<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained('villas')->cascadeOnDelete();
            $table->string('room_name'); // e.g. "Primary Bedroom", "Pool View Suite"
            $table->string('bed_type')->default('King Bed'); // 'King Bed', 'Queen Bed', 'Twin Beds'
            $table->string('image')->nullable();
            $table->boolean('ensuite_bath')->default(true);
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_rooms');
    }
};

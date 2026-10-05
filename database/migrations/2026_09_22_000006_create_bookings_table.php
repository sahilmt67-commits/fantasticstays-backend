<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('villa_id')->constrained('villas')->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('total_guests')->default(1);
            $table->integer('nights')->default(1);
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending'); // 'pending', 'confirmed', 'completed', 'cancelled'
            $table->string('payment_status')->default('unpaid'); // 'unpaid', 'partial', 'paid', 'refunded'
            $table->text('special_requests')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

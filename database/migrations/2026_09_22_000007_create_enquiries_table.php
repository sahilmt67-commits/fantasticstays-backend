<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->nullable()->constrained('villas')->nullOnDelete();
            $table->string('villa_name')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->integer('guests')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->default('website'); // 'website', 'whatsapp', 'villa_detail', 'contact_page'
            $table->string('status')->default('new'); // 'new', 'contacted', 'qualified', 'booked', 'closed'
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_leads', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 20);
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 50);
            $table->text('message')->nullable();
            $table->string('status', 20)->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_leads');
    }
};

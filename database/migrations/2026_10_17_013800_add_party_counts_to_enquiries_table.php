<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->unsignedInteger('adults')->nullable()->after('guests');
            $table->unsignedInteger('children')->nullable()->after('adults');
            $table->unsignedInteger('infants')->nullable()->after('children');
            $table->unsignedInteger('pets')->nullable()->after('infants');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['adults', 'children', 'infants', 'pets']);
        });
    }
};

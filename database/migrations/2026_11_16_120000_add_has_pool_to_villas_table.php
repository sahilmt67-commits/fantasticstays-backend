<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->boolean('has_pool')->default(false)->after('bathrooms');
        });
    }

    public function down(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn('has_pool');
        });
    }
};

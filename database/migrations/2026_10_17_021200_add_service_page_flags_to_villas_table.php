<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->boolean('show_on_sale')->default(false)->after('is_featured');
            $table->boolean('show_on_list')->default(false)->after('show_on_sale');
        });
    }

    public function down(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn(['show_on_sale', 'show_on_list']);
        });
    }
};

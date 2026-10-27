<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE villas MODIFY discount_percent VARCHAR(120) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE villas MODIFY discount_percent TINYINT UNSIGNED NULL');
    }
};

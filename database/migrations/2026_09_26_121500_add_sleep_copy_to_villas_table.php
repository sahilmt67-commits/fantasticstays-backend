<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->string('sleep_heading')->nullable()->after('beds');
            $table->text('sleep_intro')->nullable()->after('sleep_heading');
        });

        DB::table('villas')->where('slug', 'casa-serenity')->update([
            'sleep_heading' => 'Five serene sleeping sanctuaries',
            'sleep_intro' => 'Each bedroom is air-conditioned with an en-suite bathroom, premium linen and a view of the pool, garden or courtyard.',
        ]);
    }

    public function down(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn(['sleep_heading', 'sleep_intro']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [
            'contact_heading' => 'Speak with a Villa Specialist',
            'contact_intro' => 'Share your travel dates and requirements. Our team typically responds within 2 hours between 9 am and 9 pm IST.',
        ];

        foreach ($rows as $key => $value) {
            if (DB::table('settings')->where('key', $key)->exists()) {
                continue;
            }
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'group' => 'general',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['contact_heading', 'contact_intro'])->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [
            'footer_kicker' => 'Goa · Private Estates',
            'footer_about' => 'Handpicked private villas in Goa for families, couples, groups, weddings and corporate retreats — with personalised booking support and dedicated guest assistance.',
            'social_instagram' => 'https://instagram.com',
            'social_facebook' => 'https://facebook.com',
            'social_youtube' => 'https://youtube.com',
        ];

        foreach ($rows as $key => $value) {
            $exists = DB::table('settings')->where('key', $key)->exists();
            if ($exists) {
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

        $replacements = [
            'contact_phone' => ['+91 98765 43210', '+91 88603 31188'],
            'whatsapp_number' => ['+919876543210', '+918860331188'],
            'contact_email' => ['concierge@fantasticstays.com', 'Booking@fantasticstays.com'],
        ];

        foreach ($replacements as $key => [$from, $to]) {
            DB::table('settings')->where('key', $key)->where('value', $from)->update([
                'value' => $to,
                'updated_at' => $now,
            ]);
        }

        DB::table('settings')
            ->where('key', 'office_address')
            ->where('value', 'like', 'Villa 12%')
            ->update([
                'value' => "House No. 4/1635 Probavaddo\nCalangute, Bardez, Goa-403516\nContact: 8860331188",
                'updated_at' => $now,
            ]);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'footer_kicker',
            'footer_about',
            'social_instagram',
            'social_facebook',
            'social_youtube',
        ])->delete();
    }
};

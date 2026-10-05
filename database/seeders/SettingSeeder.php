<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Fantastic Stays', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Luxury Private Villas in Goa', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '+91 98765 43210', 'group' => 'contact'],
            ['key' => 'whatsapp_number', 'value' => '+919876543210', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'concierge@fantasticstays.com', 'group' => 'contact'],
            ['key' => 'office_address', 'value' => 'Villa 12, De Mello Vaddo, Assagao, Goa 403507', 'group' => 'contact'],
            ['key' => 'currency_symbol', 'value' => '₹', 'group' => 'booking'],
            ['key' => 'tax_rate_percent', 'value' => '18', 'group' => 'booking'],
            ['key' => 'concierge_support', 'value' => '24/7 Dedicated Luxury Concierge Service', 'group' => 'general'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}

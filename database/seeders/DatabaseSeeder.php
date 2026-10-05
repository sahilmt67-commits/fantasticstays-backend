<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LocationSeeder::class,
            AmenitySeeder::class,
            VillaSeeder::class,
            OccasionSeeder::class,
            ReviewSeeder::class,
            FaqSeeder::class,
            SettingSeeder::class,
            BookingSeeder::class,
        ]);
    }
}

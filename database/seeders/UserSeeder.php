<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@fantasticstays.com'],
            [
                'name' => 'Fantastic Stays Admin',
                'phone' => '+91 98765 43210',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}

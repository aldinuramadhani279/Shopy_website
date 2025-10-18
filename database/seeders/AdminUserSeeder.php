<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'address' => '123 Admin Street',
                'province' => 'Admin Province',
                'city' => 'Admin City',
                'district' => 'Admin District',
                'postal_code' => '12345',
                'role' => 'admin',
            ]);
        }
    }
}

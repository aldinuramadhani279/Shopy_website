<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::create([
            'code' => 'SAVE10',
            'type' => 'percent',
            'value' => 10.00,
            'minimum_amount' => 50.00,
            'usage_limit' => 100,
        ]);

        Coupon::create([
            'code' => 'SAVE20',
            'type' => 'fixed',
            'value' => 20.00,
            'minimum_amount' => 100.00,
            'usage_limit' => 50,
        ]);
    }
}

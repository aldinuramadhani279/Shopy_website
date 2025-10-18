<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Laravel T-Shirt',
            'description' => 'Comfortable cotton t-shirt with Laravel logo',
            'price' => 29.99,
            'active' => true,
        ]);

        Product::create([
            'name' => 'PHP Mug',
            'description' => 'Ceramic mug with PHP logo',
            'price' => 14.99,
            'active' => true,
        ]);

        Product::create([
            'name' => 'JavaScript Book',
            'description' => 'Comprehensive guide to modern JavaScript',
            'price' => 39.99,
            'active' => true,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create dummy categories if they don't exist
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Clothing', 'description' => 'Fashion and apparel'],
            ['name' => 'Home & Kitchen', 'description' => 'Home appliances and kitchen items'],
            ['name' => 'Books', 'description' => 'Books and educational materials'],
            ['name' => 'Sports', 'description' => 'Sports equipment and accessories'],
            ['name' => 'Beauty', 'description' => 'Beauty and personal care products'],
        ];

        foreach ($categories as $categoryData) {
            $slug = Str::slug($categoryData['name']);
            if (!Category::where('slug', $slug)->exists()) {
                Category::create([
                    'name' => $categoryData['name'],
                    'slug' => $slug,
                    'description' => $categoryData['description'],
                    'is_active' => true,
                ]);
            }
        }

        // Get categories for products
        $allCategories = Category::all();
        
        // Create dummy products if they don't exist
        $products = [
            [
                'name' => 'Smartphone XYZ',
                'description' => 'Latest smartphone with advanced features',
                'price' => 599.99,
                'stock' => 50,
                'weight' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Cotton T-Shirt',
                'description' => 'Comfortable cotton t-shirt for everyday wear',
                'price' => 24.99,
                'stock' => 100,
                'weight' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Coffee Maker',
                'description' => 'Automatic coffee maker with timer',
                'price' => 89.99,
                'stock' => 25,
                'weight' => 1200,
                'is_active' => true,
            ],
            [
                'name' => 'Programming Book',
                'description' => 'Learn programming with this comprehensive guide',
                'price' => 39.99,
                'stock' => 75,
                'weight' => 300,
                'is_active' => true,
            ],
            [
                'name' => 'Yoga Mat',
                'description' => 'Non-slip yoga mat for exercise',
                'price' => 29.99,
                'stock' => 60,
                'weight' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Face Cream',
                'description' => 'Moisturizing face cream for all skin types',
                'price' => 19.99,
                'stock' => 80,
                'weight' => 50,
                'is_active' => true,
            ],
        ];

        foreach ($products as $index => $productData) {
            $slug = Str::slug($productData['name']);
            if (!Product::where('slug', $slug)->exists() && $allCategories->count() > 0) {
                Product::create([
                    'category_id' => $allCategories->random()->id, // Random category assignment
                    'name' => $productData['name'],
                    'slug' => $slug,
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'weight' => $productData['weight'],
                    'image' => 'images/products/default-product.jpg', // Placeholder image
                    'gallery' => json_encode([]), // Empty gallery for now
                    'is_active' => $productData['is_active'],
                ]);
            }
        }
    }
}

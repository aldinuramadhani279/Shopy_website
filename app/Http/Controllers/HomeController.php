<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     * This method is publicly accessible and does not require authentication.
     */
    public function index()
    {
        try {
            // Get latest products
            $latestProducts = Product::latest()
                                    ->take(8)
                                    ->get();

            // Get best-selling products
            $bestSellingProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
                                        ->join('order_items', 'products.id', '=', 'order_items.product_id')
                                        ->groupBy('products.id')
                                        ->orderByDesc('total_sold')
                                        ->take(8)
                                        ->get();

            $categories = Category::where('is_active', true)
                                 ->take(4)
                                 ->get();

            return view('home', compact('latestProducts', 'bestSellingProducts', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Home index error: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return view('home', [
                'latestProducts' => collect([]),
                'bestSellingProducts' => collect([]),
                'categories' => collect([])
            ]);
        }
    }
}

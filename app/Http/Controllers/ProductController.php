<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     * This method is publicly accessible and does not require authentication.
     */
    public function index(Request $request)
    {
        \Log::info('ProductController@index called with params: ' . json_encode($request->all()));
        
        try {
            $query = Product::with('category');

            // Search functionality
            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
                \Log::info('Search applied: ' . $searchTerm);
            }

            // Category filter
            if ($request->has('category') && $request->category != '') {
                $query->where('category_id', $request->category);
                \Log::info('Category filter applied: ' . $request->category);
            }

            // Price range filter
            if ($request->has('min_price') && $request->min_price != '') {
                $query->where('price', '>=', $request->min_price);
                \Log::info('Min price filter applied: ' . $request->min_price);
            }

            if ($request->has('max_price') && $request->max_price != '') {
                $query->where('price', '<=', $request->max_price);
                \Log::info('Max price filter applied: ' . $request->max_price);
            }

            // Sorting
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    \Log::info('Sorting applied: price_low');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    \Log::info('Sorting applied: price_high');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    \Log::info('Sorting applied: name_asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    \Log::info('Sorting applied: name_desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    \Log::info('Sorting applied: newest');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    \Log::info('Sorting applied: oldest');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    \Log::info('Sorting applied: default');
                    break;
            }

            $products = $query->with(['category'])->paginate(12)->appends(request()->query());
            $categories = Category::where('is_active', true)->get();
            
            \Log::info('Products loaded: ' . $products->count() . ' items, total: ' . $products->total());

            return view('frontend.products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Product index error: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return back()->with('error', 'An error occurred while loading products.');
        }
    }

    /**
     * Display a listing of the categories.
     * This method is publicly accessible and does not require authentication.
     */
    public function categories()
    {
        \Log::info('ProductController@categories called');
        
        try {
            $categories = Category::where('is_active', true)->get();
            
            \Log::info('Categories loaded: ' . $categories->count() . ' items');
            
            return view('frontend.categories.index', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Category index error: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return back()->with('error', 'An error occurred while loading categories.');
        }
    }

    /**
     * Display the specified product.
     * This method is publicly accessible and does not require authentication.
     * Shows 404 page if product is not found or is not active.
     */
    public function show($slug)
    {
        \Log::info('ProductController@show called with slug: ' . $slug);
        
        try {
            // Get the product with active status check
            $product = Product::where('slug', $slug)
                              ->where('is_active', true)
                              ->with(['category'])
                              ->first();
            
            \Log::info('Product lookup result: ' . ($product ? 'found' : 'not found'));
            
            if (!$product) {
                // Log the specific issue
                \Log::warning('Product not found or not active: ' . $slug);
                
                // Check if product exists but is not active
                $inactiveProduct = Product::where('slug', $slug)->first();
                if ($inactiveProduct) {
                    \Log::warning('Product exists but is not active: ' . $slug . ' with ID: ' . $inactiveProduct->id);
                } else {
                    \Log::warning('Product does not exist: ' . $slug);
                }
                
                // Product not found or not active
                return redirect()->route('products.index')
                                ->with('error', 'The product you are looking for is not available.');
            }
            
            $relatedProducts = Product::where('category_id', $product->category_id ?? null)
                                      ->where('id', '!=', $product->id)
                                      ->where('is_active', true)
                                      ->with(['category'])
                                      ->take(4)
                                      ->get();

            \Log::info('Successfully loaded product: ' . $product->name . ' with ' . $relatedProducts->count() . ' related products');
            
            return view('frontend.products.show', compact('product', 'relatedProducts'));
        } catch (\Exception $e) {
            \Log::error('Product show error: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return redirect()->route('products.index')
                            ->with('error', 'The product you are looking for is not available.');
        }
    }
}
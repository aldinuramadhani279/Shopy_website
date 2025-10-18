<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        $products = $query->latest()->paginate(10)->appends(request()->query());
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255|unique:products,name',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'weight' => 'required|integer|min:0',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'boolean',
            ]);

            $data = $request->except('image', 'gallery');
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->boolean('is_active');

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = ImageHelper::processImage($request->file('image'), 'images/products');
            }

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                $galleryImages = [];
                foreach ($request->file('gallery') as $galleryImage) {
                    $galleryImages[] = ImageHelper::processImage($galleryImage, 'images/products/gallery');
                }
                $data['gallery'] = json_encode($galleryImages);
            }

            Product::create($data);

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            \Log::error('Product creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'weight' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'boolean',
            ]);

            $data = $request->except('image', 'gallery');
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->boolean('is_active');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    ImageHelper::deleteImage($product->image);
                }

                $data['image'] = ImageHelper::processImage($request->file('image'), 'images/products');
            }

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                // Delete old gallery images if exist
                if ($product->gallery) {
                    $oldGallery = json_decode($product->gallery);
                    foreach ($oldGallery as $oldImage) {
                        ImageHelper::deleteImage($oldImage);
                    }
                }

                $galleryImages = [];
                foreach ($request->file('gallery') as $galleryImage) {
                    $galleryImages[] = ImageHelper::processImage($galleryImage, 'images/products/gallery');
                }
                $data['gallery'] = json_encode($galleryImages);
            }

            $product->update($data);

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Product update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            ImageHelper::deleteImage($product->image);
        }

        // Delete gallery images if exist
        if ($product->gallery) {
            $galleryImages = json_decode($product->gallery);
            foreach ($galleryImages as $image) {
                ImageHelper::deleteImage($image);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Activate/Deactivate products
     */
    public function toggleActive(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        if ($request->action === 'delete') {
            // Handle bulk delete
            Product::whereIn('id', $request->ids)->delete();
            $message = 'Products deleted successfully.';
        } else {
            // Handle activate/deactivate
            Product::whereIn('id', $request->ids)->update(['is_active' => $request->action === 'activate']);
            $message = 'Products updated successfully.';
        }

        return redirect()->back()->with('success', $message);
    }
}
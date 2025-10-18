<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $data = $request->except('image');
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->boolean('is_active');

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = ImageHelper::processImage($request->file('image'), 'images/categories');
            }

            $category = Category::create($data);

            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            \Log::error('Category creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create category: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $data = $request->except('image');
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->boolean('is_active');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image) {
                    ImageHelper::deleteImage($category->image);
                }

                $data['image'] = ImageHelper::processImage($request->file('image'), 'images/categories');
            }

            $category->update($data);

            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Category update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update category: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete category with associated products.');
        }

        // Delete image if exists
        if ($category->image) {
            ImageHelper::deleteImage($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
    
    /**
     * Toggle active status for category
     */
    public function toggleActive(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
            'action' => 'required|in:activate,deactivate',
        ]);

        // Update categories based on action
        Category::whereIn('id', $request->ids)->update(['is_active' => $request->action === 'activate']);

        $message = $request->action === 'activate' ? 'Categories activated successfully.' : 'Categories deactivated successfully.';
        
        return redirect()->back()->with('success', $message);
    }
}
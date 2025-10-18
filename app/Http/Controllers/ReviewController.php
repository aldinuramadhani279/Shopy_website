<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('user', 'product', 'order')->where('status', 'pending')->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Display approved reviews.
     */
    public function approved()
    {
        $reviews = Review::with('user', 'product', 'order')->where('status', 'approved')->latest()->paginate(10);
        return view('admin.reviews.approved', compact('reviews'));
    }

    /**
     * Display rejected reviews.
     */
    public function rejected()
    {
        $reviews = Review::with('user', 'product', 'order')->where('status', 'rejected')->latest()->paginate(10);
        return view('admin.reviews.rejected', compact('reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'status' => $request->status,
            'admin_comment' => $request->comment,
        ]);

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to submit a review.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user has already reviewed this product for this order
        $existingReview = Review::where('user_id', auth()->id())
                               ->where('product_id', $request->product_id)
                               ->where('order_id', $request->order_id)
                               ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product for this order.');
        }

        // Check if order belongs to the user and is delivered
        $order = Order::where('id', $request->order_id)
                     ->where('user_id', auth()->id())
                     ->where('status', 'delivered')
                     ->first();

        if (!$order) {
            return back()->with('error', 'You can only review products from delivered orders.');
        }

        // Check if product is in the order
        $orderItem = $order->orderItems()->where('product_id', $request->product_id)->first();

        if (!$orderItem) {
            return back()->with('error', 'Invalid product for this order.');
        }

        // Create review
        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Requires admin approval
            'admin_comment' => null,
        ]);

        return back()->with('success', 'Review submitted successfully and is pending approval.');
    }

    /**
     * Display approved reviews for a product.
     */
    public function productReviews($productId)
    {
        $product = Product::findOrFail($productId);
        $reviews = Review::where('product_id', $productId)
                        ->where('status', 'approved')
                        ->with('user')
                        ->latest()
                        ->paginate(10);

        return view('frontend.reviews.product', compact('product', 'reviews'));
    }
}
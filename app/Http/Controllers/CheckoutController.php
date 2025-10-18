<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        // Get cart items
        $cartItems = session('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = array_reduce($cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
        
        $discount = session('cart.discount', 0);
        $total = $subtotal - $discount;

        // Get user's shipping address
        $user = auth()->user();

        return view('frontend.checkout.index', compact('cartItems', 'subtotal', 'discount', 'total', 'user'));
    }

    public function process(Request $request)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        // Get cart items
        $cartItems = session('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate request
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_province' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:bank_transfer,cod,ewallet',
        ]);

        // Calculate totals
        $subtotal = array_reduce($cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
        
        $discount = session('cart.discount', 0);
        $total = $subtotal - $discount;

        // Check if products are still available and have enough stock
        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if (!$product || !$product->is_active || $product->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', 'One or more products in your cart are no longer available or have insufficient stock.');
            }
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'status' => $request->payment_method === 'cod' ? 'processing' : 'pending_payment',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'cod' ? 'paid' : 'unpaid',
            'total_amount' => $total,
            'shipping_address' => json_encode([
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'province' => $request->shipping_province,
                'postal_code' => $request->shipping_postal_code,
            ]),
            'notes' => $request->notes,
        ]);

        // Create order items and update product stock
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);

            // Update product stock
            $product = Product::find($item['id']);
            $product->decrement('stock', $item['quantity']);
        }

        // Clear cart
        session()->forget(['cart', 'cart.discount', 'cart.coupon_code']);

        return redirect()->route('checkout.success', $order->id)->with('success', 'Order placed successfully!');
    }

    public function success($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Check if order belongs to the authenticated user
        if (auth()->check() && $order->user_id != auth()->id()) {
            abort(403);
        }
        
        return view('frontend.checkout.success', compact('order'));
    }
}
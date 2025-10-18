<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // For demonstration, we'll use session to store cart items
        $cartItems = session('cart', []);
        $subtotal = $this->calculateSubtotal($cartItems);
        $discount = session('cart.discount', 0);
        $total = $subtotal - $discount;

        return view('frontend.cart.index', compact('cartItems', 'subtotal', 'discount', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is active
        if (!$product->is_active) {
            return back()->with('error', 'This product is not available.');
        }

        // Check stock
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available for this product.');
        }

        // Get current cart
        $cart = session('cart', []);

        // Check if product already in cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $product->id) {
                // Check if adding this quantity would exceed stock
                if (($item['quantity'] + $request->quantity) > $product->stock) {
                    return back()->with('error', 'Not enough stock available for this product.');
                }
                
                $item['quantity'] += $request->quantity;
                $found = true;
                break;
            }
        }

        // If not found, add new item
        if (!$found) {
            $cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->image,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session('cart', []);

        // Check stock
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available for this product.');
        }

        // Update quantity
        foreach ($cart as &$item) {
            if ($item['id'] == $product->id) {
                $item['quantity'] = $request->quantity;
                break;
            }
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Cart updated successfully!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = session('cart', []);

        // Remove item
        $cart = array_filter($cart, function($item) use ($request) {
            return $item['id'] != $request->product_id;
        });

        session(['cart' => $cart]);

        return back()->with('success', 'Product removed from cart successfully!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon->isValid()) {
            return back()->with('error', 'This coupon is no longer valid.');
        }

        // Get cart items
        $cartItems = session('cart', []);
        $subtotal = $this->calculateSubtotal($cartItems);

        // Check minimum amount requirement
        if ($coupon->minimum_amount && $subtotal < $coupon->minimum_amount) {
            return back()->with('error', 'Minimum amount of Rp. ' . number_format($coupon->minimum_amount, 2) . ' required to use this coupon.');
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($subtotal);

        // Store discount in session
        session(['cart.discount' => $discount]);
        session(['cart.coupon_code' => $coupon->code]);

        return back()->with('success', 'Coupon applied successfully! You saved Rp. ' . number_format($discount, 2));
    }

    public function removeCoupon()
    {
        session()->forget(['cart.discount', 'cart.coupon_code']);

        return back()->with('success', 'Coupon removed successfully.');
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return back()->with('error', 'This product is not available.');
        }

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available for this product.');
        }

        $cart = session('cart', []);

        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $product->id) {
                if (($item['quantity'] + $request->quantity) > $product->stock) {
                    return back()->with('error', 'Not enough stock available for this product.');
                }
                
                $item['quantity'] += $request->quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->image,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('checkout.index')->with('success', 'Proceed to checkout.');
    }

    private function calculateSubtotal($cartItems)
    {
        return array_reduce($cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }
}
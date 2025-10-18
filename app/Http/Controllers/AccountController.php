<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class AccountController extends Controller
{
    public function dashboard()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get user's orders
        $orders = Order::where('user_id', auth()->id())->latest()->take(5)->get();

        return view('frontend.account.dashboard', compact('orders'));
    }

    public function profile()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        return view('frontend.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $user->update($request->only([
            'name', 'email', 'phone', 'address', 'province', 'city', 'district', 'postal_code'
        ]));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);

        return view('frontend.account.orders', compact('orders'));
    }

    public function orderDetail($orderId)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);

        return view('frontend.account.order-detail', compact('order'));
    }
}
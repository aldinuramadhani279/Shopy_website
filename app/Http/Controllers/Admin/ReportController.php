<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Basic statistics for reports
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');
        
        // Recent orders for the report
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        return view('admin.reports.index', compact('totalOrders', 'totalProducts', 'totalCustomers', 'totalRevenue', 'recentOrders'));
    }
}

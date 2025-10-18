<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Sort by total orders or total spent
        if ($request->has('sort') && $request->sort != '') {
            switch ($request->sort) {
                case 'orders_asc':
                    $query->withCount('orders')->orderBy('orders_count', 'asc');
                    break;
                case 'orders_desc':
                    $query->withCount('orders')->orderBy('orders_count', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $customers = $query->paginate(10)->appends(request()->query());
        
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }
        
        $customer->load('orders.orderItems.product');
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Export customers to Excel.
     */
    public function export()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}
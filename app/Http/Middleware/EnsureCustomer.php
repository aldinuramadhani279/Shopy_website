<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            // Jika pengguna tidak login, redirect ke login
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'customer') {
            // Jika pengguna login tapi bukan customer, redirect ke home atau tampilkan error
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            abort(403, 'Unauthorized action. Customer access only.');
        }

        return $next($request);
    }
}
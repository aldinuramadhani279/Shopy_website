<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
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

        if (Auth::user()->role !== 'admin') {
            // Jika pengguna login tapi bukan admin, redirect atau tampilkan error
            if (Auth::user()->role === 'customer') {
                return redirect()->route('home');
            }
            abort(403, 'Unauthorized action. Admin access only.');
        }

        return $next($request);
    }
}
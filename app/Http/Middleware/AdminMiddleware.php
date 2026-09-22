<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        // Check if the user is authenticated via the admin guard and has the admin role
        if (!Auth::guard('admin')->check()) {
            // If not authenticated, redirect to login
            return redirect()->route('login');
        }

        // Assuming the User model has a 'role' field
        if (Auth::guard('admin')->user()->role !== 'Admin') {
            // If not an admin, redirect or abort
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
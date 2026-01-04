<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('accountant.login')->with('error', 'Please login to access this page.');
        }

        if (!Auth::user()->userRole || Auth::user()->userRole->name !== 'Accountant') {
            Auth::logout();
            return redirect()->route('accountant.login')->with('error', 'Access denied. Invalid user role.');
        }

        if (!Auth::user()->status) {
            Auth::logout();
            return redirect()->route('accountant.login')->with('error', 'Your account is inactive. Please contact administrator.');
        }

        if (!Auth::user()->accountant) {
            Auth::logout();
            return redirect()->route('accountant.login')->with('error', 'Accountant profile not found. Please contact administrator.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentMiddleware
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
            return redirect()->route('parent.login')
                ->with('error', 'Please login to access the parent portal.');
        }

        if (!Auth::user()->userRole || Auth::user()->userRole->name !== 'Parent') {
            Auth::logout();
            return redirect()->route('parent.login')
                ->with('error', 'Access denied. This portal is for parents only.');
        }

        if (!Auth::user()->status) {
            Auth::logout();
            return redirect()->route('parent.login')
                ->with('error', 'Your account is inactive. Please contact the school administration.');
        }

        // Check if parent has details record
        if (!Auth::user()->parent) {
            Auth::logout();
            return redirect()->route('parent.login')
                ->with('error', 'Parent profile not found. Please contact the school administration.');
        }

        return $next($request);
    }
}

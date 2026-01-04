<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibrarianMiddleware
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
            return redirect()->route('librarian.login')
                ->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();
        
        if ($user->role_id != 4) { // 4 = Librarian role
            Auth::logout();
            return redirect()->route('librarian.login')
                ->with('error', 'Access denied. Librarian role required.');
        }

        if (!$user->status) { // status is boolean in this system
            Auth::logout();
            return redirect()->route('librarian.login')
                ->with('error', 'Your account is inactive. Please contact administrator.');
        }

        return $next($request);
    }
}
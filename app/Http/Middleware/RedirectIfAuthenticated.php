<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string|null ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect based on role
                $user = Auth::guard($guard)->user();
                $roleName = optional($user->userRole)->name;
                
                if ($roleName === 'Super Admin') {
                    return redirect()->route('superadmin.dashboard');
                } elseif ($roleName === 'Admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($roleName === 'Teacher') {
                    return redirect()->route('teacher.dashboard');
                } elseif ($roleName === 'Student') {
                    return redirect()->route('student.dashboard');
                } elseif ($roleName === 'Parent') {
                    return redirect()->route('parent.dashboard');
                } elseif ($roleName === 'Librarian') {
                    return redirect()->route('librarian.dashboard');
                } elseif ($roleName === 'Accountant') {
                    return redirect()->route('accountant.dashboard');
                } else {
                    return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }
}

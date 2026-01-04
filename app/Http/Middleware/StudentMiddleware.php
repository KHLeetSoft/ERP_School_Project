<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('student.login')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Check if user has a role
        if (!$user->userRole) {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Your account does not have a valid role. Please contact administrator.');
        }

        // Check if user is a student
        if ($user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }

        // Check if user account is active
        if (!$user->status) {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Your account is inactive. Please contact administrator.');
        }

        // Check if student profile exists and is active
        if ($user->student && $user->student->status !== 'active') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Your student profile is inactive. Please contact administrator.');
        }

        // Share student data with all views
        view()->share('student', $user->student);
        view()->share('studentUser', $user);

        return $next($request);
    }
}

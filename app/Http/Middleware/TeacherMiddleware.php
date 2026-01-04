<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
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
            return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Check if user has a role
        if (!$user->userRole) {
            Auth::logout();
            return redirect()->route('teacher.login')->with('error', 'Your account does not have a valid role. Please contact administrator.');
        }

        // Check if user is a teacher
        if ($user->userRole->name !== 'Teacher') {
            Auth::logout();
            return redirect()->route('teacher.login')->with('error', 'Access denied. Teacher role required.');
        }

        // Check if user account is active
        if (!$user->status) {
            Auth::logout();
            return redirect()->route('teacher.login')->with('error', 'Your account is inactive. Please contact administrator.');
        }

        // Check if teacher profile exists and is active
        if ($user->teacher && $user->teacher->status !== 'active') {
            Auth::logout();
            return redirect()->route('teacher.login')->with('error', 'Your teacher profile is inactive. Please contact administrator.');
        }

        // Share teacher data with all views
        view()->share('teacher', $user->teacher);
        view()->share('teacherUser', $user);

        return $next($request);
    }
} 
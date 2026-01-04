<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Where to redirect users after login.
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        $roleName = optional($user->userRole)->name;

        if ($roleName === 'Super Admin') {
            return route('superadmin.dashboard');
        } elseif ($roleName === 'Admin') {
            return route('admin.dashboard');
        } elseif ($roleName === 'Teacher') {
            return route('teacher.dashboard');
        } elseif ($roleName === 'Parent') {
            return route('parent.dashboard');
        } elseif ($roleName === 'Librarian') {
            return route('librarian.dashboard');
        } elseif ($roleName === 'Student') {
            return route('student.dashboard');
        } elseif ($roleName === 'Accountant') {
            return route('accountant.dashboard');
        } else {
            return '/home';
        }
    }

    /**
     * Handle a successful authentication attempt.
     */
    protected function authenticated($request, $user)
    {
        $roleName = optional($user->userRole)->name;
        
        if ($roleName === 'Super Admin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($roleName === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($roleName === 'Teacher') {
            return redirect()->route('teacher.dashboard');
        } elseif ($roleName === 'Parent') {
            return redirect()->route('parent.dashboard');
        } elseif ($roleName === 'Librarian') {
            return redirect()->route('librarian.dashboard');
        } elseif ($roleName === 'Student') {
            return redirect()->route('student.dashboard');
        } elseif ($roleName === 'Accountant') {
            return redirect()->route('accountant.dashboard');
        }

        return redirect()->intended($this->redirectPath());
    }
}

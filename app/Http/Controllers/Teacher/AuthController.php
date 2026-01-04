<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        // Temporarily comment out middleware for testing
        // $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('teacher.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Check if user exists and has teacher role
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && $user->userRole && $user->userRole->name === 'Teacher' && $user->status) {
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('teacher.dashboard'));
            }
        }

        return redirect()->back()
            ->withErrors(['email' => 'The provided credentials do not match our records or you do not have teacher access.'])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('teacher.login');
    }

    public function showRegisterForm()
    {
        return view('teacher.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3, // Teacher role
            'status' => true,
        ]);

        // Create teacher profile
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'qualification' => $request->qualification,
            'subject' => $request->subject,
            'experience' => $request->experience,
            'address' => $request->address,
            'bio' => $request->bio,
            'joining_date' => now(),
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }
}
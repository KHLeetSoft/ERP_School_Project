<?php 
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    protected $guard = 'admin';

    public function __construct()
    {
        // Only apply guest middleware to login methods, not showLoginForm
        $this->middleware('guest:admin')->only(['login']);
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Debug: Log login attempt
        \Log::info('Admin login attempt started', [
            'email' => $request->email,
            'guard' => 'admin'
        ]);

        // Attempt to log in using the "admin" guard
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('admin')->user();

            // Debug: Log user details
            \Log::info('Admin login successful', [
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'role_name' => optional($user->userRole)->name,
                'is_authenticated' => Auth::guard('admin')->check()
            ]);

            // Extra safety: ensure this account indeed has the admin role (role_id = 2)
            if ($user->role_id !== 2) {
                // Not an admin → log-out and show error
                \Log::warning('User is not admin', ['role_id' => $user->role_id]);
                Auth::guard('admin')->logout();
                return redirect()
                    ->route('admin.login')
                    ->withErrors(['email' => 'You are not authorized to log in as Admin.']);
            }

            // Authenticated & role verified → go to dashboard
            \Log::info('Redirecting to admin dashboard');
            return redirect()->intended(route('admin.dashboard'));
        }

        // Debug: Log failed login
        \Log::warning('Admin login failed', [
            'email' => $request->email,
            'guard' => 'admin'
        ]);

        // Invalid credentials
        return redirect()
            ->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }


   public function logout(Request $request)
   {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

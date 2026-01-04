<?php 
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
     use AuthenticatesUsers;

    protected $guard = 'admin';

    public function showLoginForm()
    {
                return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to log in using the "admin" guard
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('admin')->user();

            // Extra safety: ensure this account indeed has the admin role (role_id = 2)
            if ($user->role_id !== 2) {
                // Not an admin → log-out and show error
                Auth::guard('admin')->logout();
                return redirect()
                    ->route('admin.login')
                    ->withErrors(['email' => 'You are not authorized to log in as Admin.']);
            }

            // Authenticated & role verified → go to dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

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

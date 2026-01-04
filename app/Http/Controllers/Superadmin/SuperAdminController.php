<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SuperAdminController extends Controller
{
    // 📊 Dashboard View
    public function dashboard()
    {
        return view('superadmin.dashboard');
    }
    // public function dashboard()
    // {
    //     $totalSchools = School::count();
    //     $activeSchools = School::where('status', true)->count();
    //     $totalAdmins = User::where('role', 'admin')->count();
    //     $totalUsers = User::count();

    //     $recentSchools = School::with('admin')->latest()->take(5)->get();
    //     $recentAdmins = User::where('role', 'admin')->latest()->take(5)->get();

    //     return view('superadmin.dashboard', compact(
    //         'totalSchools',
    //         'activeSchools', 
    //         'totalAdmins',
    //         'totalUsers',
    //         'recentSchools',
    //         'recentAdmins'
    //     ));
    // }

    // 🏫 All Schools
    public function index()
    {
        $schools = School::all();
        return view('superadmin.schools.index', compact('schools'));
    }

    // ➕ Create School
    public function create()
    {
        return view('superadmin.schools.create');
    }

    // 💾 Store School + Admin
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:6',
        ]);

        $admin = User::create([
            'name' => $request->name . ' Admin',
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin',
        ]);

        $school = School::create([
            'name' => $request->name,
            'email' => $request->admin_email,
            'admin_id' => $admin->id,
            'status' => true,
        ]);

        $admin->update(['school_id' => $school->id]);

        return redirect()->route('superadmin.schools.index')->with('success', 'School and Admin created.');
    }

    // ✏️ Edit School
    public function edit($id)
    {
        $school = School::findOrFail($id);
        return view('superadmin.schools.edit', compact('school'));
    }

    // ✅ Update School
    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);
        $school->update([
            'name' => $request->name,
            'theme_settings' => json_encode($request->theme_settings),
            'status' => $request->status ?? true,
        ]);
        return redirect()->route('superadmin.schools.index')->with('success', 'School updated.');
    }

    // ❌ Delete School
    public function destroy($id)
    {
        School::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'School deleted.');
    }

    // 🔐 Logout Super Admin
    public function logout(Request $request)
    {
        Auth::logout();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('superadmin.login');
    }

    // 👤 View Profile
    public function profile()
    {
        $user = Auth::user();
        return view('superadmin.profile', compact('user'));
    }

    // 🔄 Change Password Form
    public function changePasswordForm()
    {
        return view('superadmin.change-password');
    }

    // 🔄 Update Password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('superadmin.profile')->with('success', 'Password changed successfully');
    }
}


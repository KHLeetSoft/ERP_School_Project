<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Librarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LibrarianController extends Controller
{
    /**
     * Show the librarian login form.
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'Librarian') {
            return redirect()->route('librarian.dashboard');
        }
        
        return view('librarian.auth.login');
    }

    /**
     * Show the librarian registration form.
     */
    public function showRegistrationForm()
    {
        if (Auth::check() && Auth::user()->role === 'Librarian') {
            return redirect()->route('librarian.dashboard');
        }
        
        return view('librarian.auth.register');
    }

    /**
     * Handle librarian registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => 'required|string|max:50|unique:librarians',
            'designation' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'joining_date' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'role' => 'Librarian',
            'status' => 'active',
        ]);

        // Handle profile image upload
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $profileImagePath = $image->storeAs('librarian_profiles', $filename, 'public');
        }

        // Create librarian profile
        $librarian = Librarian::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'designation' => $request->designation,
            'department' => $request->department,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'joining_date' => $request->joining_date,
            'profile_image' => $profileImagePath,
            'bio' => $request->bio,
            'status' => 'active',
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect()->route('librarian.dashboard')
            ->with('success', 'Registration successful! Welcome to the Librarian Portal.');
    }

    /**
     * Handle librarian login.
     */
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

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Check if user is a librarian (role_id = 4)
            if (Auth::user()->role_id != 4) {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['email' => 'Access denied. Only librarians can access this portal.'])
                    ->withInput($request->except('password'));
            }
            $request->session()->regenerate();

            // Update last login information
            Auth::user()->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return redirect()->intended(route('librarian.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->except('password'));
    }

    /**
     * Handle librarian logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('librarian.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the librarian dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $librarian = Librarian::where('user_id', $user->id)->first();
        
        // If no librarian profile exists, create a basic one
        if (!$librarian) {
            $librarian = Librarian::create([
                'user_id' => $user->id,
                'employee_id' => 'LIB' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                'designation' => 'Librarian',
                'department' => 'Library Services',
                'status' => 'active',
            ]);
        }

        // Update last login
        $librarian->updateLastLogin(request()->ip());
        
        // Get dashboard statistics
        $stats = [
            'total_books' => 0, // Will be implemented when books module is created
            'available_books' => 0,
            'borrowed_books' => 0,
            'overdue_books' => 0,
            'total_members' => User::where('role_id', 6)->where('status', true)->count(), // 6 = Student
            'total_teachers' => User::where('role_id', 3)->where('status', true)->count(), // 3 = Teacher
            'pending_requests' => 0, // Will be implemented when book requests are created
            'recent_activities' => [], // Will be implemented when activity logs are created
        ];

        // Get recent activities (placeholder for now)
        $recent_activities = [
            [
                'type' => 'login',
                'message' => 'Logged in successfully',
                'time' => $librarian->last_login_at,
                'icon' => 'fas fa-sign-in-alt',
                'color' => 'success'
            ],
            [
                'type' => 'profile',
                'message' => 'Profile updated',
                'time' => $librarian->updated_at,
                'icon' => 'fas fa-user-edit',
                'color' => 'info'
            ]
        ];

        return view('librarian.dashboard', compact('stats', 'recent_activities', 'librarian'));
    }

    /**
     * Show the librarian profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $librarian = Librarian::where('user_id', $user->id)->first();
        
        // Get user statistics for the profile
        $totalUsers = User::whereIn('role_id', [3, 6])->count();
        $activeUsers = User::whereIn('role_id', [3, 6])->where('status', true)->count();
        
        return view('librarian.profile', compact('librarian', 'totalUsers', 'activeUsers'));
    }
    /**
     * Update the librarian profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $librarian = Librarian::where('user_id', $user->id)->first();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'employee_id' => 'nullable|string|max:50|unique:librarians,employee_id,' . ($librarian ? $librarian->id : 'NULL'),
            'designation' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user record
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update or create librarian record
        $librarianData = [
            'employee_id' => $request->employee_id,
            'designation' => $request->designation,
            'department' => $request->department,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'bio' => $request->bio,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('librarian_profiles', $filename, 'public');
            $librarianData['profile_image'] = $path;
        }

        if ($librarian) {
            $librarian->update($librarianData);
        } else {
            $librarianData['user_id'] = $user->id;
            $librarianData['status'] = 'active';
            Librarian::create($librarianData);
        }

        return redirect()->route('librarian.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the change password form.
     */
    public function showChangePasswordForm()
    {
        return view('librarian.change-password');
    }

    /**
     * Handle password change.
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $librarian = Auth::user();

        if (!Hash::check($request->current_password, $librarian->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $librarian->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('librarian.profile')
            ->with('success', 'Password changed successfully!');
    }

    /**
     * Show all users (students and teachers).
     */
    public function users(Request $request)
    {
        $query = User::whereIn('role_id', [3, 6]) // 3 = Teacher, 6 = Student
            ->where('status', true);

        // Apply filters
        if ($request->filled('role')) {
            $roleId = $request->role === 'Student' ? 6 : ($request->role === 'Teacher' ? 3 : null);
            if ($roleId) {
                $query->where('role_id', $roleId);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total_students' => User::where('role_id', 6)->where('status', true)->count(),
            'total_teachers' => User::where('role_id', 3)->where('status', true)->count(),
            'total_users' => User::whereIn('role_id', [3, 6])->where('status', true)->count(),
        ];

        return view('librarian.users.index', compact('users', 'stats'));
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        if (!in_array($user->role, ['Student', 'Teacher'])) {
            abort(404);
        }

        return view('librarian.users.show', compact('user'));
    }

    /**
     * Show librarian settings.
     */
    public function settings()
    {
        $librarian = Auth::user();
        return view('librarian.settings', compact('librarian'));
    }
}
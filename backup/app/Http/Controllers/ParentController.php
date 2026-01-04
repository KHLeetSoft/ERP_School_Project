<?php

namespace App\Http\Controllers;

use App\Models\ParentDetails as ParentModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    /**
     * Show parent login form
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->userRole && Auth::user()->userRole->name === 'Parent') {
            return redirect()->route('parent.dashboard');
        }
        
        return view('parent.auth.login');
    }

    /**
     * Handle parent login
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
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            // Check if user has parent role and is active
            if ($user->userRole && $user->userRole->name === 'Parent' && $user->status) {
                $request->session()->regenerate();
                
                // Update last login
                $user->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                ]);

                return redirect()->intended(route('parent.dashboard'))
                    ->with('success', 'Welcome back!');
            } else {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['email' => 'Invalid credentials or account not active.'])
                    ->withInput();
            }
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid credentials or account not active.'])
            ->withInput();
    }

    /**
     * Show parent registration form
     */
    public function showRegisterForm()
    {
        return view('parent.auth.register');
    }

    /**
     * Handle parent registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone_primary' => 'required|string|max:20',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'occupation_father' => 'nullable|string|max:255',
            'occupation_mother' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 7, // Parent role ID
                'status' => true,
                'phone' => $request->phone_primary,
                'address' => $request->address,
            ]);

            // Create parent details
            ParentModel::create([
                'user_id' => $user->id,
                'school_id' => 1, // Default school ID
                'primary_contact_name' => $request->name,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'phone_primary' => $request->phone_primary,
                'email_primary' => $request->email,
                'address' => $request->address,
                'occupation_father' => $request->occupation_father,
                'occupation_mother' => $request->occupation_mother,
                'status' => 'active',
            ]);

            DB::commit();

            // Auto login after registration
            Auth::login($user);

            return redirect()->route('parent.dashboard')
                ->with('success', 'Registration successful! Welcome to the parent portal.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show parent dashboard
     */
    public function dashboard()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        // Get recent activities for children
        $recentActivities = collect();
        foreach ($children as $child) {
            // Get recent assignments, grades, attendance, etc.
            $recentActivities = $recentActivities->merge([
                // Add recent activities here
            ]);
        }

        $stats = [
            'children_count' => $children->count(),
            'active_children' => $children->where('status', true)->count(),
            'recent_communications' => $parent->communications()->latest()->limit(5)->count(),
        ];

        return view('parent.dashboard', compact('parent', 'children', 'recentActivities', 'stats'));
    }

    /**
     * Show parent profile
     */
    public function profile()
    {
        $parent = Auth::user()->parent;
        return view('parent.profile', compact('parent'));
    }

    /**
     * Update parent profile
     */
    public function updateProfile(Request $request)
    {
        $parent = Auth::user()->parent;
        
        $validator = Validator::make($request->all(), [
            'primary_contact_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone_primary' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'email_primary' => 'required|email|max:255',
            'email_secondary' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'occupation_father' => 'nullable|string|max:255',
            'occupation_mother' => 'nullable|string|max:255',
            'income_range' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $parent->update($request->all());

        // Update user information
        Auth::user()->update([
            'name' => $request->primary_contact_name,
            'email' => $request->email_primary,
            'phone' => $request->phone_primary,
            'address' => $request->address,
        ]);

        return redirect()->back()
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show children list
     */
    public function children()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->paginate(10);
        
        return view('parent.children.index', compact('children'));
    }

    /**
     * Show specific child details
     */
    public function showChild(Student $child)
    {
        $parent = Auth::user()->parent;
        
        // Check if this child belongs to the parent
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        $child->load(['schoolClass', 'section', 'assignments', 'grades']);
        
        return view('parent.children.show', compact('child'));
    }

    /**
     * Show child's academic progress
     */
    public function childProgress(Student $child)
    {
        $parent = Auth::user()->parent;
        
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        $child->load(['schoolClass', 'section', 'grades', 'assignments']);
        
        return view('parent.children.progress', compact('child'));
    }

    /**
     * Show child's attendance
     */
    public function childAttendance(Student $child)
    {
        $parent = Auth::user()->parent;
        
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        $child->load(['schoolClass', 'section']);
        
        return view('parent.children.attendance', compact('child'));
    }

    /**
     * Show communications
     */
    public function communications()
    {
        $parent = Auth::user()->parent;
        $communications = $parent->communications()
            ->with(['sender', 'student'])
            ->latest()
            ->paginate(15);
        
        return view('parent.communications.index', compact('communications'));
    }

    /**
     * Show attendance overview
     */
    public function attendance()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        return view('parent.attendance.index', compact('children'));
    }

    /**
     * Show child attendance detail
     */
    public function childAttendanceDetail(Student $child)
    {
        $parent = Auth::user()->parent;
        
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        return view('parent.attendance.child', compact('child'));
    }

    /**
     * Show results and performance
     */
    public function results()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        return view('parent.results.index', compact('children'));
    }

    /**
     * Show child results
     */
    public function childResults(Student $child)
    {
        $parent = Auth::user()->parent;
        
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        return view('parent.results.child', compact('child'));
    }

    /**
     * Show homework and assignments
     */
    public function homework()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        return view('parent.homework.index', compact('children'));
    }

    /**
     * Show child homework
     */
    public function childHomework(Student $child)
    {
        $parent = Auth::user()->parent;
        
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        return view('parent.homework.child', compact('child'));
    }

    /**
     * Show fee management
     */
    public function fees()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        return view('parent.fees.index', compact('children'));
    }

    /**
     * Show child fees
     */
    public function childFees(Student $child)
    {
        $parent = Auth::user()->parent;
        
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        return view('parent.fees.child', compact('child'));
    }

    /**
     * Process fee payment
     */
    public function processPayment(Request $request)
    {
        // Implement payment processing logic
        return redirect()->back()->with('success', 'Payment processed successfully!');
    }

    /**
     * Show notices and circulars
     */
    public function notices()
    {
        $parent = Auth::user()->parent;
        
        return view('parent.notices.index', compact('parent'));
    }

    /**
     * Show specific notice
     */
    public function showNotice($notice)
    {
        return view('parent.notices.show', compact('notice'));
    }

    /**
     * Show transport information
     */
    public function transport()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        return view('parent.transport.index', compact('children'));
    }

    /**
     * Show transport tracking
     */
    public function transportTracking()
    {
        return view('parent.transport.tracking');
    }

    /**
     * Show library information
     */
    public function library()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        return view('parent.library.index', compact('children'));
    }

    /**
     * Show child library
     */
    public function childLibrary(Student $child)
    {
        $parent = Auth::user()->parent;
        
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        return view('parent.library.child', compact('child'));
    }

    /**
     * Show PTM meetings
     */
    public function ptm()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        return view('parent.ptm.index', compact('children'));
    }

    /**
     * Show specific PTM meeting
     */
    public function showPtm($meeting)
    {
        return view('parent.ptm.show', compact('meeting'));
    }

    /**
     * Submit PTM feedback
     */
    public function submitFeedback(Request $request, $meeting)
    {
        // Implement feedback submission logic
        return redirect()->back()->with('success', 'Feedback submitted successfully!');
    }

    /**
     * Show health records
     */
    public function health()
    {
        $parent = Auth::user()->parent;
        $children = $parent->students()->with(['schoolClass', 'section'])->get();
        
        return view('parent.health.index', compact('children'));
    }

    /**
     * Show child health records
     */
    public function childHealth(Student $child)
    {
        $parent = Auth::user()->parent;
        
        if (!$parent->students()->where('student_id', $child->id)->exists()) {
            abort(403, 'Unauthorized access to child information.');
        }

        return view('parent.health.child', compact('child'));
    }

    /**
     * Logout parent
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('parent.login')
            ->with('success', 'You have been logged out successfully.');
    }
}

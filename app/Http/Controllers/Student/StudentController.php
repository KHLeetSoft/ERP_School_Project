<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['showLoginForm', 'login']);
        $this->middleware('auth')->except(['showLoginForm', 'login']);
    }

    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->userRole && Auth::user()->userRole->name === 'Student') {
            return redirect()->route('student.dashboard');
        }
        
        return view('student.auth.login');
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

        // Check if user exists and has student role
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && $user->userRole && $user->userRole->name === 'Student' && $user->status) {
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'));
            }
        }

        return redirect()->back()
            ->withErrors(['email' => 'The provided credentials do not match our records or you do not have student access.'])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);
        // Get student statistics
        $stats = [
            'attendance_percentage' => $this->getAttendancePercentage($student),
            'upcoming_exams' => $this->getUpcomingExams($student),
            'recent_assignments' => $this->getRecentAssignments($student),
            'fee_status' => $this->getFeeStatus($student),
        ];

        return view('student.dashboard', compact('user', 'student', 'stats'));
    }

    public function profile()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);
        
        return view('student.profile', compact('user', 'student'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->student) {
            $user->student->update([
                'phone' => $request->phone,
            ]);
        }

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('student.profile')->with('success', 'Password changed successfully.');
    }

    private function getAttendancePercentage($student)
    {
        // This would typically query the attendance table
        // For now, return a mock value
        return 85.5;
    }

    private function getUpcomingExams($student)
    {
        // This would typically query the exams table
        // For now, return a mock value
        return [];
    }

    private function getRecentAssignments($student)
    {
        // This would typically query the assignments table
        // For now, return a mock value
        return [];
    }

    private function getFeeStatus($student)
    {
        // This would typically query the fees table
        // For now, return a mock value
        return 'Paid';
    }
}

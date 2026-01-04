<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ExamSchedule;
use App\Models\ExamMark;
use App\Models\Attendance;
use App\Models\User;
use App\Models\DailyThought;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
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
        });
    }

   public function index()
{
    $user = Auth::user();
    $teacher = $user->teacher;

    // Total classes assigned to this teacher
    $totalClasses = $teacher ? $teacher->classes()->count() : 0;

    // Total students under this teacher’s classes
    $totalStudents = $teacher ? $teacher->students()->count() : 0;

    // Total exams created by this teacher
    $totalExams = $teacher ? $teacher->exams()->count() : 0;

    // Upcoming classes (example: filter by future start_date)
    $upcomingClasses = $teacher 
        ? $teacher->classes()
                  ->where('start_date', '>=', now())
                  ->orderBy('start_date')
                  ->take(5)
                  ->get()
        : collect();

    // Recent activities (example: last 5 exams created)
    $recentActivities = $teacher 
        ? $teacher->exams()->latest()->take(5)->get()
        : collect();

    // Daily bilingual thought persisted per day
    $today = now()->toDateString();
    $record = DailyThought::where('date', $today)->first();
    if (!$record) {
        $fallbacks = [
            ['en' => 'Every day is a chance to learn and grow.', 'hi' => 'हर दिन सीखने और बढ़ने का एक अवसर है।'],
            ['en' => 'Small steps lead to big achievements.', 'hi' => 'छोटे कदम बड़ी उपलब्धियों की ओर ले जाते हैं।'],
            ['en' => 'Believe in yourself and all that you are.', 'hi' => 'खुद पर और जो आप हैं उस पर विश्वास रखें।'],
            ['en' => 'Consistency is the key to success.', 'hi' => 'निरंतरता सफलता की कुंजी है।'],
            ['en' => 'Be the reason someone smiles today.', 'hi' => 'आज किसी की मुस्कान की वजह बनें।'],
        ];
        $pick = $fallbacks[array_rand($fallbacks)];
        $record = DailyThought::create([
            'date' => $today,
            'thought_en' => $pick['en'],
            'thought_hi' => $pick['hi'],
        ]);
    }
    $dailyThought = ['en' => $record->thought_en, 'hi' => $record->thought_hi];

    $data = [
        'user' => $user,
        'teacher' => $teacher,
        'total_classes' => $totalClasses,
        'total_students' => $totalStudents,
        'total_exams' => $totalExams,
        'upcoming_classes' => $upcomingClasses,
        'recent_activities' => $recentActivities,
        'daily_thought' => $dailyThought,
    ];

    return view('teacher.dashboard', compact('data'));
}

    public function profile()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        return view('teacher.profile', compact('user', 'teacher'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
        ]);

        // Update user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update teacher information
        if ($teacher) {
            $teacher->update([
                'phone' => $request->phone,
                'qualification' => $request->qualification,
                'subject' => $request->subject,
                'experience' => $request->experience,
                'address' => $request->address,
                'bio' => $request->bio,
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
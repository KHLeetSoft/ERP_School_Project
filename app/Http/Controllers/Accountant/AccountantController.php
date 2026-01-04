<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\AccountantDetails;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Section;
use Carbon\Carbon;

class AccountantController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['showLoginForm', 'login', 'showRegisterForm', 'register']);
    }

    // Authentication Methods
    public function showLoginForm()
    {
        return view('accountant.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            if ($user->userRole && $user->userRole->name === 'Accountant' && $user->status) {
                $request->session()->regenerate();
                $user->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip()
                ]);
                
                return redirect()->intended(route('accountant.dashboard'))->with('success', 'Welcome back!');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Invalid credentials or account not active.'])->withInput();
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials or account not active.'])->withInput();
    }

    public function showRegisterForm()
    {
        return view('accountant.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 8, // Accountant role
            'status' => true,
            'school_id' => 1, // Default school
            'admin_id' => 1, // Default admin
        ]);

        AccountantDetails::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'salary' => $request->salary,
            'joining_date' => now(),
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('accountant.dashboard')->with('success', 'Account created successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('accountant.login')->with('success', 'You have been logged out successfully.');
    }

    // Dashboard Methods
    public function dashboard()
    {
        $user = Auth::user();
        $accountant = $user->accountant;
        
        // Get dashboard statistics
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', true)->count(),
            'total_fees_collected' => Payment::where('status', 'completed')->sum('amount'),
            'pending_fees' => Fee::whereNull('transaction_id')->sum('amount'),
            'monthly_collection' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'recent_payments' => Payment::with('student', 'fee')
                ->latest()
                ->limit(5)
                ->get(),
        ];

        // Get recent activities
        $recentActivities = [
            [
                'title' => 'Fee Payment Received',
                'description' => 'Payment of ₹2,500 received from John Doe',
                'time' => '2 hours ago',
                'icon' => 'fas fa-credit-card',
                'color' => 'success'
            ],
            [
                'title' => 'New Fee Structure Updated',
                'description' => 'Monthly fees updated for Class 10',
                'time' => '1 day ago',
                'icon' => 'fas fa-edit',
                'color' => 'info'
            ],
            [
                'title' => 'Payment Reminder Sent',
                'description' => 'Reminder sent to 15 parents for pending fees',
                'time' => '2 days ago',
                'icon' => 'fas fa-bell',
                'color' => 'warning'
            ],
        ];

        return view('accountant.dashboard', compact('stats', 'recentActivities', 'accountant'));
    }

    // Profile Methods
    public function profile()
    {
        $user = Auth::user();
        $accountant = $user->accountant;
        
        return view('accountant.profile', compact('user', 'accountant'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $accountant = $user->accountant;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $accountant->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'salary' => $request->salary,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        return view('accountant.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    // Fee Management Methods
    public function fees()
    {
        $fees = Fee::with('student', 'schoolClass')
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_fees' => Fee::count(),
            'pending_fees' => Fee::whereNull('transaction_id')->count(),
            'paid_fees' => Fee::whereNotNull('transaction_id')->count(),
            'total_amount' => Fee::sum('amount'),
            'pending_amount' => Fee::whereNull('transaction_id')->sum('amount'),
        ];

        return view('accountant.fees.index', compact('fees', 'stats'));
    }

    public function createFee()
    {
        $students = Student::with('schoolClass', 'section')->get();
        $classes = SchoolClass::all();
        
        return view('accountant.fees.create', compact('students', 'classes'));
    }

    public function storeFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'fee_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Fee::create([
            'student_id' => $request->student_id,
            'class_id' => $request->class_id ?? 1, // Default class if not provided
            'amount' => $request->amount,
            'fee_date' => $request->due_date,
            'remarks' => $request->description,
            'payment_mode' => 'pending', // Use payment_mode instead of status
        ]);

        return redirect()->route('accountant.fees')->with('success', 'Fee created successfully!');
    }

    public function editFee(Fee $fee)
    {
        $students = Student::with('schoolClass', 'section')->get();
        $classes = SchoolClass::all();
        
        return view('accountant.fees.edit', compact('fee', 'students', 'classes'));
    }

    public function updateFee(Request $request, Fee $fee)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'fee_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fee->update([
            'student_id' => $request->student_id,
            'class_id' => $request->class_id ?? $fee->class_id,
            'amount' => $request->amount,
            'fee_date' => $request->due_date,
            'remarks' => $request->description,
            'payment_mode' => $request->status ?? $fee->payment_mode,
        ]);

        return redirect()->route('accountant.fees')->with('success', 'Fee updated successfully!');
    }

    public function deleteFee(Fee $fee)
    {
        $fee->delete();
        return redirect()->route('accountant.fees')->with('success', 'Fee deleted successfully!');
    }

    // Payment Methods
    public function payments()
    {
        $payments = Payment::with('student', 'fee')
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_payments' => Payment::count(),
            'completed_payments' => Payment::where('status', 'completed')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_amount' => Payment::sum('amount'),
            'completed_amount' => Payment::where('status', 'completed')->sum('amount'),
        ];

        return view('accountant.payments.index', compact('payments', 'stats'));
    }

    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fee_id' => 'required|exists:fees,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fee = Fee::findOrFail($request->fee_id);

        if ($request->amount > $fee->amount) {
            return redirect()->back()->withErrors(['amount' => 'Payment amount cannot exceed fee amount.'])->withInput();
        }

        $payment = Payment::create([
            'fee_id' => $request->fee_id,
            'student_id' => $fee->student_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
            'status' => 'completed',
            'processed_by' => Auth::id(),
        ]);

        // Update fee payment_mode if fully paid
        if ($request->amount >= $fee->amount) {
            $fee->update(['payment_mode' => 'paid']);
        }

        return redirect()->route('accountant.payments')->with('success', 'Payment processed successfully!');
    }

    // Reports Methods
    public function reports()
    {
        $monthlyData = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $classWiseData = Fee::join('school_classes', 'students_fees.class_id', '=', 'school_classes.id')
            ->selectRaw('school_classes.name as class_name, SUM(students_fees.amount) as total_fees, SUM(CASE WHEN students_fees.payment_mode = "paid" THEN students_fees.amount ELSE 0 END) as paid_fees')
            ->groupBy('school_classes.id', 'school_classes.name')
            ->get();

        return view('accountant.reports.index', compact('monthlyData', 'classWiseData'));
    }

    // Student Management Methods
    public function students()
    {
        $students = Student::with('schoolClass', 'section', 'parent')
            ->latest()
            ->paginate(20);
        
        $classes = SchoolClass::all();
        $sections = Section::all();

        return view('accountant.students.index', compact('students', 'classes', 'sections'));
    }

    public function studentFees(Student $student)
    {
        $fees = $student->fees()->latest()->get();
        $payments = $student->payments()->latest()->get();
        
        return view('accountant.students.fees', compact('student', 'fees', 'payments'));
    }
}

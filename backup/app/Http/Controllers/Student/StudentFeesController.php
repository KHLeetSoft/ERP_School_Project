<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentFee;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentFeesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
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

        // Get current academic year
        $academicYear = $request->get('academic_year', date('Y'));
        
        // Get student's fees
        $fees = $this->getStudentFees($student, $academicYear);
        
        // Get fee structure for student's class
        $feeStructure = $this->getFeeStructure($student, $academicYear);
        
        // Calculate fee statistics
        $stats = $this->calculateFeeStats($fees);
        
        return view('student.fees.index', compact(
            'fees', 
            'feeStructure', 
            'stats',
            'academicYear'
        ));
    }

    public function structure(Request $request)
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

        $academicYear = $request->get('academic_year', date('Y'));
        
        // Get detailed fee structure
        $feeStructure = $this->getDetailedFeeStructure($student, $academicYear);
        
        // Calculate total fees
        $totalFees = $feeStructure->sum('amount');
        
        return view('student.fees.structure', compact(
            'feeStructure', 
            'totalFees',
            'academicYear'
        ));
    }

    public function history(Request $request)
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

        $status = $request->get('status', 'all');
        $year = $request->get('year', date('Y'));
        
        // Get payment history
        $payments = $this->getPaymentHistory($student, $status, $year);
        
        // Calculate payment statistics
        $stats = $this->calculatePaymentStats($payments);
        
        return view('student.fees.history', compact(
            'payments', 
            'stats',
            'status',
            'year'
        ));
    }

    public function show($id)
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

        $fee = StudentFee::with(['schoolClass'])
            ->where('id', $id)
            ->where('student_id', $student->user_id)
            ->firstOrFail();
        
        // Get payments for this fee
        $payments = Payment::where('fee_id', $id)
            ->where('student_id', $student->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('student.fees.show', compact('fee', 'payments'));
    }

    public function invoice($id)
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

        $fee = StudentFee::with(['schoolClass'])
            ->where('id', $id)
            ->where('student_id', $student->user_id)
            ->firstOrFail();
        
        // Get payments for this fee
        $payments = Payment::where('fee_id', $id)
            ->where('student_id', $student->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('student.fees.invoice', compact('fee', 'payments'));
    }

    private function getStudentFees($student, $academicYear)
    {
        if (!$student) {
            return collect();
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if (!$studentDetail) {
            return collect();
        }

        return StudentFee::with(['schoolClass'])
            ->where('student_id', $studentDetail->id)
            ->whereYear('fee_date', $academicYear)
            ->orderBy('fee_date', 'desc')
            ->get();
    }

    private function getFeeStructure($student, $academicYear)
    {
        if (!$student) {
            return collect();
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if (!$studentDetail) {
            return collect();
        }

        return FeeStructure::where('class_id', $studentDetail->class_id ?? 0)
            ->where('academic_year', $academicYear)
            ->where('is_active', true)
            ->orderBy('fee_type')
            ->get();
    }

    private function getDetailedFeeStructure($student, $academicYear)
    {
        return $this->getFeeStructure($student, $academicYear);
    }

    private function getPaymentHistory($student, $status, $year)
    {
        if (!$student) {
            return collect();
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if (!$studentDetail) {
            return collect();
        }

        $query = Payment::with(['fee.schoolClass'])
            ->where('student_id', $studentDetail->id)
            ->whereYear('created_at', $year);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    private function calculateFeeStats($fees)
    {
        if ($fees->isEmpty()) {
            return [
                'total_fees' => 0,
                'paid_fees' => 0,
                'pending_fees' => 0,
                'overdue_fees' => 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'pending_amount' => 0,
            ];
        }

        $totalFees = $fees->count();
        $totalAmount = $fees->sum('amount');
        
        // For now, we'll assume all fees are pending since the current structure doesn't have status
        // In a real implementation, you'd check payment records
        $paidFees = 0; // This would be calculated based on payment records
        $pendingFees = $totalFees - $paidFees;
        $overdueFees = $fees->filter(function($fee) {
            return $fee->fee_date && Carbon::now()->gt($fee->fee_date);
        })->count();

        return [
            'total_fees' => $totalFees,
            'paid_fees' => $paidFees,
            'pending_fees' => $pendingFees,
            'overdue_fees' => $overdueFees,
            'total_amount' => $totalAmount,
            'paid_amount' => 0, // This would be calculated from payment records
            'pending_amount' => $totalAmount,
        ];
    }

    private function calculatePaymentStats($payments)
    {
        if ($payments->isEmpty()) {
            return [
                'total_payments' => 0,
                'completed_payments' => 0,
                'pending_payments' => 0,
                'failed_payments' => 0,
                'total_amount' => 0,
                'completed_amount' => 0,
            ];
        }

        $totalPayments = $payments->count();
        $completedPayments = $payments->where('status', 'completed')->count();
        $pendingPayments = $payments->where('status', 'pending')->count();
        $failedPayments = $payments->where('status', 'failed')->count();
        
        $totalAmount = $payments->sum('amount');
        $completedAmount = $payments->where('status', 'completed')->sum('amount');

        return [
            'total_payments' => $totalPayments,
            'completed_payments' => $completedPayments,
            'pending_payments' => $pendingPayments,
            'failed_payments' => $failedPayments,
            'total_amount' => $totalAmount,
            'completed_amount' => $completedAmount,
        ];
    }
}

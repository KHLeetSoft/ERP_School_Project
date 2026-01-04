<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\FeeHead;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FeeStructureExport;
use App\Imports\FeeStructureImport;

class FeeStructureController extends Controller
{
    protected $adminUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->adminUser = auth()->guard('admin')->user();
            $this->schoolId = $this->adminUser ? $this->adminUser->school_id : null;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $feeStructures = FeeStructure::with(['schoolClass', 'section', 'student', 'feeHead'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.fees.fee-structures.index', compact('feeStructures'));
    }

    public function create()
    {
        $schoolClasses = SchoolClass::where('status', 'active')->get();
        $sections = Section::where('status', 'active')->get();
        $feeHeads = FeeHead::where('is_active', true)->orderBy('sort_order')->get();
        
        return view('admin.fees.fee-structures.create', compact('schoolClasses', 'sections', 'feeHeads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'student_id' => 'nullable|exists:students,id',
            'fee_head_id' => 'required|exists:fee_heads,id',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,quarterly,yearly,one_time',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        FeeStructure::create([
            'name' => $request->name,
            'school_class_id' => $request->school_class_id,
            'section_id' => $request->section_id,
            'student_id' => $request->student_id,
            'fee_head_id' => $request->fee_head_id,
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.fee-structures.index')
            ->with('success', 'Fee structure created successfully.');
    }

    public function show(FeeStructure $feeStructure)
    {
        $this->authorizeSchool($feeStructure);
        return view('admin.finance.fee-structure.show', compact('feeStructure'));
    }

    public function edit(FeeStructure $feeStructure)
    {
        $this->authorizeSchool($feeStructure);
        $classes = SchoolClass::where('school_id', auth()->user()->school_id ?? 1)->get();
        $academicYears = $this->getAcademicYears();
        $feeTypes = $this->getFeeTypes();
        $frequencies = $this->getFrequencies();
        
        return view('admin.finance.fee-structure.edit', compact('feeStructure', 'classes', 'academicYears', 'feeTypes', 'frequencies'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $this->authorizeSchool($feeStructure);
        
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'academic_year' => 'required|string|max:20',
            'fee_type' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,quarterly,half_yearly,yearly,one_time',
            'due_date' => 'nullable|date',
            'late_fee' => 'nullable|numeric|min:0',
            'discount_applicable' => 'boolean',
            'max_discount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $feeStructure->update([
            'class_id' => $request->class_id,
            'academic_year' => $request->academic_year,
            'fee_type' => $request->fee_type,
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'due_date' => $request->due_date,
            'late_fee' => $request->late_fee ?? 0,
            'discount_applicable' => $request->has('discount_applicable'),
            'max_discount' => $request->max_discount ?? 0,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.finance.fee-structure.index')
            ->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $this->authorizeSchool($feeStructure);
        $feeStructure->delete();
        
        return redirect()->route('admin.finance.fee-structure.index')
            ->with('success', 'Fee structure deleted successfully.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        // Get academic years
        $academicYears = FeeStructure::where('school_id', $schoolId)
            ->distinct()
            ->pluck('academic_year')
            ->sort()
            ->values();
        
        $currentYear = $academicYears->last() ?? date('Y') . '-' . (date('Y') + 1);
        
        // Fee structure statistics
        $totalFeeStructures = FeeStructure::where('school_id', $schoolId)->count();
        $activeFeeStructures = FeeStructure::where('school_id', $schoolId)->where('is_active', true)->count();
        $totalAmount = FeeStructure::where('school_id', $schoolId)->sum('amount');
        $avgAmount = $totalFeeStructures > 0 ? $totalAmount / $totalFeeStructures : 0;
        
        // Fee types distribution
        $feeTypes = FeeStructure::where('school_id', $schoolId)
            ->selectRaw('fee_type, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('fee_type')
            ->orderByDesc('total_amount')
            ->limit(8)
            ->get();
        
        // Frequency distribution
        $frequencies = FeeStructure::where('school_id', $schoolId)
            ->selectRaw('frequency, COUNT(*) as count')
            ->groupBy('frequency')
            ->get();
        
        // Class-wise fee distribution
        $classDistribution = FeeStructure::where('school_id', $schoolId)
            ->with('class')
            ->selectRaw('class_id, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('class_id')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
        
        // Monthly trend for current academic year
        $monthlyTrend = collect(range(11, 0))->map(function($i) use ($schoolId, $currentYear) {
            $date = Carbon::today()->subMonths($i);
            return [
                'month' => $date->format('M Y'),
                'count' => FeeStructure::where('school_id', $schoolId)
                    ->where('academic_year', $currentYear)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'amount' => FeeStructure::where('school_id', $schoolId)
                    ->where('academic_year', $currentYear)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount')
            ];
        })->reverse()->values();
        
        // Discount statistics
        $discountStats = [
            'with_discount' => FeeStructure::where('school_id', $schoolId)->where('discount_applicable', true)->count(),
            'without_discount' => FeeStructure::where('school_id', $schoolId)->where('discount_applicable', false)->count(),
            'total_discount' => FeeStructure::where('school_id', $schoolId)->sum('max_discount')
        ];
        
        // Late fee statistics
        $lateFeeStats = [
            'with_late_fee' => FeeStructure::where('school_id', $schoolId)->where('late_fee', '>', 0)->count(),
            'total_late_fee' => FeeStructure::where('school_id', $schoolId)->sum('late_fee')
        ];
        
        // Additional statistics for enhanced dashboard
        $highestAmount = FeeStructure::where('school_id', $schoolId)->max('amount');
        $lowestAmount = FeeStructure::where('school_id', $schoolId)->min('amount');
        $totalClasses = SchoolClass::where('school_id', $schoolId)->count();
        
        // Most popular fee type
        $mostPopularFeeTypeQuery = FeeStructure::where('school_id', $schoolId)
            ->selectRaw('fee_type, COUNT(*) as count')
            ->groupBy('fee_type')
            ->orderByDesc('count')
            ->first();
        $mostPopularFeeType = $mostPopularFeeTypeQuery ? $mostPopularFeeTypeQuery->fee_type : 'N/A';
        
        // Frequency breakdown
        $monthlyCount = FeeStructure::where('school_id', $schoolId)->where('frequency', 'monthly')->count();
        $quarterlyCount = FeeStructure::where('school_id', $schoolId)->where('frequency', 'quarterly')->count();
        $halfYearlyCount = FeeStructure::where('school_id', $schoolId)->where('frequency', 'half_yearly')->count();
        $yearlyCount = FeeStructure::where('school_id', $schoolId)->where('frequency', 'yearly')->count();
        
        // Financial overview
        $totalRevenue = $totalAmount; // Total potential revenue
        $avgPerClass = $totalClasses > 0 ? $totalAmount / $totalClasses : 0;
        $maxDiscount = FeeStructure::where('school_id', $schoolId)->max('max_discount');
        $totalLateFees = $lateFeeStats['total_late_fee'];
        
        // Performance metrics
        $activeRate = $totalFeeStructures > 0 ? ($activeFeeStructures / $totalFeeStructures) * 100 : 0;
        $discountRate = $totalFeeStructures > 0 ? ($discountStats['with_discount'] / $totalFeeStructures) * 100 : 0;
        $lateFeeRate = $totalFeeStructures > 0 ? ($lateFeeStats['with_late_fee'] / $totalFeeStructures) * 100 : 0;
        
        // Growth rate calculation (comparing current year with previous)
        $currentYearCount = FeeStructure::where('school_id', $schoolId)
            ->where('academic_year', $currentYear)
            ->count();
        $previousYear = date('Y', strtotime('-1 year')) . '-' . date('Y');
        $previousYearCount = FeeStructure::where('school_id', $schoolId)
            ->where('academic_year', $previousYear)
            ->count();
        $growthRate = $previousYearCount > 0 ? (($currentYearCount - $previousYearCount) / $previousYearCount) * 100 : 0;
        
        // Additional data for enhanced charts
        $amountRanges = [
            '0-1000' => FeeStructure::where('school_id', $schoolId)->whereBetween('amount', [0, 1000])->count(),
            '1001-5000' => FeeStructure::where('school_id', $schoolId)->whereBetween('amount', [1001, 5000])->count(),
            '5001-10000' => FeeStructure::where('school_id', $schoolId)->whereBetween('amount', [5001, 10000])->count(),
            '10001-20000' => FeeStructure::where('school_id', $schoolId)->whereBetween('amount', [10001, 20000])->count(),
            '20000+' => FeeStructure::where('school_id', $schoolId)->where('amount', '>', 20000)->count(),
        ];
        
        // Academic year comparison data
        $academicYearComparison = [];
        foreach ($academicYears as $year) {
            $academicYearComparison[$year] = [
                'active' => FeeStructure::where('school_id', $schoolId)->where('academic_year', $year)->where('is_active', true)->count(),
                'inactive' => FeeStructure::where('school_id', $schoolId)->where('academic_year', $year)->where('is_active', false)->count(),
            ];
        }
        
        // Class performance ranking
        $classPerformance = FeeStructure::where('school_id', $schoolId)
            ->with('class')
            ->selectRaw('class_id, AVG(amount) as avg_amount, COUNT(*) as count')
            ->groupBy('class_id')
            ->orderByDesc('avg_amount')
            ->limit(5)
            ->get();
        
        // Fee type efficiency (based on amount and frequency)
        $feeTypeEfficiency = FeeStructure::where('school_id', $schoolId)
            ->selectRaw('fee_type, AVG(amount) as avg_amount, COUNT(*) as count')
            ->groupBy('fee_type')
            ->orderByDesc('avg_amount')
            ->limit(4)
            ->get();
        
        return view('admin.finance.fee-structure.dashboard', compact(
            'totalFeeStructures', 'activeFeeStructures', 'totalAmount', 'avgAmount',
            'feeTypes', 'frequencies', 'classDistribution', 'monthlyTrend',
            'discountStats', 'lateFeeStats', 'academicYears', 'currentYear',
            'highestAmount', 'lowestAmount', 'totalClasses', 'mostPopularFeeType',
            'monthlyCount', 'quarterlyCount', 'halfYearlyCount', 'yearlyCount',
            'totalRevenue', 'avgPerClass', 'maxDiscount', 'totalLateFees',
            'activeRate', 'discountRate', 'lateFeeRate', 'growthRate',
            'amountRanges', 'academicYearComparison', 'classPerformance', 'feeTypeEfficiency'
        ));
    }

    public function export()
    {
        return Excel::download(new FeeStructureExport(auth()->user()->school_id ?? 1), 'fee-structures.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        
        Excel::import(new FeeStructureImport(auth()->user()->school_id ?? 1), $request->file('file'));
        
        return back()->with('success', 'Fee structures imported successfully.');
    }

    public function toggleStatus(FeeStructure $feeStructure)
    {
        $this->authorizeSchool($feeStructure);
        
        $feeStructure->update([
            'is_active' => !$feeStructure->is_active,
            'updated_by' => Auth::id()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'is_active' => $feeStructure->is_active
        ]);
    }

    public function getByClass(Request $request)
    {
        $classId = $request->class_id;
        $academicYear = $request->academic_year;
        
        $feeStructures = FeeStructure::where('school_id', auth()->user()->school_id ?? 1)
            ->where('class_id', $classId)
            ->where('academic_year', $academicYear)
            ->where('is_active', true)
            ->get();
        
        return response()->json($feeStructures);
    }

    private function authorizeSchool(FeeStructure $feeStructure): void
    {
        if (($feeStructure->school_id ?? null) !== (auth()->user()->school_id ?? 1)) {
            abort(403);
        }
    }

    private function getAcademicYears(): array
    {
        $currentYear = date('Y');
        $years = [];
        
        for ($i = 0; $i < 5; $i++) {
            $year = $currentYear - $i;
            $years[] = $year . '-' . ($year + 1);
        }
        
        return $years;
    }

    private function getFeeTypes(): array
    {
        return [
            'Tuition Fee',
            'Transport Fee',
            'Library Fee',
            'Laboratory Fee',
            'Sports Fee',
            'Computer Fee',
            'Examination Fee',
            'Development Fee',
            'Admission Fee',
            'Other Fee'
        ];
    }

    private function getFrequencies(): array
    {
        return [
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'half_yearly' => 'Half Yearly',
            'yearly' => 'Yearly',
            'one_time' => 'One Time'
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentFee;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use Yajra\DataTables\Facades\DataTables;

class StudentFeeController extends Controller
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
        if ($request->ajax()) {
            $adminUser = auth()->user();

            if (!$adminUser || !$adminUser->id || $adminUser->role_id != 2) {
                return DataTables::of(collect([]))->make(true);
            }
            $query = StudentFee::with(['student.user', 'schoolClass'])->latest();

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    $student = $row->student;
                    $name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                    return e($name ?: ($student->user->name ?? '-'));
                })
                ->addColumn('class_name', fn($row) => e($row->schoolClass->name ?? '-'))
                ->addColumn('amount', fn($row) => number_format($row->amount, 2))
                ->addColumn('fee_date', fn($row) => e(optional($row->fee_date)->format('Y-m-d') ?: $row->fee_date))
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.students.fees.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.fees.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-fee" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        return view('admin.students.fees.index', compact('classes', 'students'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        return view('admin.students.fees.create', compact('classes', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'class_id' => 'required|exists:school_classes,id',
            'amount' => 'required|numeric|min:0',
            'fee_date' => 'required|date',
            'payment_mode' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        StudentFee::create($request->all());
        return redirect()->route('admin.students.fees.index')->with('success', 'Fee record added successfully.');
    }

    public function edit($id)
    {
        $fee = StudentFee::findOrFail($id);
        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        return view('admin.students.fees.edit', compact('fee', 'classes', 'students'));
    }

    public function show($id)
    {
        $fee = StudentFee::with(['student.user', 'schoolClass'])->findOrFail($id);
        return view('admin.students.fees.show', compact('fee'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'class_id' => 'required|exists:school_classes,id',
            'amount' => 'required|numeric|min:0',
            'fee_date' => 'required|date',
            'payment_mode' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $fee = StudentFee::findOrFail($id);
        $fee->update($request->all());
        return redirect()->route('admin.students.fees.index')->with('success', 'Fee record updated successfully.');
    }

    public function destroy($id)
    {
        StudentFee::findOrFail($id)->delete();
        return response()->json(['message' => 'Fee record deleted successfully.']);
    }
}



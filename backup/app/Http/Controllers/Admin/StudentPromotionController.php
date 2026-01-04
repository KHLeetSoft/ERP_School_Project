<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentPromotion;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use App\Models\Section;
use Yajra\DataTables\Facades\DataTables;

class StudentPromotionController extends Controller
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
            $query = StudentPromotion::with(['student.user', 'fromClass', 'toClass', 'fromSection', 'toSection'])->latest();

            if ($request->filled('class_id')) {
                $query->where('from_class_id', $request->class_id);
            }
            if ($request->filled('to_class_id')) {
                $query->where('to_class_id', $request->to_class_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    $s = $row->student;
                    $name = trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? ''));
                    return e($name ?: ($s->user->name ?? '-'));
                })
                ->addColumn('from_class', fn($row) => e($row->fromClass->name ?? '-'))
                ->addColumn('to_class', fn($row) => e($row->toClass->name ?? '-'))
                ->addColumn('promoted_at', fn($row) => e(optional($row->promoted_at)->format('Y-m-d')))
                ->addColumn('status', function ($row) {
                    $colorClass = '';
                    switch (strtolower($row->status)) {
                        case 'promoted':
                            $colorClass = 'bg-success text-white';
                            break;
                        case 'retained':
                            $colorClass = 'bg-danger text-white';
                            break;
                        case 'transfered':
                            $colorClass = 'bg-warning text-dark';
                            break;
                        case 'active':
                            $colorClass = 'badge-light-success';
                            break;
                        case 'inactive':
                            $colorClass = 'badge-light-danger';
                            break;
                        default:
                            $colorClass = 'bg-secondary text-white';
                    }
                
                    return '<span class="badge ' . $colorClass . '">' . e(ucfirst($row->status)) . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.students.promotions.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.promotions.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-promotion" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['status','actions'])
                ->make(true);
        }

        $classes = SchoolClass::all();
        return view('admin.students.promotions.index', compact('classes'));
    }

    public function create()
    {
        $students = StudentDetail::all();
        $classes = SchoolClass::all();
        $sections = Section::all();
        return view('admin.students.promotions.create', compact('students', 'classes', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'from_class_id' => 'required|exists:school_classes,id',
            'to_class_id' => 'required|exists:school_classes,id',
            'from_section_id' => 'nullable|exists:sections,id',
            'to_section_id' => 'nullable|exists:sections,id',
            'promoted_at' => 'nullable|date',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        StudentPromotion::create($request->all());
        return redirect()->route('admin.students.promotions.index')->with('success', 'Student promotion recorded.');
    }

    public function show($id)
    {
        $promotion = StudentPromotion::with(['student.user', 'fromClass', 'toClass', 'fromSection', 'toSection'])->findOrFail($id);
        return view('admin.students.promotions.show', compact('promotion'));
    }

    public function edit($id)
    {
        $promotion = StudentPromotion::findOrFail($id);
        $students = StudentDetail::all();
        $classes = SchoolClass::all();
        $sections = Section::all();
        return view('admin.students.promotions.edit', compact('promotion', 'students', 'classes', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'from_class_id' => 'required|exists:school_classes,id',
            'to_class_id' => 'required|exists:school_classes,id',
            'from_section_id' => 'nullable|exists:sections,id',
            'to_section_id' => 'nullable|exists:sections,id',
            'promoted_at' => 'nullable|date',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $promotion = StudentPromotion::findOrFail($id);
        $promotion->update($request->all());
        return redirect()->route('admin.students.promotions.index')->with('success', 'Student promotion updated.');
    }

    public function destroy($id)
    {
        StudentPromotion::findOrFail($id)->delete();
        return response()->json(['message' => 'Student promotion deleted.']);
    }
}



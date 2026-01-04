<?php
namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coverage;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class CoverageController extends Controller
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
            $coverages = Coverage::with(['schoolClass', 'subject', 'teacher', 'section', 'school'])->latest();

            return DataTables::of($coverages)
                ->addIndexColumn()
                ->addColumn('class_name', function ($row) {
                return $row->schoolClass->name ?? '-';
            })
            ->addColumn('section_name', function ($row) {
                return $row->section->name ?? '-';
            })
            ->addColumn('subject_name', function ($row) {
                return $row->subject->name ?? '-';
            })
          ->addColumn('teacher_name', function ($row) {
                $teacher = User::where('id', $row->teacher_id)->where('role_id', 3)->first();
                return $teacher ? $teacher->name : '-';
            })
                ->addColumn('status', fn($row) => ucfirst($row->status))
                ->addColumn('priority', fn($row) => ucfirst($row->priority))
            ->addColumn('created_at', function($row){
                return $row->created_at ? $row->created_at->format('d-m-Y') : '-';
            })
                ->addColumn('actions', function($row){
                    $buttons = '<div class="d-flex justify-content-center">';
                    $buttons .= '<a href="' . route('admin.academic.coverage.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.academic.coverage.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-coverage-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.academic.coverage.index');
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        return view('admin.academic.coverage.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'class_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'status' => 'nullable|string',
        ]);
        Coverage::create($validated);
        return redirect()->route('admin.academic.coverage.index')->with('success', 'Coverage created successfully.');
    }

    public function show(Coverage $coverage)
    {
        return view('admin.academic.coverage.show', compact('coverage'));
    }

    public function edit(Coverage $coverage)
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        return view('admin.academic.coverage.edit', compact('coverage', 'classes', 'subjects'));
    }

    public function update(Request $request, Coverage $coverage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'class_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'status' => 'nullable|string',
        ]);
        $coverage->update($validated);
        return redirect()->route('admin.academic.coverage.index')->with('success', 'Coverage updated successfully.');
    }

    public function destroy(Coverage $coverage)
    {
        $coverage->delete();
        return redirect()->route('admin.academic.coverage.index')->with('success', 'Coverage deleted successfully.');
    }

    public function dashboard()
    {
        $total = Coverage::count();
        $pending = Coverage::where('status', 'pending')->count();
        $completed = Coverage::where('status', 'completed')->count();

        $classes = SchoolClass::pluck('name','id');
        $subjects = Subject::pluck('subject_name','id');

        $classStatus = [];
        foreach($classes as $id => $name){
            $classStatus[$name] = [
                'pending' => Coverage::where('class_id',$id)->where('status','pending')->count(),
                'completed' => Coverage::where('class_id',$id)->where('status','completed')->count(),
            ];
        }

        $subjectStatus = [];
        foreach($subjects as $id => $name){
            $subjectStatus[$name] = [
                'pending' => Coverage::where('subject_id',$id)->where('status','pending')->count(),
                'completed' => Coverage::where('subject_id',$id)->where('status','completed')->count(),
            ];
        }

        return view('admin.academic.coverage.dashboard', compact('total','pending','completed','classStatus','subjectStatus'));
    }
}

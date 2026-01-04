<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Excel;
use PDF;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User; 
use App\Exports\AssignmentExport;
use App\Imports\AssignmentImport;
class AssignmentController extends Controller
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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    if ($request->ajax()) {
        $admin = auth()->user();
        $schoolId = $admin->school_id;

        $data = Assignment::where('school_id', $schoolId)
            ->with(['schoolClass', 'section', 'subject', 'teacher']) // relationships agar define hain
            ->latest();

        return DataTables::of($data)
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
            ->addColumn('status', function($row){
                $color = match($row->status) {
                    'pending' => 'warning',
                    'submitted' => 'info',
                    'checked' => 'primary',
                    'completed' => 'success',
                    default => 'secondary'
                };
                return '<span class="badge bg-'.$color.' text-white">'.ucfirst($row->status).'</span>';
            })
            ->addColumn('created_at', function($row){
                return $row->created_at ? $row->created_at->format('d-m-Y') : '-';
            })
            ->editColumn('file', function ($row) {
                if ($row->file) {
                    return '<a href="'.asset('storage/assignments/'.$row->file).'" target="_blank" class="btn btn-sm btn-primary">View</a>';
                }
                return '<span class="text-muted">No File</span>';
            })
           ->addColumn('action', function ($row) {
                $buttons = '<div class="d-flex justify-content-center">';
                $buttons .= '<a href="' . route('admin.academic.assignments.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                $buttons .= '<a href="' . route('admin.academic.assignments.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-assignment-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['status','file','action'])
            ->make(true);
    }

    return view('admin.academic.assignments.index');
}

    /**
     * Show the form for creating a new resource.
     */
  
    public function create()
    {
        $classes = \App\Models\SchoolClass::all(); // all classes
        $sections = \App\Models\Section::all();    // all sections
        $subjects = \App\Models\Subject::all();    // all subjects
        $teachers = \App\Models\User::where('role_id', 3)->get(); // only teachers

        return view('admin.academic.assignments.create', compact('classes', 'sections', 'subjects', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);
        $assignment = Assignment::create($validated);
        return redirect()->route('admin.academic.assignments.index')->with('success', 'Assignment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        return view('admin.academic.assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        return view('admin.academic.assignments.edit', compact('assignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Academic\Assignments\Assignment $assignment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);
        $assignment->update($validated);
        return redirect()->route('admin.academic.assignments.index')->with('success', 'Assignment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Academic\Assignments\Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.academic.assignments.index')->with('success', 'Assignment deleted successfully.');
    }

    /**
     * Show dashboard for assignments.
     */
 public function dashboard()
{
    // Stats
    $total = Assignment::count();
    $pending = Assignment::where('status', 'pending')->count();
    $completed = Assignment::where('status', 'completed')->count();
    $delayed = Assignment::where('status', 'submitted')->where('due_date','<',now())->count();

    // Monthly data (last 6 months)
    $months = [];
    $assignedPerMonth = [];
    $completedPerMonth = [];
    $delayedPerMonth = [];

    for($i=5; $i>=0; $i--){
        $month = now()->subMonths($i)->format('M Y');
        $months[] = $month;

        $assignedPerMonth[] = Assignment::whereMonth('assigned_date', now()->subMonths($i)->month)->count();
        $completedPerMonth[] = Assignment::whereMonth('due_date', now()->subMonths($i)->month)
                                    ->where('status','completed')->count();
        $delayedPerMonth[] = Assignment::whereMonth('due_date', now()->subMonths($i)->month)
                                    ->where('status','pending')->count();
    }

    // Subject-wise
  $subjects = \App\Models\Subject::select('id', 'subject_name')->get();

    $subjectCount = $subjects->mapWithKeys(function($subject){
        return [$subject->subject_name => Assignment::where('subject_id', $subject->id)->count()];
    });
    // Agar aap chart ke labels aur data chahte ho:
    $labels = $subjectCount->keys();       // Collection of subject names
    $data   = $subjectCount->values();     // Collection of counts

    // Agar JavaScript me pass karna hai, to JSON me convert karo:
    $labelsJson = $labels->toJson();
    $dataJson   = $data->toJson();
        // Class-wise status
    $classes = \App\Models\SchoolClass::pluck('name','id');
    $classStatus = [];
    foreach($classes as $id => $name){
        $classStatus[$name] = [
            'pending' => Assignment::where('class_id',$id)->where('status','pending')->count(),
            'completed' => Assignment::where('class_id',$id)->where('status','completed')->count(),
            'delayed' => Assignment::where('class_id',$id)->where('status','pending')->where('due_date','<',now())->count(),
        ];
    }

    // Daily submissions (last 30 days)
    $dailyLabels = [];
    $dailyData = [];
    for($i=29; $i>=0; $i--){
        $date = now()->subDays($i)->format('d M');
        $dailyLabels[] = $date;
        $dailyData[] = Assignment::whereDate('assigned_date', now()->subDays($i)->toDateString())->count();
    }

    return view('admin.academic.assignments.dashboard', compact(
        'total','pending','completed','delayed',
        'months','assignedPerMonth','completedPerMonth','delayedPerMonth',
        'subjects','subjectCount','classes','classStatus','dailyLabels','dailyData'
    ));
}


}

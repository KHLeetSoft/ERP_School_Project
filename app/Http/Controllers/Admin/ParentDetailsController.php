<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentDetail;
use App\Models\StudentDetail;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class ParentDetailsController extends Controller
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
            $query = ParentDetail::with(['user','students.user'])->latest();

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('parent_name', fn($row) => e($row->primary_contact_name ?? $row->user->name ?? '-'))
                ->addColumn('phone', fn($row) => e($row->phone_primary ?? '-'))
                ->addColumn('students', function ($row) {
                    return e($row->students->pluck('first_name')->map(fn($n) => trim($n))->implode(', '));
                })
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.parents.details.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.parents.details.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-parent-detail" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $students = StudentDetail::all();
        return view('admin.parents.details.index', compact('students'));
    }

    public function create()
    {
        $students = StudentDetail::all();
        return view('admin.parents.details.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone_primary' => 'nullable|string',
            'email_primary' => 'nullable|email',
            'student_ids' => 'array',
            'student_ids.*' => 'exists:student_details,id',
        ]);

        $data = $request->except('student_ids');
        $data['school_id'] = auth()->user()->school_id ?? null;
        $parent = ParentDetail::create($data);

        if ($request->filled('student_ids')) {
            $parent->students()->sync(array_fill_keys($request->student_ids, ['relation' => 'guardian']));
        }

        return redirect()->route('admin.parents.details.index')->with('success', 'Parent details created.');
    }

    public function show($id)
    {
        $record = ParentDetail::with(['user','students.user'])->findOrFail($id);
        return view('admin.parents.details.show', compact('record'));
    }

    public function edit($id)
    {
        $record = ParentDetail::findOrFail($id);
        $students = StudentDetail::all();
        $selected = $record->students()->pluck('student_id')->toArray();
        return view('admin.parents.details.edit', compact('record','students','selected'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone_primary' => 'nullable|string',
            'email_primary' => 'nullable|email',
            'student_ids' => 'array',
            'student_ids.*' => 'exists:student_details,id',
        ]);

        $record = ParentDetail::findOrFail($id);
        $record->update($request->except('student_ids'));

        $syncPayload = [];
        foreach ($request->student_ids ?? [] as $sid) { $syncPayload[$sid] = ['relation' => 'guardian']; }
        $record->students()->sync($syncPayload);

        return redirect()->route('admin.parents.details.index')->with('success', 'Parent details updated.');
    }

    public function destroy($id)
    {
        ParentDetail::findOrFail($id)->delete();
        return response()->json(['message' => 'Parent details deleted.']);
    }
}



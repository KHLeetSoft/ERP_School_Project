<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Substitution;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class SubstitutionController extends Controller
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
    public function index()
    {
       
       if (request()->ajax())
     {
            $schoolId = Auth::user()->school_id;

            $substitutions = Substitution::where('school_id', $schoolId);

            return datatables()->of($substitutions)
                ->addIndexColumn()
               ->addColumn('teacher_name', function ($row) {
                    return $row->teacher ? $row->teacher->name : '-';
                })
                ->addColumn('substitute_name', function ($row) {
                    return $row->substitute ? $row->substitute->name : '-';
                })
                ->addColumn('date', function ($row) {
                    return $row->date ? \Carbon\Carbon::parse($row->date)->format('d-m-Y') : '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '-';
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d-m-Y H:i') : '-';
                })
              ->addColumn('actions', function ($row) {
                $buttons = '<div class="d-flex justify-content-center align-items-center gap-2">';
                // Bulk select checkbox (styled)
                $buttons .= '<div class="form-check form-check-inline m-0"><input type="checkbox" class="form-check-input bulk-select" value="' . $row->id . '" /></div>';
                // Status switch
                $checked = $row->status == 1 ? 'checked' : '';
                $buttons .= '<div class="form-check form-switch m-0"><input type="checkbox" class="form-check-input toggle-status-switch" data-id="' . $row->id . '" ' . $checked . '></div>';
                // Actions dropdown
                $buttons .= '<a href="' . route('admin.academic.substitution.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                $buttons .= '<a href="' . route('admin.academic.substitution.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-substitution-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                return $buttons;
      })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.academic.substitution.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role_id', 3)->get(['id', 'name']);

    return view('admin.academic.substitution.create', compact('teachers'));
   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer',
            'substitute_id' => 'required|integer',
            'date' => 'required|date',
        ]);
        $validated['school_id'] = Auth::user()->school_id;
        Substitution::create($validated);
        return redirect()->route('admin.academic.substitution.index')->with('success', 'Substitution created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    $schoolId = Auth::user()->school_id;
    $substitution = Substitution::where('school_id', $schoolId)->findOrFail($id);
    return view('admin.academic.substitution.show', compact('substitution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $schoolId = Auth::user()->school_id;
    $substitution = Substitution::where('school_id', $schoolId)->findOrFail($id);
    return view('admin.academic.substitution.edit', compact('substitution'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer',
            'substitute_id' => 'required|integer',
            'date' => 'required|date',
        ]);
        $schoolId = Auth::user()->school_id;
        $substitution = Substitution::where('school_id', $schoolId)->findOrFail($id);
        $substitution->update($validated);
        return redirect()->route('admin.academic.substitution.index')->with('success', 'Substitution updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schoolId = Auth::user()->school_id;
        $substitution = Substitution::where('school_id', $schoolId)->findOrFail($id);
        $substitution->delete();
        return redirect()->route('admin.academic.substitution.index')->with('success', 'Substitution deleted successfully.');
    
    }
     /**
     * Bulk update status for substitutions.
     */
    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');
        if (!is_array($ids) || $status === null) {
            return response()->json(['message' => 'Invalid request.'], 400);
        }
        $schoolId = Auth::user()->school_id;
        $updated = Substitution::whereIn('id', $ids)->where('school_id', $schoolId)->update(['status' => $status]);
        return response()->json(['message' => "$updated substitutions updated."]);
    }

    /**
     * Toggle status for a single substitution.
     */
    public function toggleStatus($id)
    {
        $schoolId = Auth::user()->school_id;
        $substitution = Substitution::where('id', $id)->where('school_id', $schoolId)->firstOrFail();
        $substitution->status = $substitution->status == 1 ? 0 : 1;
        $substitution->save();
        return response()->json(['message' => 'Status updated.', 'status' => $substitution->status]);
    }


}

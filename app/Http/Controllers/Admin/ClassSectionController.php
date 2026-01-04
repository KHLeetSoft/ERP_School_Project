<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\SchoolClass;
use App\Models\Section;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class ClassSectionController extends Controller
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
     * Display a listing of class sections
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $adminUser = auth()->user();

            if (!$adminUser || !$adminUser->id || $adminUser->role_id != 2) {
                return DataTables::of(collect([]))->make(true);
            }
            
            $query = ClassSection::with(['class', 'section'])
                    ->where('school_id', $adminUser->school_id)
                    ->latest();
    
            return DataTables::of($query)
                ->addIndexColumn()
               ->addColumn('class', fn($row) => e($row->class->name ?? '-'))
              ->addColumn('section', fn($row) => e($row->section->name ?? '-'))
                ->addColumn('status', function ($row) {
                    return $row->status == 'Active' 
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.students.class_sections.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-librarian-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </a>';
                    
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }
    
        $classes = SchoolClass::where('school_id', auth()->user()->school_id)->get();
        $sections = Section::where('school_id', auth()->user()->school_id)->get();
        return view('admin.students.class_sections.index', compact('classes', 'sections'));
    }
    
    /**
     * Store a newly created class section
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Check if the combination already exists
        $exists = ClassSection::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('school_id', auth()->user()->school_id)
            ->exists();
            
        if ($exists) {
            return response()->json([
                'success' => false, 
                'message' => 'This class and section combination already exists.'
            ], 422);
        }

        $data = $request->only('class_id', 'section_id', 'status');
        $data['school_id'] = auth()->user()->school_id;
        $data['created_by'] = auth()->id();
        
        $classSection = ClassSection::create($data);
        
        return response()->json([
            'success' => true, 
            'message' => 'Class section created successfully',
            'data' => $classSection->load(['class', 'section'])
        ]);
    }

    /**
     * Get class section for editing
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $classSection = ClassSection::with(['class', 'section'])
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);
            
        return response()->json([
            'success' => true,
            'data' => $classSection
        ]);
    }

    /**
     * Update the specified class section
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $classSection = ClassSection::where('school_id', auth()->user()->school_id)
            ->findOrFail($id);
            
        // Check if the combination already exists (excluding current record)
        $exists = ClassSection::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', '!=', $id)
            ->exists();
            
        if ($exists) {
            return response()->json([
                'success' => false, 
                'message' => 'This class and section combination already exists.'
            ], 422);
        }

        $data = $request->only('class_id', 'section_id', 'status');
        $data['updated_by'] = auth()->id();
        
        $classSection->update($data);
        
        return response()->json([
            'success' => true, 
            'message' => 'Class section updated successfully',
            'data' => $classSection->fresh()->load(['class', 'section'])
        ]);
    }

    /**
     * Remove the specified class section
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $classSection = ClassSection::where('school_id', auth()->user()->school_id)
            ->findOrFail($id);
            
        // Check if the class section is being used elsewhere before deleting
        // Add any necessary checks here
        
        $classSection->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Class section deleted successfully'
        ]);
    }
    
    /**
     * Get sections by class ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSectionsByClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:school_classes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        $sections = ClassSection::with('section')
            ->where('class_id', $request->class_id)
            ->where('school_id', auth()->user()->school_id)
            ->where('status', 'Active')
            ->get()
            ->pluck('section');
            
        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }
}

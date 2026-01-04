<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use App\Models\StudentDocument;
use App\Models\StudentDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class StudentDocumentController extends Controller
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
            $query = StudentDocument::with('student.user')->latest();

            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->filled('document_type')) {
                $query->where('document_type', $request->document_type);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    $s = $row->student;
                    $name = trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? ''));
                    return e($name ?: ($s->user->name ?? '-'));
                })
                ->addColumn('file', function ($row) {
                    if (!$row->file_path) return '-';
                    $url = Storage::url($row->file_path);
                    return '<a href="'.$url.'" target="_blank">View</a>';
                })
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.students.documents.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.documents.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-document" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['file', 'actions'])
                ->make(true);
        }

        $students = StudentDetail::all();
        return view('admin.students.documents.index', compact('students'));
    }

    public function create()
    {
        $students = StudentDetail::all();
        return view('admin.students.documents.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'document_type' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:20480',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only(['student_id','document_type','title','issued_date','expiry_date','status','notes']);
        $data['school_id'] = auth()->user()->school_id ?? null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Get student details to determine folder path
            $studentDetail = StudentDetail::find($request->student_id);
            if ($studentDetail) {
                $fileManager = new FileManagerService();
                $path = $fileManager->uploadToStudentFolder(
                    $studentDetail->school_id, 
                    $studentDetail->user_id, 
                    'documents', 
                    $file
                );
            } else {
                // Fallback to old path if student details not found
                $path = $file->store('uploads/student-documents', 'public');
            }
            
            $data['file_path'] = $path;
            $data['mime_type'] = $file->getClientMimeType();
            $data['original_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $doc = StudentDocument::create($data);
        return redirect()->route('admin.students.documents.index')->with('success', 'Document saved.');
    }

    public function show($id)
    {
        $document = StudentDocument::with('student.user')->findOrFail($id);
        return view('admin.students.documents.show', compact('document'));
    }

    public function edit($id)
    {
        $document = StudentDocument::findOrFail($id);
        $students = StudentDetail::all();
        return view('admin.students.documents.edit', compact('document', 'students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'document_type' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:20480',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $document = StudentDocument::findOrFail($id);
        $data = $request->only(['student_id','document_type','title','issued_date','expiry_date','status','notes']);

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            
            $file = $request->file('file');
            
            // Get student details to determine folder path
            $studentDetail = StudentDetail::find($request->student_id);
            if ($studentDetail) {
                $fileManager = new FileManagerService();
                $path = $fileManager->uploadToStudentFolder(
                    $studentDetail->school_id, 
                    $studentDetail->user_id, 
                    'documents', 
                    $file
                );
            } else {
                // Fallback to old path if student details not found
                $path = $file->store('uploads/student-documents', 'public');
            }
            
            $data['file_path'] = $path;
            $data['mime_type'] = $file->getClientMimeType();
            $data['original_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $document->update($data);
        return redirect()->route('admin.students.documents.index')->with('success', 'Document updated.');
    }

    public function destroy($id)
    {
        $document = StudentDocument::findOrFail($id);
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
        return response()->json(['message' => 'Document deleted.']);
    }
}



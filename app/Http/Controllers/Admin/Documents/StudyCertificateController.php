<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentStudyCertificate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentStudyCertificateExport;
use App\Imports\DocumentStudyCertificateImport;

class StudyCertificateController extends Controller
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
            $schoolId = auth()->user()->school_id ?? null;
            $query = DocumentStudyCertificate::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date_of_birth', fn($r)=>optional($r->date_of_birth)->format('Y-m-d'))
                ->editColumn('issue_date', fn($r)=>optional($r->issue_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.documents.study-certificate.show', $r->id);
                    $edit = route('admin.documents.study-certificate.edit', $r->id);
                    $destroy = route('admin.documents.study-certificate.destroy', $r->id);
                    $print = route('admin.documents.study-certificate.print', $r->id);
                    $download = route('admin.documents.study-certificate.download', $r->id);

                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-sc-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $schoolId = auth()->user()->school_id ?? null;
        $certs = DocumentStudyCertificate::where('school_id', $schoolId)->latest()->paginate(15);
        return view('admin.documents.study_certificate.index', compact('certs'));
    }

    public function create()
    {
        return view('admin.documents.study_certificate.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'nullable|integer|exists:student_details,id',
            'student_name' => 'required|string|max:255',
            'admission_no' => 'nullable|string|max:100',
            'roll_no' => 'nullable|string|max:50',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:30',
            'sc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $data['school_id'] = auth()->user()->school_id ?? null;
        DocumentStudyCertificate::create($data);
        return redirect()->route('admin.documents.study-certificate.index')->with('success', 'Study Certificate created.');
    }

    public function show(DocumentStudyCertificate $study_certificate)
    {
        return view('admin.documents.study_certificate.show', ['sc' => $study_certificate]);
    }

    public function edit(DocumentStudyCertificate $study_certificate)
    {
        return view('admin.documents.study_certificate.edit', ['sc' => $study_certificate]);
    }

    public function update(Request $request, DocumentStudyCertificate $study_certificate)
    {
        $data = $request->validate([
            'student_id' => 'nullable|integer|exists:student_details,id',
            'student_name' => 'required|string|max:255',
            'admission_no' => 'nullable|string|max:100',
            'roll_no' => 'nullable|string|max:50',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:30',
            'sc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $study_certificate->update($data);
        return redirect()->route('admin.documents.study-certificate.index')->with('success', 'Study Certificate updated.');
    }

    public function destroy(DocumentStudyCertificate $study_certificate)
    {
        $study_certificate->delete();
        return back()->with('success', 'Study Certificate deleted.');
    }

    public function print(DocumentStudyCertificate $study_certificate)
    {
        return view('admin.documents.study_certificate.print', ['sc' => $study_certificate]);
    }

    public function download(DocumentStudyCertificate $study_certificate)
    {
        $fileName = 'study_certificate_' . $study_certificate->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($study_certificate) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'student_id','student_name','admission_no','roll_no','class_name','section_name','date_of_birth','father_name','mother_name','academic_year','sc_number','issue_date','remarks','status'
            ]);
            fputcsv($handle, [
                $study_certificate->student_id,
                $study_certificate->student_name,
                $study_certificate->admission_no,
                $study_certificate->roll_no,
                $study_certificate->class_name,
                $study_certificate->section_name,
                optional($study_certificate->date_of_birth)->format('Y-m-d'),
                $study_certificate->father_name,
                $study_certificate->mother_name,
                $study_certificate->academic_year,
                $study_certificate->sc_number,
                optional($study_certificate->issue_date)->format('Y-m-d'),
                $study_certificate->remarks,
                $study_certificate->status,
            ]);
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new DocumentStudyCertificateExport($schoolId), 'study_certificates.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new DocumentStudyCertificateImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = DocumentStudyCertificate::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recent = DocumentStudyCertificate::where('school_id', $schoolId)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.documents.study_certificate.dashboard', compact('statusCounts','recent'));
    }
}



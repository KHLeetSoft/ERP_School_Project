<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentConductCertificate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentConductCertificateExport;
use App\Imports\DocumentConductCertificateImport;

class ConductCertificateController extends Controller
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
            $query = DocumentConductCertificate::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date_of_birth', fn($r)=>optional($r->date_of_birth)->format('Y-m-d'))
                ->editColumn('issue_date', fn($r)=>optional($r->issue_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.documents.conduct-certificate.show', $r->id);
                    $edit = route('admin.documents.conduct-certificate.edit', $r->id);
                    $destroy = route('admin.documents.conduct-certificate.destroy', $r->id);
                    $print = route('admin.documents.conduct-certificate.print', $r->id);
                    $download = route('admin.documents.conduct-certificate.download', $r->id);

                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-cc-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $schoolId = auth()->user()->school_id ?? null;
        $certs = DocumentConductCertificate::where('school_id', $schoolId)->latest()->paginate(15);
        return view('admin.documents.conduct_certificate.index', compact('certs'));
    }

    public function create()
    {
        return view('admin.documents.conduct_certificate.create');
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
            'conduct' => 'nullable|string|max:255',
            'cc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $data['school_id'] = auth()->user()->school_id ?? null;
        DocumentConductCertificate::create($data);
        return redirect()->route('admin.documents.conduct-certificate.index')->with('success', 'Conduct Certificate created.');
    }

    public function show(DocumentConductCertificate $conduct_certificate)
    {
        return view('admin.documents.conduct_certificate.show', ['cc' => $conduct_certificate]);
    }

    public function edit(DocumentConductCertificate $conduct_certificate)
    {
        return view('admin.documents.conduct_certificate.edit', ['cc' => $conduct_certificate]);
    }

    public function update(Request $request, DocumentConductCertificate $conduct_certificate)
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
            'conduct' => 'nullable|string|max:255',
            'cc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $conduct_certificate->update($data);
        return redirect()->route('admin.documents.conduct-certificate.index')->with('success', 'Conduct Certificate updated.');
    }

    public function destroy(DocumentConductCertificate $conduct_certificate)
    {
        $conduct_certificate->delete();
        return back()->with('success', 'Conduct Certificate deleted.');
    }

    public function print(DocumentConductCertificate $conduct_certificate)
    {
        return view('admin.documents.conduct_certificate.print', ['cc' => $conduct_certificate]);
    }

    public function download(DocumentConductCertificate $conduct_certificate)
    {
        $fileName = 'conduct_certificate_' . $conduct_certificate->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($conduct_certificate) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'student_id','student_name','admission_no','roll_no','class_name','section_name','date_of_birth','father_name','mother_name','conduct','cc_number','issue_date','remarks','status'
            ]);
            fputcsv($handle, [
                $conduct_certificate->student_id,
                $conduct_certificate->student_name,
                $conduct_certificate->admission_no,
                $conduct_certificate->roll_no,
                $conduct_certificate->class_name,
                $conduct_certificate->section_name,
                optional($conduct_certificate->date_of_birth)->format('Y-m-d'),
                $conduct_certificate->father_name,
                $conduct_certificate->mother_name,
                $conduct_certificate->conduct,
                $conduct_certificate->cc_number,
                optional($conduct_certificate->issue_date)->format('Y-m-d'),
                $conduct_certificate->remarks,
                $conduct_certificate->status,
            ]);
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new DocumentConductCertificateExport($schoolId), 'conduct_certificates.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new DocumentConductCertificateImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = DocumentConductCertificate::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recent = DocumentConductCertificate::where('school_id', $schoolId)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.documents.conduct_certificate.dashboard', compact('statusCounts','recent'));
    }
}



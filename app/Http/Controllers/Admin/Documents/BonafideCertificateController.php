<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentBonafideCertificate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentBonafideCertificateExport;
use App\Imports\DocumentBonafideCertificateImport;

class BonafideCertificateController extends Controller
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
            $query = DocumentBonafideCertificate::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date_of_birth', fn($r)=>optional($r->date_of_birth)->format('Y-m-d'))
                ->editColumn('issue_date', fn($r)=>optional($r->issue_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.documents.bonafide-certificate.show', $r->id);
                    $edit = route('admin.documents.bonafide-certificate.edit', $r->id);
                    $destroy = route('admin.documents.bonafide-certificate.destroy', $r->id);
                    $print = route('admin.documents.bonafide-certificate.print', $r->id);
                    $download = route('admin.documents.bonafide-certificate.download', $r->id);

                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-bc-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $schoolId = auth()->user()->school_id ?? null;
        $certs = DocumentBonafideCertificate::where('school_id', $schoolId)->latest()->paginate(15);
        return view('admin.documents.bonafide_certificate.index', compact('certs'));
    }

    public function create()
    {
        return view('admin.documents.bonafide_certificate.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'nullable|integer|exists:student_details,id',
            'student_name' => 'required|string|max:255',
            'admission_no' => 'nullable|string|max:100',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'bc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $data['school_id'] = auth()->user()->school_id ?? null;
        DocumentBonafideCertificate::create($data);
        return redirect()->route('admin.documents.bonafide-certificate.index')->with('success', 'Bonafide Certificate created.');
    }

    public function show(DocumentBonafideCertificate $bonafide_certificate)
    {
        return view('admin.documents.bonafide_certificate.show', ['bc' => $bonafide_certificate]);
    }

    public function edit(DocumentBonafideCertificate $bonafide_certificate)
    {
        return view('admin.documents.bonafide_certificate.edit', ['bc' => $bonafide_certificate]);
    }

    public function update(Request $request, DocumentBonafideCertificate $bonafide_certificate)
    {
        $data = $request->validate([
            'student_id' => 'nullable|integer|exists:student_details,id',
            'student_name' => 'required|string|max:255',
            'admission_no' => 'nullable|string|max:100',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'bc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $bonafide_certificate->update($data);
        return redirect()->route('admin.documents.bonafide-certificate.index')->with('success', 'Bonafide Certificate updated.');
    }

    public function destroy(DocumentBonafideCertificate $bonafide_certificate)
    {
        $bonafide_certificate->delete();
        return back()->with('success', 'Bonafide Certificate deleted.');
    }

    public function print(DocumentBonafideCertificate $bonafide_certificate)
    {
        return view('admin.documents.bonafide_certificate.print', ['bc' => $bonafide_certificate]);
    }

    public function download(DocumentBonafideCertificate $bonafide_certificate)
    {
        $fileName = 'bonafide_certificate_' . $bonafide_certificate->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($bonafide_certificate) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'student_id','student_name','admission_no','class_name','section_name','date_of_birth',
                'father_name','mother_name','purpose','bc_number','issue_date','remarks','status'
            ]);
            fputcsv($handle, [
                $bonafide_certificate->student_id,
                $bonafide_certificate->student_name,
                $bonafide_certificate->admission_no,
                $bonafide_certificate->class_name,
                $bonafide_certificate->section_name,
                optional($bonafide_certificate->date_of_birth)->format('Y-m-d'),
                $bonafide_certificate->father_name,
                $bonafide_certificate->mother_name,
                $bonafide_certificate->purpose,
                $bonafide_certificate->bc_number,
                optional($bonafide_certificate->issue_date)->format('Y-m-d'),
                $bonafide_certificate->remarks,
                $bonafide_certificate->status,
            ]);
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new DocumentBonafideCertificateExport($schoolId), 'bonafide_certificates.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new DocumentBonafideCertificateImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;

        $statusCounts = DocumentBonafideCertificate::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recent = DocumentBonafideCertificate::where('school_id', $schoolId)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.documents.bonafide_certificate.dashboard', compact('statusCounts','recent'));
    }

}





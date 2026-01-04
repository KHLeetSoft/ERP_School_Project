<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentExperienceCertificate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentExperienceCertificateExport;
use App\Imports\DocumentExperienceCertificateImport;

class ExperienceCertificateController extends Controller
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
            $query = DocumentExperienceCertificate::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('joining_date', fn($r)=>optional($r->joining_date)->format('Y-m-d'))
                ->editColumn('relieving_date', fn($r)=>optional($r->relieving_date)->format('Y-m-d'))
                ->editColumn('issue_date', fn($r)=>optional($r->issue_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.documents.experience-certificate.show', $r->id);
                    $edit = route('admin.documents.experience-certificate.edit', $r->id);
                    $destroy = route('admin.documents.experience-certificate.destroy', $r->id);
                    $print = route('admin.documents.experience-certificate.print', $r->id);
                    $download = route('admin.documents.experience-certificate.download', $r->id);

                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-ec-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $schoolId = auth()->user()->school_id ?? null;
        $certs = DocumentExperienceCertificate::where('school_id', $schoolId)->latest()->paginate(15);
        return view('admin.documents.experience_certificate.index', compact('certs'));
    }

    public function create()
    {
        return view('admin.documents.experience_certificate.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|integer',
            'employee_name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:150',
            'department' => 'nullable|string|max:150',
            'joining_date' => 'nullable|date',
            'relieving_date' => 'nullable|date|after_or_equal:joining_date',
            'total_experience' => 'nullable|string|max:100',
            'ec_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $data['school_id'] = auth()->user()->school_id ?? null;
        DocumentExperienceCertificate::create($data);
        return redirect()->route('admin.documents.experience-certificate.index')->with('success', 'Experience Certificate created.');
    }

    public function show(DocumentExperienceCertificate $experience_certificate)
    {
        return view('admin.documents.experience_certificate.show', ['ec' => $experience_certificate]);
    }

    public function edit(DocumentExperienceCertificate $experience_certificate)
    {
        return view('admin.documents.experience_certificate.edit', ['ec' => $experience_certificate]);
    }

    public function update(Request $request, DocumentExperienceCertificate $experience_certificate)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|integer',
            'employee_name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:150',
            'department' => 'nullable|string|max:150',
            'joining_date' => 'nullable|date',
            'relieving_date' => 'nullable|date|after_or_equal:joining_date',
            'total_experience' => 'nullable|string|max:100',
            'ec_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $experience_certificate->update($data);
        return redirect()->route('admin.documents.experience-certificate.index')->with('success', 'Experience Certificate updated.');
    }

    public function destroy(DocumentExperienceCertificate $experience_certificate)
    {
        $experience_certificate->delete();
        return back()->with('success', 'Experience Certificate deleted.');
    }

    public function print(DocumentExperienceCertificate $experience_certificate)
    {
        return view('admin.documents.experience_certificate.print', ['ec' => $experience_certificate]);
    }

    public function download(DocumentExperienceCertificate $experience_certificate)
    {
        $fileName = 'experience_certificate_' . $experience_certificate->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($experience_certificate) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'employee_id','employee_name','designation','department','joining_date','relieving_date','total_experience',
                'ec_number','issue_date','remarks','status'
            ]);
            fputcsv($handle, [
                $experience_certificate->employee_id,
                $experience_certificate->employee_name,
                $experience_certificate->designation,
                $experience_certificate->department,
                optional($experience_certificate->joining_date)->format('Y-m-d'),
                optional($experience_certificate->relieving_date)->format('Y-m-d'),
                $experience_certificate->total_experience,
                $experience_certificate->ec_number,
                optional($experience_certificate->issue_date)->format('Y-m-d'),
                $experience_certificate->remarks,
                $experience_certificate->status,
            ]);
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new DocumentExperienceCertificateExport($schoolId), 'experience_certificates.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new DocumentExperienceCertificateImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = DocumentExperienceCertificate::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recent = DocumentExperienceCertificate::where('school_id', $schoolId)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.documents.experience_certificate.dashboard', compact('statusCounts','recent'));
    }
}



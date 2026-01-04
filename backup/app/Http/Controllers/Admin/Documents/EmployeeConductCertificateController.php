<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentEmployeeConductCertificate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentEmployeeConductCertificateExport;
use App\Imports\DocumentEmployeeConductCertificateImport;

class EmployeeConductCertificateController extends Controller
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
            $query = DocumentEmployeeConductCertificate::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('issue_date', fn($r)=>optional($r->issue_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.documents.employee-conduct-certificate.show', $r->id);
                    $edit = route('admin.documents.employee-conduct-certificate.edit', $r->id);
                    $destroy = route('admin.documents.employee-conduct-certificate.destroy', $r->id);
                    $print = route('admin.documents.employee-conduct-certificate.print', $r->id);
                    $download = route('admin.documents.employee-conduct-certificate.download', $r->id);

                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-ecc-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $schoolId = auth()->user()->school_id ?? null;
        $certs = DocumentEmployeeConductCertificate::where('school_id', $schoolId)->latest()->paginate(15);
        return view('admin.documents.employee_conduct_certificate.index', compact('certs'));
    }

    public function create()
    {
        return view('admin.documents.employee_conduct_certificate.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|integer',
            'employee_name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:150',
            'department' => 'nullable|string|max:150',
            'conduct' => 'nullable|string|max:255',
            'ecc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $data['school_id'] = auth()->user()->school_id ?? null;
        DocumentEmployeeConductCertificate::create($data);
        return redirect()->route('admin.documents.employee-conduct-certificate.index')->with('success', 'Employee Conduct Certificate created.');
    }

    public function show(DocumentEmployeeConductCertificate $employee_conduct_certificate)
    {
        return view('admin.documents.employee_conduct_certificate.show', ['ecc' => $employee_conduct_certificate]);
    }

    public function edit(DocumentEmployeeConductCertificate $employee_conduct_certificate)
    {
        return view('admin.documents.employee_conduct_certificate.edit', ['ecc' => $employee_conduct_certificate]);
    }

    public function update(Request $request, DocumentEmployeeConductCertificate $employee_conduct_certificate)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|integer',
            'employee_name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:150',
            'department' => 'nullable|string|max:150',
            'conduct' => 'nullable|string|max:255',
            'ecc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $employee_conduct_certificate->update($data);
        return redirect()->route('admin.documents.employee-conduct-certificate.index')->with('success', 'Employee Conduct Certificate updated.');
    }

    public function destroy(DocumentEmployeeConductCertificate $employee_conduct_certificate)
    {
        $employee_conduct_certificate->delete();
        return back()->with('success', 'Employee Conduct Certificate deleted.');
    }

    public function print(DocumentEmployeeConductCertificate $employee_conduct_certificate)
    {
        return view('admin.documents.employee_conduct_certificate.print', ['ecc' => $employee_conduct_certificate]);
    }

    public function download(DocumentEmployeeConductCertificate $employee_conduct_certificate)
    {
        $fileName = 'employee_conduct_certificate_' . $employee_conduct_certificate->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($employee_conduct_certificate) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'employee_id','employee_name','designation','department','conduct','ecc_number','issue_date','remarks','status'
            ]);
            fputcsv($handle, [
                $employee_conduct_certificate->employee_id,
                $employee_conduct_certificate->employee_name,
                $employee_conduct_certificate->designation,
                $employee_conduct_certificate->department,
                $employee_conduct_certificate->conduct,
                $employee_conduct_certificate->ecc_number,
                optional($employee_conduct_certificate->issue_date)->format('Y-m-d'),
                $employee_conduct_certificate->remarks,
                $employee_conduct_certificate->status,
            ]);
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new DocumentEmployeeConductCertificateExport($schoolId), 'employee_conduct_certificates.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new DocumentEmployeeConductCertificateImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = DocumentEmployeeConductCertificate::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recent = DocumentEmployeeConductCertificate::where('school_id', $schoolId)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.documents.employee_conduct_certificate.dashboard', compact('statusCounts','recent'));
    }
}



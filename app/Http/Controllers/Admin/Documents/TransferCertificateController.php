<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentTransferCertificate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentTransferCertificateExport;
use App\Imports\DocumentTransferCertificateImport;

class TransferCertificateController extends Controller
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
            $query = DocumentTransferCertificate::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date_of_birth', fn($r)=>optional($r->date_of_birth)->format('Y-m-d'))
                ->editColumn('admission_date', fn($r)=>optional($r->admission_date)->format('Y-m-d'))
                ->editColumn('leaving_date', fn($r)=>optional($r->leaving_date)->format('Y-m-d'))
                ->editColumn('issue_date', fn($r)=>optional($r->issue_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.documents.transfer-certificate.show', $r->id);
                    $edit = route('admin.documents.transfer-certificate.edit', $r->id);
                    $destroy = route('admin.documents.transfer-certificate.destroy', $r->id);
                    $print = route('admin.documents.transfer-certificate.print', $r->id);
                    $download = route('admin.documents.transfer-certificate.download', $r->id); // ✅ नया download
                
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-tc-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                
                ->make(true);
        }

        $schoolId = auth()->user()->school_id ?? null;
        $tcs = DocumentTransferCertificate::where('school_id', $schoolId)->latest()->paginate(15);
        return view('admin.documents.transfer_certificate.index', compact('tcs'));
    }

    public function create()
    {
        return view('admin.documents.transfer_certificate.create');
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
            'admission_date' => 'nullable|date',
            'leaving_date' => 'nullable|date|after_or_equal:admission_date',
            'reason_for_leaving' => 'nullable|string|max:500',
            'conduct' => 'nullable|string|max:255',
            'tc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $data['school_id'] = auth()->user()->school_id ?? null;
        DocumentTransferCertificate::create($data);
        return redirect()->route('admin.documents.transfer-certificate.index')->with('success', 'Transfer Certificate created.');
    }

    public function show(DocumentTransferCertificate $transfer_certificate)
    {
        return view('admin.documents.transfer_certificate.show', ['tc' => $transfer_certificate]);
    }

    public function edit(DocumentTransferCertificate $transfer_certificate)
    {
        return view('admin.documents.transfer_certificate.edit', ['tc' => $transfer_certificate]);
    }

    public function update(Request $request, DocumentTransferCertificate $transfer_certificate)
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
            'admission_date' => 'nullable|date',
            'leaving_date' => 'nullable|date|after_or_equal:admission_date',
            'reason_for_leaving' => 'nullable|string|max:500',
            'conduct' => 'nullable|string|max:255',
            'tc_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $transfer_certificate->update($data);
        return redirect()->route('admin.documents.transfer-certificate.index')->with('success', 'Transfer Certificate updated.');
    }

    public function destroy(DocumentTransferCertificate $transfer_certificate)
    {
        $transfer_certificate->delete();
        return back()->with('success', 'Transfer Certificate deleted.');
    }

    public function print(DocumentTransferCertificate $transfer_certificate)
    {
        return view('admin.documents.transfer_certificate.print', ['tc' => $transfer_certificate]);
    }

    public function download(DocumentTransferCertificate $transfer_certificate)
    {
        $fileName = 'transfer_certificate_' . $transfer_certificate->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($transfer_certificate) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'student_id','student_name','admission_no','class_name','section_name','date_of_birth',
                'father_name','mother_name','admission_date','leaving_date','reason_for_leaving','conduct',
                'tc_number','issue_date','remarks','status'
            ]);
            fputcsv($handle, [
                $transfer_certificate->student_id,
                $transfer_certificate->student_name,
                $transfer_certificate->admission_no,
                $transfer_certificate->class_name,
                $transfer_certificate->section_name,
                optional($transfer_certificate->date_of_birth)->format('Y-m-d'),
                $transfer_certificate->father_name,
                $transfer_certificate->mother_name,
                optional($transfer_certificate->admission_date)->format('Y-m-d'),
                optional($transfer_certificate->leaving_date)->format('Y-m-d'),
                $transfer_certificate->reason_for_leaving,
                $transfer_certificate->conduct,
                $transfer_certificate->tc_number,
                optional($transfer_certificate->issue_date)->format('Y-m-d'),
                $transfer_certificate->remarks,
                $transfer_certificate->status,
            ]);
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new DocumentTransferCertificateExport($schoolId), 'transfer_certificates.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new DocumentTransferCertificateImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = DocumentTransferCertificate::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recent = DocumentTransferCertificate::where('school_id', $schoolId)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.documents.transfer_certificate.dashboard', compact('statusCounts','recent'));
    }
}




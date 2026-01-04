<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentIdCard;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentIdCardExport;
use App\Imports\DocumentIdCardImport;

class IdCardController extends Controller
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
            $query = DocumentIdCard::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date_of_birth', fn($r)=>optional($r->date_of_birth)->format('Y-m-d'))
                ->editColumn('issue_date', fn($r)=>optional($r->issue_date)->format('Y-m-d'))
                ->editColumn('expiry_date', fn($r)=>optional($r->expiry_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.documents.idcard.show', $r->id);
                    $edit = route('admin.documents.idcard.edit', $r->id);
                    $destroy = route('admin.documents.idcard.destroy', $r->id);
                    $download = route('admin.documents.idcard.download', $r->id);
                    $print = route('admin.documents.idcard.print', $r->id);
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-idcard-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.documents.idcard.index');
    }

    public function create()
    {
        return view('admin.documents.idcard.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'nullable|integer|exists:students,id',
            'student_name' => 'required|string|max:255',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'roll_number' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:5',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:active,inactive',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        DocumentIdCard::create($data);
        return redirect()->route('admin.documents.idcard.index')->with('success', 'ID Card created.');
    }

    public function show(DocumentIdCard $idcard)
    {
        return view('admin.documents.idcard.show', compact('idcard'));
    }

    public function edit(DocumentIdCard $idcard)
    {
        return view('admin.documents.idcard.edit', compact('idcard'));
    }

    public function update(Request $request, DocumentIdCard $idcard)
    {
        $data = $request->validate([
            'student_id' => 'nullable|integer|exists:students,id',
            'student_name' => 'required|string|max:255',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'roll_number' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:5',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:active,inactive',
        ]);
        $idcard->update($data);
        return redirect()->route('admin.documents.idcard.index')->with('success', 'ID Card updated.');
    }

    public function destroy(DocumentIdCard $idcard)
    {
        $idcard->delete();
        return back()->with('success', 'ID Card deleted.');
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new DocumentIdCardExport($schoolId), 'idcards.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new DocumentIdCardImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $schoolId = auth()->user()->school_id ?? null;
        DocumentIdCard::where('school_id', $schoolId)->whereIn('id', $ids)->delete();
        return back()->with('success', 'Selected ID Cards deleted.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = DocumentIdCard::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $recent = DocumentIdCard::where('school_id', $schoolId)->orderByDesc('created_at')->take(10)->get();
        return view('admin.documents.idcard.dashboard', compact('statusCounts','recent'));
    }

    public function download(DocumentIdCard $idcard)
    {
        $fileName = 'idcard_' . $idcard->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        $callback = function () use ($idcard) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['student_id','student_name','class_name','section_name','roll_number','date_of_birth','blood_group','address','phone','guardian_name','issue_date','expiry_date','status']);
            fputcsv($handle, [
                $idcard->student_id,
                $idcard->student_name,
                $idcard->class_name,
                $idcard->section_name,
                $idcard->roll_number,
                optional($idcard->date_of_birth)->format('Y-m-d'),
                $idcard->blood_group,
                $idcard->address,
                $idcard->phone,
                $idcard->guardian_name,
                optional($idcard->issue_date)->format('Y-m-d'),
                optional($idcard->expiry_date)->format('Y-m-d'),
                $idcard->status,
            ]);
            fclose($handle);
        };
        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function print(DocumentIdCard $idcard)
    {
        // Optionally generate a simple QR payload (e.g., student name + roll)
        $qrPayload = json_encode([
            'student' => $idcard->student_name,
            'roll' => $idcard->roll_number,
            'class' => trim(($idcard->class_name ?? '').' '.($idcard->section_name ?? '')),
            'valid_till' => optional($idcard->expiry_date)->format('Y-m-d'),
        ]);
        return view('admin.documents.idcard.print', compact('idcard','qrPayload'));
    }
}



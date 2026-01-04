<?php

namespace App\Http\Controllers\Admin\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentMarksheet;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentMarksheetExport;
use App\Imports\DocumentMarksheetImport;

class MarksheetController extends Controller
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
            $query = DocumentMarksheet::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('issue_date', fn($r)=>optional($r->issue_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.documents.marksheet.show', $r->id);
                    $edit = route('admin.documents.marksheet.edit', $r->id);
                    $destroy = route('admin.documents.marksheet.destroy', $r->id);
                    $print = route('admin.documents.marksheet.print', $r->id);
                    $download = route('admin.documents.marksheet.download', $r->id);

                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-ms-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $schoolId = auth()->user()->school_id ?? null;
        $items = DocumentMarksheet::where('school_id', $schoolId)->latest()->paginate(15);
        return view('admin.documents.marksheet.index', compact('items'));
    }

    public function create()
    {
        return view('admin.documents.marksheet.create');
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
            'exam_name' => 'nullable|string|max:150',
            'term' => 'nullable|string|max:100',
            'academic_year' => 'nullable|string|max:30',
            'ms_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'total_marks' => 'nullable|numeric',
            'obtained_marks' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
            'grade' => 'nullable|string|max:10',
            'result_status' => 'nullable|in:pass,fail',
            'remarks' => 'nullable|string|max:500',
            'marks_json' => 'nullable|string',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $data['school_id'] = auth()->user()->school_id ?? null;
        DocumentMarksheet::create($data);
        return redirect()->route('admin.documents.marksheet.index')->with('success', 'Marksheet created.');
    }

    public function show(DocumentMarksheet $marksheet)
    {
        return view('admin.documents.marksheet.show', ['ms' => $marksheet]);
    }

    public function edit(DocumentMarksheet $marksheet)
    {
        return view('admin.documents.marksheet.edit', ['ms' => $marksheet]);
    }

    public function update(Request $request, DocumentMarksheet $marksheet)
    {
        $data = $request->validate([
            'student_id' => 'nullable|integer|exists:student_details,id',
            'student_name' => 'required|string|max:255',
            'admission_no' => 'nullable|string|max:100',
            'roll_no' => 'nullable|string|max:50',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'exam_name' => 'nullable|string|max:150',
            'term' => 'nullable|string|max:100',
            'academic_year' => 'nullable|string|max:30',
            'ms_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'total_marks' => 'nullable|numeric',
            'obtained_marks' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
            'grade' => 'nullable|string|max:10',
            'result_status' => 'nullable|in:pass,fail',
            'remarks' => 'nullable|string|max:500',
            'marks_json' => 'nullable|string',
            'status' => 'required|in:issued,cancelled,draft',
        ]);

        $marksheet->update($data);
        return redirect()->route('admin.documents.marksheet.index')->with('success', 'Marksheet updated.');
    }

    public function destroy(DocumentMarksheet $marksheet)
    {
        $marksheet->delete();
        return back()->with('success', 'Marksheet deleted.');
    }

    public function print(DocumentMarksheet $marksheet)
    {
        return view('admin.documents.marksheet.print', ['ms' => $marksheet]);
    }

    public function download(DocumentMarksheet $marksheet)
    {
        $fileName = 'marksheet_' . $marksheet->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($marksheet) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'student_id','student_name','admission_no','roll_no','class_name','section_name','exam_name','term','academic_year',
                'ms_number','issue_date','total_marks','obtained_marks','percentage','grade','result_status','remarks','status'
            ]);
            fputcsv($handle, [
                $marksheet->student_id,
                $marksheet->student_name,
                $marksheet->admission_no,
                $marksheet->roll_no,
                $marksheet->class_name,
                $marksheet->section_name,
                $marksheet->exam_name,
                $marksheet->term,
                $marksheet->academic_year,
                $marksheet->ms_number,
                optional($marksheet->issue_date)->format('Y-m-d'),
                $marksheet->total_marks,
                $marksheet->obtained_marks,
                $marksheet->percentage,
                $marksheet->grade,
                $marksheet->result_status,
                $marksheet->remarks,
                $marksheet->status,
            ]);
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new DocumentMarksheetExport($schoolId), 'marksheets.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new DocumentMarksheetImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = DocumentMarksheet::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recent = DocumentMarksheet::where('school_id', $schoolId)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.documents.marksheet.dashboard', compact('statusCounts','recent'));
    }
}



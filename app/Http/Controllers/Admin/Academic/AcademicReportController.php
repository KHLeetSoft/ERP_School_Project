<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\AcademicReport;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AcademicReportExport;
use App\Imports\AcademicReportImport;

class AcademicReportController extends Controller
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
            $query = AcademicReport::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('report_date', fn($r)=>optional($r->report_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.academic.reports.show', $r->id);
                    $edit = route('admin.academic.reports.edit', $r->id);
                    $destroy = route('admin.academic.reports.destroy', $r->id);
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-report-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.academic.reports.index');
    }

    public function create()
    {
        return view('admin.academic.reports.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_date' => 'required|date',
            'type' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published,archived',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        AcademicReport::create($data);
        return redirect()->route('admin.academic.reports.index')->with('success', 'Report created.');
    }

    public function show(AcademicReport $report)
    {
        return view('admin.academic.reports.show', compact('report'));
    }

    public function edit(AcademicReport $report)
    {
        return view('admin.academic.reports.edit', compact('report'));
    }

    public function update(Request $request, AcademicReport $report)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_date' => 'required|date',
            'type' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published,archived',
        ]);
        $report->update($data);
        return redirect()->route('admin.academic.reports.index')->with('success', 'Report updated.');
    }

    public function destroy(AcademicReport $report)
    {
        $report->delete();
        return back()->with('success', 'Report deleted.');
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new AcademicReportExport($schoolId), 'academic_reports.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new AcademicReportImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $schoolId = auth()->user()->school_id ?? null;
        AcademicReport::where('school_id', $schoolId)->whereIn('id', $ids)->delete();
        return back()->with('success', 'Selected reports deleted.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = AcademicReport::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $monthlyCounts = AcademicReport::where('school_id', $schoolId)
            ->selectRaw("DATE_FORMAT(report_date, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $recent = AcademicReport::where('school_id', $schoolId)->orderByDesc('report_date')->take(10)->get();

        return view('admin.academic.reports.dashboard', compact('statusCounts','monthlyCounts','recent'));
    }

    public function download($id)
    {
        $report = AcademicReport::findOrFail($id);

        // Example: assuming file stored in storage/app/reports/
        $filePath = storage_path("app/reports/{$report->file_name}");

        if (file_exists($filePath)) {
            return response()->download($filePath, $report->title . '.pdf');
        }

        return redirect()->back()->with('error', 'File not found!');
    }

}



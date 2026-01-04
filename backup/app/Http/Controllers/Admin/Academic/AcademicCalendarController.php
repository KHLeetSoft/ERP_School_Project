<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AcademicCalendarExport;
use App\Imports\AcademicCalendarImport;

class AcademicCalendarController extends Controller
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
            $query = AcademicCalendar::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date', fn($r)=>optional($r->date)->format('Y-m-d'))
                ->editColumn('start_time', fn($r)=>optional($r->start_time)->format('Y-m-d H:i'))
                ->editColumn('end_time', fn($r)=>optional($r->end_time)->format('Y-m-d H:i'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.academic.calendar.show', $r->id);
                    $edit = route('admin.academic.calendar.edit', $r->id);
                    $destroy = route('admin.academic.calendar.destroy', $r->id);
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-calendar-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.academic.calendar.index');
    }

    public function create()
    {
        return view('admin.academic.calendar.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        AcademicCalendar::create($data);
        return redirect()->route('admin.academic.calendar.index')->with('success', 'Calendar event created.');
    }

    public function show(AcademicCalendar $calendar)
    {
        return view('admin.academic.calendar.show', compact('calendar'));
    }

    public function edit(AcademicCalendar $calendar)
    {
        return view('admin.academic.calendar.edit', compact('calendar'));
    }

    public function update(Request $request, AcademicCalendar $calendar)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        $calendar->update($data);
        return redirect()->route('admin.academic.calendar.index')->with('success', 'Calendar event updated.');
    }

    public function destroy(AcademicCalendar $calendar)
    {
        $calendar->delete();
        return back()->with('success', 'Calendar event deleted.');
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new AcademicCalendarExport($schoolId), 'academic_calendar.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new AcademicCalendarImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $schoolId = auth()->user()->school_id ?? null;
        AcademicCalendar::where('school_id', $schoolId)->whereIn('id', $ids)->delete();
        return back()->with('success', 'Selected events deleted.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;

        $upcoming = AcademicCalendar::where('school_id', $schoolId)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(10)
            ->get();

        $statusCounts = AcademicCalendar::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $monthlyCounts = AcademicCalendar::where('school_id', $schoolId)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $completed = AcademicCalendar::where('school_id', $schoolId)->where('status', 'completed')->count();
        $upcomingCount = AcademicCalendar::where('school_id', $schoolId)->where('date', '>=', now()->toDateString())->count();

        return view('admin.academic.calendar.dashboard', compact(
            'upcoming',
            'statusCounts',
            'monthlyCounts',
            'completed',
            'upcomingCount'
        ));
    }
}



<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Ptm;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PtmController extends Controller
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
            $query = Ptm::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date', fn($r)=>optional($r->date)->format('Y-m-d'))
                ->editColumn('start_time', fn($r)=>optional($r->start_time)->format('Y-m-d H:i'))
                ->editColumn('end_time', fn($r)=>optional($r->end_time)->format('Y-m-d H:i'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.academic.ptm.show', $r->id);
                    $edit = route('admin.academic.ptm.edit', $r->id);
                    $destroy = route('admin.academic.ptm.destroy', $r->id);
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-ptm-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.academic.ptm.index');
    }

    public function create()
    {
        return view('admin.academic.ptm.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        Ptm::create($data);
        return redirect()->route('admin.academic.ptm.index')->with('success', 'PTM created.');
    }

    public function show(Ptm $ptm)
    {
        return view('admin.academic.ptm.show', compact('ptm'));
    }

    public function edit(Ptm $ptm)
    {
        return view('admin.academic.ptm.edit', compact('ptm'));
    }

    public function update(Request $request, Ptm $ptm)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        $ptm->update($data);
        return redirect()->route('admin.academic.ptm.index')->with('success', 'PTM updated.');
    }

    public function destroy(Ptm $ptm)
    {
        $ptm->delete();
        return back()->with('success', 'PTM deleted.');
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        $fileName = 'ptm_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        $callback = function () use ($schoolId) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['title','description','date','start_time','end_time','status']);
            Ptm::where('school_id', $schoolId)->orderBy('date')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->title,
                        $row->description,
                        optional($row->date)->format('Y-m-d'),
                        optional($row->start_time)->format('Y-m-d H:i:s'),
                        optional($row->end_time)->format('Y-m-d H:i:s'),
                        $row->status,
                    ]);
                }
            });
            fclose($handle);
        };
        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:csv,txt']]);
        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) return back()->withErrors(['file' => 'Unable to read file']);
        $header = fgetcsv($handle);
        $expected = ['title','description','date','start_time','end_time','status'];
        if (!$header || array_map('strtolower', $header) !== $expected) {
            fclose($handle);
            return back()->withErrors(['file' => 'Invalid CSV header. Expected: '.implode(',', $expected)]);
        }
     
        $schoolId = auth()->user()->school_id ?? null;
        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 6) continue;
            [$title,$description,$date,$start,$end,$status] = $row;
            try {
                Ptm::create([
                    'school_id' => $schoolId,
                    'title' => trim($title),
                    'description' => trim($description) ?: null,
                    'date' => $date,
                    'start_time' => $start,
                    'end_time' => $end,
                    'status' => in_array($status, ['scheduled','completed','cancelled']) ? $status : 'scheduled',
                ]);
                $imported++;
            } catch (\Throwable $e) { /* skip */ }
        }
        fclose($handle);
        return back()->with('success', "Imported {$imported} PTMs.");
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $schoolId = auth()->user()->school_id ?? null;
        Ptm::where('school_id', $schoolId)->whereIn('id', $ids)->delete();
        return back()->with('success', 'Selected PTMs deleted.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
    
        // Upcoming PTMs
        $upcoming = Ptm::where('school_id', $schoolId)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(10)
            ->get();
    
        // Status Counts for Pie/Donut
        $statusCounts = Ptm::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    
        // Monthly PTM Count
        $monthlyCounts = Ptm::where('school_id', $schoolId)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
    
        // Completed vs Upcoming
        $completed = Ptm::where('school_id', $schoolId)->where('status', 'Completed')->count();
        $upcomingCount = Ptm::where('school_id', $schoolId)->where('date', '>=', now()->toDateString())->count();
    
        return view('admin.academic.ptm.dashboard', compact(
            'upcoming',
            'statusCounts',
            'monthlyCounts',
            'completed',
            'upcomingCount'
        ));
    }
    
    
}



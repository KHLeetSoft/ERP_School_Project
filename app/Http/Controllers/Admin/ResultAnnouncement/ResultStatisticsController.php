<?php

namespace App\Http\Controllers\Admin\ResultAnnouncement;

use App\Http\Controllers\Controller;
use App\Models\ResultStatistic;
use App\Models\ResultAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ResultStatisticsController extends Controller
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
            $query = ResultStatistic::with(['creator', 'resultAnnouncement'])
                ->where('school_id', auth()->user()->school_id ?? 1)
                ->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('announcement', fn($r) => $r->resultAnnouncement->title ?? '-')
                ->addColumn('created_by', fn($r) => $r->creator->name ?? '-')
                ->addColumn('generated_at', function ($r) {
                    return $r->generated_at 
                        ? $r->generated_at->format('d M Y') 
                        : '-';
                })
                ->addColumn('actions', function ($r) {
                    $showUrl = route('admin.result-announcement.statistics.show', $r->id);
                    $editUrl = route('admin.result-announcement.statistics.edit', $r->id);
                    $deleteUrl = route('admin.result-announcement.statistics.destroy', $r->id);
                    return '<div class="btn-group" role="group">'
                        .'<a href="'.$showUrl.'" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        .'<a href="'.$editUrl.'" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        .'<button type="button" data-url="'.$deleteUrl.'" class="btn btn-sm js-delete-stat" title="Delete"><i class="bx bx-trash"></i></button>'
                        .'</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $announcements = ResultAnnouncement::where('school_id', auth()->user()->school_id ?? 1)
            ->orderByDesc('created_at')->get(['id','title']);

        return view('admin.result-announcement.statistics.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'result_announcement_id' => 'nullable|exists:result_announcements,id',
            'filters' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Placeholder metrics; extendable later with real computations
        $metrics = [
            'total_students' => 0,
            'appeared' => 0,
            'passed' => 0,
            'failed' => 0,
            'pass_percentage' => 0,
            'top_score' => 0,
            'average_score' => 0,
            'median_score' => 0,
            'lowest_score' => 0,
            'grade_distribution' => [
                'A' => 0,
                'B' => 0,
                'C' => 0,
                'D' => 0,
                'F' => 0,
            ],
        ];

        // Parse filters JSON if provided as string
        $filters = $request->filters;
        if (is_string($filters) && trim($filters) !== '') {
            try {
                $decoded = json_decode($filters, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $filters = $decoded;
                } else {
                    return back()->withErrors(['filters' => 'Filters must be valid JSON.'])->withInput();
                }
            } catch (\Throwable $e) {
                return back()->withErrors(['filters' => 'Filters must be valid JSON.'])->withInput();
            }
        }

        ResultStatistic::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'result_announcement_id' => $request->result_announcement_id,
            'title' => $request->title,
            'filters' => $filters ?? [],
            'metrics' => $metrics,
            'generated_at' => now(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.result-announcement.statistics.index')
            ->with('success', 'Statistics generated.');
    }

    public function dashboard()
    {
        $stats = ResultStatistic::where('school_id', auth()->user()->school_id ?? 1)
            ->orderByDesc('generated_at')
            ->take(20)
            ->get();

        // Simple aggregations for a quick dashboard
        $aggregate = [
            'total_students' => $stats->sum(fn($s) => (int)($s->metrics['total_students'] ?? 0)),
            'appeared' => $stats->sum(fn($s) => (int)($s->metrics['appeared'] ?? 0)),
            'passed' => $stats->sum(fn($s) => (int)($s->metrics['passed'] ?? 0)),
            'failed' => $stats->sum(fn($s) => (int)($s->metrics['failed'] ?? 0)),
        ];
        $aggregate['pass_percentage'] = $aggregate['appeared'] > 0
            ? round(($aggregate['passed'] / max(1, $aggregate['appeared'])) * 100, 2)
            : 0;

        // Aggregate grade distribution across stats
        $gradeLabels = ['A','B','C','D','F'];
        $gradeTotals = array_map(function ($grade) use ($stats) {
            return $stats->sum(function ($s) use ($grade) {
                $dist = $s->metrics['grade_distribution'] ?? [];
                return (int)($dist[$grade] ?? 0);
            });
        }, $gradeLabels);

        return view('admin.result-announcement.statistics.dashboard', compact('stats', 'aggregate', 'gradeLabels', 'gradeTotals'));
    }

    public function create()
    {
        $announcements = ResultAnnouncement::where('school_id', auth()->user()->school_id ?? 1)
            ->orderByDesc('created_at')->get(['id','title']);

        return view('admin.result-announcement.statistics.create', compact('announcements'));
    }

    public function show(ResultStatistic $statistic)
    {
        $this->authorizeView($statistic);
        return view('admin.result-announcement.statistics.show', compact('statistic'));
    }

    public function edit(ResultStatistic $statistic)
    {
        $this->authorizeView($statistic);
        $announcements = ResultAnnouncement::where('school_id', auth()->user()->school_id ?? 1)
            ->orderByDesc('created_at')->get(['id','title']);
        return view('admin.result-announcement.statistics.edit', compact('statistic', 'announcements'));
    }

    public function update(Request $request, ResultStatistic $statistic)
    {
        $this->authorizeView($statistic);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'result_announcement_id' => 'nullable|exists:result_announcements,id',
            'filters' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $filters = $request->filters;
        if (is_string($filters) && trim($filters) !== '') {
            try {
                $decoded = json_decode($filters, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $filters = $decoded;
                } else {
                    return back()->withErrors(['filters' => 'Filters must be valid JSON.'])->withInput();
                }
            } catch (\Throwable $e) {
                return back()->withErrors(['filters' => 'Filters must be valid JSON.'])->withInput();
            }
        }

        $statistic->update([
            'result_announcement_id' => $request->result_announcement_id,
            'title' => $request->title,
            'filters' => $filters ?? [],
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.result-announcement.statistics.index')
            ->with('success', 'Statistics updated.');
    }

    public function destroy(ResultStatistic $statistic)
    {
        $this->authorizeView($statistic);
        $statistic->delete();
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.result-announcement.statistics.index')
            ->with('success', 'Statistic deleted.');
    }

    private function authorizeView(ResultStatistic $statistic): void
    {
        if (($statistic->school_id ?? null) !== (auth()->user()->school_id ?? 1)) {
            abort(403);
        }
    }
}



<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ResourceBookingController extends Controller
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
            $query = ResourceBooking::query()->where('school_id', $schoolId);
    
            if ($request->filled('status')) {
                $query->where('status', $request->string('status'));
            }
            if ($request->filled('resource_type')) {
                $query->where('resource_type', $request->string('resource_type'));
            }
            if ($request->filled('q')) {
                $q = $request->string('q');
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('resource_name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            }
    
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('start_time', function ($row) {
                    return optional($row->start_time)->format('Y-m-d H:i');
                })
                ->editColumn('end_time', function ($row) {
                    return optional($row->end_time)->format('Y-m-d H:i');
                })
                ->addColumn('resource', function ($row) {
                    return trim($row->resource_type . ' ' . ($row->resource_name ? '(' . $row->resource_name . ')' : ''));
                })
                ->addColumn('actions', function ($data) {
                    $buttons = '<div class="btn-group" role="group" aria-label="Actions">';
                
                    $buttons .= '<a href="' . route('admin.academic.resource-bookings.show', $data->id) . '" 
                                    class="btn btn-sm" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';
                
                    $buttons .= '<a href="' . route('admin.academic.resource-bookings.edit', $data->id) . '" 
                                    class="btn btn-sm " title="Edit">
                                    <i class="bx bxs-edit"></i>
                                </a>';
                
                    $buttons .= '<button type="button" data-id="' . $data->id . '" 
                                    class="btn btn-sm  delete-accountant-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </button>';
                
                    
                
                    $buttons .= '</div>';
                
                    return $buttons;
                })
                 
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.academic.resource-bookings.index');
    }

    public function create()
    {
        return view('admin.academic.resource-bookings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_type' => 'required|string|max:50',
            'resource_id' => 'nullable|integer',
            'resource_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'nullable|in:pending,approved,rejected,cancelled',
        ]);

        $validated['booked_by'] = Auth::id();
        $validated['school_id'] = $request->user()?->school_id ?? null;
        $validated['status'] = $validated['status'] ?? 'pending';

        ResourceBooking::create($validated);

        return redirect()->route('admin.academic.resource-bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    public function edit(ResourceBooking $resourceBooking)
    {
        return view('admin.academic.resource-bookings.edit', compact('resourceBooking'));
    }

    public function update(Request $request, ResourceBooking $resourceBooking)
    {
        $validated = $request->validate([
            'resource_type' => 'required|string|max:50',
            'resource_id' => 'nullable|integer',
            'resource_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:pending,approved,rejected,cancelled',
        ]);

        $resourceBooking->update($validated);

        return redirect()->route('admin.academic.resource-bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(ResourceBooking $resourceBooking)
    {
        $resourceBooking->delete();
        return redirect()->route('admin.academic.resource-bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->withErrors(['file' => 'Unable to read the file.']);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['file' => 'Empty or invalid CSV.']);
        }

        $schoolId = auth()->user()->school_id ?? null;
        $bookedBy = auth()->id();

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            // Expected columns: resource_type,resource_id,resource_name,title,description,start_time,end_time,status
            if (count($row) < 8) {
                continue;
            }
            [$resourceType, $resourceId, $resourceName, $title, $description, $startTime, $endTime, $status] = $row;

            try {
                ResourceBooking::create([
                    'resource_type' => trim((string) $resourceType),
                    'resource_id' => is_numeric($resourceId) ? (int) $resourceId : null,
                    'resource_name' => trim((string) $resourceName) ?: null,
                    'title' => trim((string) $title),
                    'description' => trim((string) $description) ?: null,
                    'booked_by' => $bookedBy,
                    'school_id' => $schoolId,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => in_array($status, ['pending','approved','rejected','cancelled']) ? $status : 'pending',
                ]);
                $imported++;
            } catch (\Throwable $e) {
                // skip invalid row
                continue;
            }
        }
        fclose($handle);

        return back()->with('success', "Imported {$imported} bookings.");
    }

    public function show(ResourceBooking $resourceBooking)
    {
        return view('admin.academic.resource-bookings.show', compact('resourceBooking'));
    }
    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $bookings = ResourceBooking::where('school_id', $schoolId)
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(10)
            ->get();

        $statusCounts = ResourceBooking::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $months = collect(range(1,12))->map(fn($m)=>date('M', mktime(0,0,0,$m,1)))->toArray();
        $bookingsPerMonth = ResourceBooking::where('school_id', $schoolId)
            ->whereYear('start_time', now()->year)
            ->selectRaw('MONTH(start_time) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');
        $monthlySeries = [];
        for ($m=1; $m<=12; $m++) { $monthlySeries[] = (int) ($bookingsPerMonth[$m] ?? 0); }

        // Resource type distribution
        $resourceTypeCounts = ResourceBooking::where('school_id', $schoolId)
            ->selectRaw('resource_type, COUNT(*) as total')
            ->groupBy('resource_type')
            ->pluck('total', 'resource_type');

        // Daily trend for the last 30 days
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();
        $dailyRows = ResourceBooking::where('school_id', $schoolId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->selectRaw('DATE(start_time) as d, COUNT(*) as total')
            ->groupBy('d')
            ->pluck('total', 'd');
        $dailyLabels = [];
        $dailySeries = [];
        for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
            $key = $d->toDateString();
            $dailyLabels[] = $d->format('d M');
            $dailySeries[] = (int) ($dailyRows[$key] ?? 0);
        }

        // Top resources by usage
        $topResourceRows = ResourceBooking::where('school_id', $schoolId)
            ->selectRaw("COALESCE(NULLIF(resource_name,''), CONCAT(UPPER(LEFT(resource_type,1)), SUBSTRING(resource_type,2))) as rname, COUNT(*) as total")
            ->groupBy('rname')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $topResourceLabels = $topResourceRows->pluck('rname')->toArray();
        $topResourceSeries = $topResourceRows->pluck('total')->map(fn($v)=>(int)$v)->toArray();

        return view('admin.academic.resource-bookings.dashboard', compact(
            'bookings',
            'statusCounts',
            'months',
            'monthlySeries',
            'resourceTypeCounts',
            'dailyLabels',
            'dailySeries',
            'topResourceLabels',
            'topResourceSeries'
        ));
    }

}



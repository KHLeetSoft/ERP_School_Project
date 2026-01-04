<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportTracking;
use App\Models\TransportVehicle;
use App\Models\TransportRoute;
use App\Models\TransportDriver;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

use Carbon\Carbon;

class TransportTrackingController extends Controller
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
            $adminUser = auth()->guard('admin')->user();
    
            $query = TransportTracking::with(['vehicle', 'route', 'driver'])
                ->latest();
    
            if ($adminUser) {
                $query->where('school_id', $adminUser->school_id);
            }
    
            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('vehicle_id')) {
                $query->where('vehicle_id', $request->vehicle_id);
            }
            if ($request->filled('route_id')) {
                $query->where('route_id', $request->route_id);
            }
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->whereBetween('tracking_date', [$request->date_from, $request->date_to]);
            }
    
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('vehicle', function ($row) {
                    if ($row->vehicle) {
                        return '<span class="fw-bold">' . e($row->vehicle->vehicle_number) . '</span><br>
                                <small class="text-muted">' . e($row->vehicle->model) . '</small>';
                    }
                    return '<span class="text-muted">N/A</span>';
                })
                ->addColumn('route', function ($row) {
                    if ($row->route) {
                        return '<span class="fw-bold">' . e($row->route->route_name) . '</span><br>
                                <small class="text-muted">' . e($row->route->start_location . ' → ' . $row->route->end_location) . '</small>';
                    }
                    return '<span class="text-muted">N/A</span>';
                })
                ->addColumn('driver', function ($row) {
                    if ($row->driver) {
                        return '<span class="fw-bold">' . e($row->driver->name) . '</span><br>
                                <small class="text-muted">📞 ' . e($row->driver->phone) . '</small>';
                    }
                    return '<span class="text-muted">N/A</span>';
                })
                ->addColumn('datetime', function ($row) {
                    return $row->tracking_date->format('M d, Y') . '<br>
                            <small class="text-muted">' . $row->tracking_time->format('H:i:s') . '</small>';
                })
                ->addColumn('location', function ($row) {
                    $location = $row->formatted_location ?? "N/A";
                    $btn = $row->google_maps_url
                        ? '<br><a href="' . $row->google_maps_url . '" target="_blank" class="btn btn-sm btn-outline-primary">
                               <i class="fas fa-map"></i> View
                           </a>'
                        : '';
                    return '<span class="fw-bold">' . e($location) . '</span>' . $btn;
                })
                ->addColumn('speed', fn($row) =>
                    '<span class="badge bg-info">' . e($row->formatted_speed) . '</span>'
                )
                ->addColumn('status', function ($row) {
                    // Use your partial for status badge
                    return view('admin.transport.tracking.partials.status-badge', ['data' => $row])->render();
                })
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex gap-2">';
                    $buttons .= '<a href="' . route('admin.transport.tracking.show', $row->id) . '" class="text-info"><i class="fas fa-eye"></i></a>';
                    $buttons .= '<a href="' . route('admin.transport.tracking.edit', $row->id) . '" class="text-primary"><i class="fas fa-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-tracking-btn"><i class="fas fa-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['vehicle', 'route', 'driver', 'datetime', 'location', 'speed', 'status', 'actions'])
                ->make(true);
        }
    
        $vehicles = TransportVehicle::bySchool(auth()->user()->school_id)->active()->get();
        $routes   = TransportRoute::bySchool(auth()->user()->school_id)->active()->get();
        $drivers  = TransportDriver::bySchool(auth()->user()->school_id)->active()->get();
        $stats    = $this->getStatistics();
    
        return view('admin.transport.tracking.index', compact('vehicles', 'routes', 'drivers', 'stats'));
    }
    
    public function dashboard()
    {
        $stats = $this->getStatistics();
        $recentTrackings = TransportTracking::with(['vehicle', 'route', 'driver'])
            ->bySchool(auth()->user()->school_id)
            ->orderBy('tracking_date', 'desc')
            ->orderBy('tracking_time', 'desc')
            ->limit(10)
            ->get();

        $todayTrackings = TransportTracking::with(['vehicle', 'route', 'driver'])
            ->bySchool(auth()->user()->school_id)
            ->whereDate('tracking_date', today())
            ->orderBy('tracking_time', 'desc')
            ->get();

        $activeVehicles = TransportVehicle::bySchool(auth()->user()->school_id)
            ->active()
            ->with(['driver', 'assignedRoute'])
            ->get();

        return view('admin.transport.tracking.dashboard', compact(
            'stats', 'recentTrackings', 'todayTrackings', 'activeVehicles'
        ));
    }

    public function create()
    {
        $vehicles = TransportVehicle::bySchool(auth()->user()->school_id)->active()->get();
        $routes = TransportRoute::bySchool(auth()->user()->school_id)->active()->get();
        $drivers = TransportDriver::bySchool(auth()->user()->school_id)->active()->get();

        return view('admin.transport.tracking.create', compact('vehicles', 'routes', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:transport_vehicles,id',
            'route_id' => 'required|exists:transport_routes,id',
            'driver_id' => 'required|exists:transport_drivers,id',
            'tracking_date' => 'required|date',
            'tracking_time' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'status' => 'required|in:on_time,delayed,early,stopped,moving',
            'notes' => 'nullable|string|max:1000',
        ]);

        $tracking = TransportTracking::create([
            'school_id' => auth()->user()->school_id,
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $request->route_id,
            'driver_id' => $request->driver_id,
            'tracking_date' => $request->tracking_date,
            'tracking_time' => $request->tracking_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed ?? 0,
            'status' => $request->status,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.transport.tracking.index')
            ->with('success', 'Tracking record created successfully.');
    }

    public function show(TransportTracking $tracking)
    {
        $tracking->load(['vehicle', 'route', 'driver', 'school', 'createdBy', 'updatedBy']);
        return view('admin.transport.tracking.show', compact('tracking'));
    }

    public function edit(TransportTracking $tracking)
    {
        $vehicles = TransportVehicle::bySchool(auth()->user()->school_id)->active()->get();
        $routes = TransportRoute::bySchool(auth()->user()->school_id)->active()->get();
        $drivers = TransportDriver::bySchool(auth()->user()->school_id)->active()->get();

        return view('admin.transport.tracking.edit', compact('tracking', 'vehicles', 'routes', 'drivers'));
    }

    public function update(Request $request, TransportTracking $tracking)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:transport_vehicles,id',
            'route_id' => 'required|exists:transport_routes,id',
            'driver_id' => 'required|exists:transport_drivers,id',
            'tracking_date' => 'required|date',
            'tracking_time' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'status' => 'required|in:on_time,delayed,early,stopped,moving',
            'notes' => 'nullable|string|max:1000',
        ]);

        $tracking->update([
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $request->route_id,
            'driver_id' => $request->driver_id,
            'tracking_date' => $request->tracking_date,
            'tracking_time' => $request->tracking_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed ?? 0,
            'status' => $request->status,
            'notes' => $request->notes,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.transport.tracking.index')
            ->with('success', 'Tracking record updated successfully.');
    }

    public function destroy(TransportTracking $tracking)
    {
        $tracking->delete();
        return redirect()->route('admin.transport.tracking.index')
            ->with('success', 'Tracking record deleted successfully.');
    }

    public function getStatistics()
    {
        $schoolId = auth()->user()->school_id;
        $today = today();
        $thisMonth = now()->startOfMonth();

        $stats = [
            'total_trackings' => TransportTracking::bySchool($schoolId)->count(),
            'today_trackings' => TransportTracking::bySchool($schoolId)->whereDate('tracking_date', $today)->count(),
            'monthly_trackings' => TransportTracking::bySchool($schoolId)->where('tracking_date', '>=', $thisMonth)->count(),
            'active_vehicles' => TransportVehicle::bySchool($schoolId)->active()->count(),
            'on_time_percentage' => 0,
            'delayed_trackings' => TransportTracking::bySchool($schoolId)->where('status', 'delayed')->count(),
            'stopped_vehicles' => TransportTracking::bySchool($schoolId)->where('status', 'stopped')->count(),
            'moving_vehicles' => TransportTracking::bySchool($schoolId)->where('status', 'moving')->count(),
        ];

        // Calculate on-time percentage
        $totalTrackings = $stats['total_trackings'];
        if ($totalTrackings > 0) {
            $onTimeTrackings = TransportTracking::bySchool($schoolId)->where('status', 'on_time')->count();
            $stats['on_time_percentage'] = round(($onTimeTrackings / $totalTrackings) * 100, 1);
        }

        // Monthly data for charts
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = TransportTracking::bySchool($schoolId)
                ->whereYear('tracking_date', $date->year)
                ->whereMonth('tracking_date', $date->month)
                ->count();
            $monthlyData[] = $count;
        }
        $stats['monthly_data'] = $monthlyData;

        // Status distribution
        $statusData = TransportTracking::bySchool($schoolId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        $stats['status_data'] = $statusData;

        return $stats;
    }

    public function getStatisticsData()
    {
        return response()->json($this->getStatistics());
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,export',
            'tracking_ids' => 'required|array|min:1',
            'tracking_ids.*' => 'exists:transport_trackings,id'
        ]);

        $trackings = TransportTracking::whereIn('id', $request->tracking_ids)
            ->bySchool(auth()->user()->school_id);

        switch ($request->action) {
            case 'delete':
                $trackings->delete();
                return redirect()->back()->with('success', 'Selected tracking records deleted successfully.');
            
            case 'export':
                // Export functionality can be implemented here
                return redirect()->back()->with('info', 'Export functionality will be implemented.');
        }
    }

    public function duplicate(TransportTracking $tracking)
    {
        $newTracking = $tracking->replicate();
        $newTracking->tracking_date = now()->toDateString();
        $newTracking->tracking_time = now()->toTimeString();
        $newTracking->created_by = auth()->id();
        $newTracking->save();

        return redirect()->route('admin.transport.tracking.index')
            ->with('success', 'Tracking record duplicated successfully.');
    }

    public function toggleStatus(TransportTracking $tracking)
    {
        $newStatus = $tracking->status === 'active' ? 'inactive' : 'active';
        $tracking->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Tracking status updated to {$newStatus}.");
    }

    // Live Tracking Methods
    public function liveTracking()
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;

        if (!$schoolId) {
            return redirect()->route('admin.dashboard')->with('error', 'School not found');
        }

        $vehicles = TransportVehicle::bySchool($schoolId)
            ->active()
            ->with(['driver', 'assignedRoute'])
            ->get();

        $routes = TransportRoute::bySchool($schoolId)->active()->get();
        $drivers = TransportDriver::bySchool($schoolId)->active()->get();

        // Get recent tracking data for live updates
        $recentTrackings = TransportTracking::with(['vehicle', 'route', 'driver'])
            ->bySchool($schoolId)
            ->whereDate('tracking_date', today())
            ->orderBy('tracking_time', 'desc')
            ->limit(50)
            ->get();

        return view('admin.transport.tracking.live', compact('vehicles', 'routes', 'drivers', 'recentTrackings'));
    }

    public function getLiveData()
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        
        if (!$schoolId) {
            return response()->json([
                'error' => 'School not found',
                'trackings' => [],
                'active_vehicles' => [],
                'stats' => [
                    'total_active_vehicles' => 0,
                    'moving_vehicles' => 0,
                    'stopped_vehicles' => 0,
                    'delayed_vehicles' => 0,
                    'on_time_vehicles' => 0,
                ]
            ]);
        }
        
        // Get today's tracking data
        $trackings = TransportTracking::with(['vehicle', 'route', 'driver'])
            ->bySchool($schoolId)
            ->whereDate('tracking_date', today())
            ->orderBy('tracking_time', 'desc')
            ->limit(20)
            ->get();

        // Get active vehicles with their latest tracking
        $activeVehicles = TransportVehicle::bySchool($schoolId)
            ->active()
            ->with(['driver', 'assignedRoute'])
            ->get()
            ->map(function ($vehicle) use ($schoolId) {
                $latestTracking = TransportTracking::bySchool($schoolId)
                    ->byVehicle($vehicle->id)
                    ->whereDate('tracking_date', today())
                    ->orderBy('tracking_time', 'desc')
                    ->first();
                
                $vehicle->latest_tracking = $latestTracking;
                return $vehicle;
            });

        // Get statistics for live dashboard
        $stats = [
            'total_active_vehicles' => $activeVehicles->count(),
            'moving_vehicles' => $activeVehicles->filter(function ($vehicle) {
                return $vehicle->latest_tracking && $vehicle->latest_tracking->status === 'moving';
            })->count(),
            'stopped_vehicles' => $activeVehicles->filter(function ($vehicle) {
                return $vehicle->latest_tracking && $vehicle->latest_tracking->status === 'stopped';
            })->count(),
            'delayed_vehicles' => $activeVehicles->filter(function ($vehicle) {
                return $vehicle->latest_tracking && $vehicle->latest_tracking->status === 'delayed';
            })->count(),
            'on_time_vehicles' => $activeVehicles->filter(function ($vehicle) {
                return $vehicle->latest_tracking && $vehicle->latest_tracking->status === 'on_time';
            })->count(),
        ];

        return response()->json([
            'trackings' => $trackings,
            'active_vehicles' => $activeVehicles,
            'stats' => $stats,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function updateLiveLocation(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:transport_vehicles,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'status' => 'required|in:on_time,delayed,early,stopped,moving',
            'notes' => 'nullable|string|max:1000',
        ]);

        $vehicle = TransportVehicle::find($request->vehicle_id);
        $route = $vehicle->assignedRoute;
        $driver = $vehicle->driver;

        if (!$route || !$driver) {
            return response()->json(['error' => 'Vehicle must have assigned route and driver'], 400);
        }

        $tracking = TransportTracking::create([
            'school_id' => auth()->user()->school_id,
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $route->id,
            'driver_id' => $driver->id,
            'tracking_date' => now()->toDateString(),
            'tracking_time' => now()->toTimeString(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed ?? 0,
            'status' => $request->status,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'tracking' => $tracking->load(['vehicle', 'route', 'driver']),
            'message' => 'Location updated successfully'
        ]);
    }

    public function startLiveTracking(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:transport_vehicles,id',
        ]);

        $vehicle = TransportVehicle::find($request->vehicle_id);
        
        if (!$vehicle->assignedRoute || !$vehicle->driver) {
            return response()->json(['error' => 'Vehicle must have assigned route and driver'], 400);
        }

        // Create initial tracking record
        $tracking = TransportTracking::create([
            'school_id' => auth()->user()->school_id,
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $vehicle->assignedRoute->id,
            'driver_id' => $vehicle->driver->id,
            'tracking_date' => now()->toDateString(),
            'tracking_time' => now()->toTimeString(),
            'latitude' => 28.6139, // Default location
            'longitude' => 77.2090,
            'speed' => 0,
            'status' => 'stopped',
            'notes' => 'Live tracking started',
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'tracking' => $tracking->load(['vehicle', 'route', 'driver']),
            'message' => 'Live tracking started successfully'
        ]);
    }

    public function stopLiveTracking(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:transport_vehicles,id',
        ]);

        // Create final tracking record
        $vehicle = TransportVehicle::find($request->vehicle_id);
        $latestTracking = TransportTracking::bySchool(auth()->user()->school_id)
            ->byVehicle($request->vehicle_id)
            ->whereDate('tracking_date', today())
            ->orderBy('tracking_time', 'desc')
            ->first();

        if ($latestTracking) {
            $tracking = TransportTracking::create([
                'school_id' => auth()->user()->school_id,
                'vehicle_id' => $request->vehicle_id,
                'route_id' => $latestTracking->route_id,
                'driver_id' => $latestTracking->driver_id,
                'tracking_date' => now()->toDateString(),
                'tracking_time' => now()->toTimeString(),
                'latitude' => $latestTracking->latitude,
                'longitude' => $latestTracking->longitude,
                'speed' => 0,
                'status' => 'stopped',
                'notes' => 'Live tracking stopped',
                'created_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'tracking' => $tracking->load(['vehicle', 'route', 'driver']),
                'message' => 'Live tracking stopped successfully'
            ]);
        }

        return response()->json(['error' => 'No active tracking found'], 400);
    }

    public function simulateLiveTracking(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:transport_vehicles,id',
            'duration' => 'required|integer|min:1|max:60', // minutes
        ]);

        $vehicle = TransportVehicle::find($request->vehicle_id);
        
        if (!$vehicle->assignedRoute || !$vehicle->driver) {
            return response()->json(['error' => 'Vehicle must have assigned route and driver'], 400);
        }

        // Start simulation
        $this->startSimulation($vehicle, $request->duration);

        return response()->json([
            'success' => true,
            'message' => "Live tracking simulation started for {$request->duration} minutes"
        ]);
    }

    private function startSimulation($vehicle, $duration)
    {
        // This would typically be handled by a queue job
        // For now, we'll create a few simulated tracking points
        
        $baseLat = 28.6139;
        $baseLng = 77.2090;
        $statuses = ['moving', 'stopped', 'on_time', 'delayed'];
        
        for ($i = 0; $i < min($duration, 10); $i++) {
            $tracking = TransportTracking::create([
                'school_id' => auth()->user()->school_id,
                'vehicle_id' => $vehicle->id,
                'route_id' => $vehicle->assignedRoute->id,
                'driver_id' => $vehicle->driver->id,
                'tracking_date' => now()->toDateString(),
                'tracking_time' => now()->addMinutes($i)->toTimeString(),
                'latitude' => $baseLat + (rand(-100, 100) / 10000),
                'longitude' => $baseLng + (rand(-100, 100) / 10000),
                'speed' => rand(0, 60),
                'status' => $statuses[array_rand($statuses)],
                'notes' => "Simulated tracking point " . ($i + 1),
                'created_by' => auth()->id(),
            ]);
        }
    }
}

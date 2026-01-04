<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportAssignment;
use App\Models\TransportVehicle;
use App\Models\TransportRoute;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TransportAssignmentController extends Controller
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
    
    /**
     * Display a listing of transport assignments with DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $schoolId = Auth::user()->school_id ?? 1;
                
                $query = TransportAssignment::with([
                        'vehicle' => function($query) {
                            $query->select('id', 'vehicle_number', 'registration_number', 'brand', 'model', 'status');
                        },
                        'route' => function($query) {
                            $query->select('id', 'route_name', 'route_number', 'start_location', 'end_location', 'status');
                        },
                        'driver' => function($query) {
                            $query->select('id', 'name', 'email');
                        },
                        'conductor' => function($query) {
                            $query->select('id', 'name', 'email');
                        },
                        'assignedBy' => function($query) {
                            $query->select('id', 'name', 'email');
                        }
                    ])
                    ->where('school_id', $schoolId);

                // Apply filters
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                if ($request->filled('shift_type')) {
                    $query->where('shift_type', $request->shift_type);
                }

                if ($request->filled('date_range')) {
                    $dates = explode(' - ', $request->date_range);
                    if (count($dates) == 2) {
                        $query->whereBetween('assignment_date', [
                            Carbon::parse($dates[0])->format('Y-m-d'),
                            Carbon::parse($dates[1])->format('Y-m-d')
                        ]);
                    }
                }

                if ($request->filled('vehicle_id')) {
                    $query->where('vehicle_id', $request->vehicle_id);
                }

                if ($request->filled('route_id')) {
                    $query->where('route_id', $request->route_id);
                }

                // Global search
                if ($request->filled('search.value')) {
                    $searchValue = $request->input('search.value');
                    $query->where(function($q) use ($searchValue) {
                        $q->whereHas('vehicle', function($vehicleQuery) use ($searchValue) {
                            $vehicleQuery->where('vehicle_number', 'like', "%{$searchValue}%")
                                        ->orWhere('registration_number', 'like', "%{$searchValue}%");
                        })
                        ->orWhereHas('route', function($routeQuery) use ($searchValue) {
                            $routeQuery->where('route_name', 'like', "%{$searchValue}%")
                                       ->orWhere('route_number', 'like', "%{$searchValue}%");
                        })
                        ->orWhereHas('driver', function($driverQuery) use ($searchValue) {
                            $driverQuery->where('name', 'like', "%{$searchValue}%");
                        })
                        ->orWhere('assignment_date', 'like', "%{$searchValue}%")
                        ->orWhere('shift_type', 'like', "%{$searchValue}%")
                        ->orWhere('status', 'like', "%{$searchValue}%");
                    });
                }

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('assignment_info', function ($data) {
                        try {
                            return view('admin.transport.assign.partials.assignment-info', compact('data'))->render();
                        } catch (\Exception $e) {
                            return '<span class="text-muted">Error loading assignment info</span>';
                        }
                    })
                    ->addColumn('vehicle_info', function ($data) {
                        try {
                            return view('admin.transport.assign.partials.vehicle-info', compact('data'))->render();
                        } catch (\Exception $e) {
                            return '<span class="text-muted">Error loading vehicle info</span>';
                        }
                    })
                    ->addColumn('route_info', function ($data) {
                        try {
                            return view('admin.transport.assign.partials.route-info', compact('data'))->render();
                        } catch (\Exception $e) {
                            return '<span class="text-muted">Error loading route info</span>';
                        }
                    })
                    ->addColumn('staff_info', function ($data) {
                        try {
                            return view('admin.transport.assign.partials.staff-info', compact('data'))->render();
                        } catch (\Exception $e) {
                            return '<span class="text-muted">Error loading staff info</span>';
                        }
                    })
                    ->addColumn('schedule_info', function ($data) {
                        try {
                            return view('admin.transport.assign.partials.schedule-info', compact('data'))->render();
                        } catch (\Exception $e) {
                            return '<span class="text-muted">Error loading schedule info</span>';
                        }
                    })
                    ->addColumn('status_badge', function ($data) {
                        try {
                            return view('admin.transport.assign.partials.status-badge', compact('data'))->render();
                        } catch (\Exception $e) {
                            return '<span class="text-muted">Error loading status</span>';
                        }
                    })
                    ->addColumn('actions', function ($data) {
                        try {
                            return view('admin.transport.assign.partials.actions', compact('data'))->render();
                        } catch (\Exception $e) {
                            return '<span class="text-muted">Error loading actions</span>';
                        }
                    })
                    ->rawColumns(['assignment_info', 'vehicle_info', 'route_info', 'staff_info', 'schedule_info', 'status_badge', 'actions'])
                    ->make(true);

            } catch (\Exception $e) {
                \Log::error('DataTables error: ' . $e->getMessage());
                return response()->json([
                    'error' => 'An error occurred while loading data: ' . $e->getMessage()
                ], 500);
            }
        }

        // Get statistics for dashboard cards
        $stats = $this->getStatistics();
        
        // Get filter options
        $vehicles = TransportVehicle::where('school_id', Auth::user()->school_id ?? 1)
            ->where('status', 'active')
            ->select('id', 'vehicle_number', 'registration_number')
            ->get();
            
        $routes = TransportRoute::where('school_id', Auth::user()->school_id ?? 1)
            ->where('status', 'active')
            ->select('id', 'route_name', 'route_number')
            ->get();

        return view('admin.transport.assign.index', compact('stats', 'vehicles', 'routes'));
    }

    /**
     * Show the form for creating a new transport assignment
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id ?? 1;
        
        $vehicles = TransportVehicle::where('school_id', $schoolId)
            ->where('status', 'active')
            ->where('is_available', true)
            ->get();
            
        $routes = TransportRoute::where('school_id', $schoolId)
            ->where('status', 'active')
            ->get();
            
        $drivers = User::where('school_id', $schoolId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'driver');
            })
            ->get();
            
        $conductors = User::where('school_id', $schoolId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'conductor');
            })
            ->get();

        return view('admin.transport.assign.create', compact('vehicles', 'routes', 'drivers', 'conductors'));
    }

    /**
     * Store a newly created transport assignment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:transport_vehicles,id',
            'route_id' => 'required|exists:transport_routes,id',
            'driver_id' => 'nullable|exists:users,id',
            'conductor_id' => 'nullable|exists:users,id',
            'assignment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'shift_type' => 'required|in:morning,afternoon,evening,night,full_day',
            'status' => 'required|in:pending,active,completed,cancelled,delayed',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $assignment = TransportAssignment::create([
                'school_id' => Auth::user()->school_id ?? 1,
                'vehicle_id' => $request->vehicle_id,
                'route_id' => $request->route_id,
                'driver_id' => $request->driver_id,
                'conductor_id' => $request->conductor_id,
                'assignment_date' => $request->assignment_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'shift_type' => $request->shift_type,
                'status' => $request->status,
                'notes' => $request->notes,
                'assigned_by' => Auth::id(),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_active' => $request->status === 'active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transport assignment created successfully!',
                'assignment' => $assignment
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified transport assignment
     */
    public function show(TransportAssignment $assignment)
    {
        $assignment->load(['vehicle', 'route', 'driver', 'conductor', 'assignedBy', 'createdBy', 'updatedBy']);
        return view('admin.transport.assign.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified transport assignment
     */
    public function edit(TransportAssignment $assignment)
    {
        $schoolId = Auth::user()->school_id ?? 1;
        
        $vehicles = TransportVehicle::where('school_id', $schoolId)
            ->where('status', 'active')
            ->get();
            
        $routes = TransportRoute::where('school_id', $schoolId)
            ->where('status', 'active')
            ->get();
            
        $drivers = User::where('school_id', $schoolId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'driver');
            })
            ->get();
            
        $conductors = User::where('school_id', $schoolId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'conductor');
            })
            ->get();

        return view('admin.transport.assign.edit', compact('assignment', 'vehicles', 'routes', 'drivers', 'conductors'));
    }

    /**
     * Update the specified transport assignment
     */
    public function update(Request $request, TransportAssignment $assignment)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:transport_vehicles,id',
            'route_id' => 'required|exists:transport_routes,id',
            'driver_id' => 'nullable|exists:users,id',
            'conductor_id' => 'nullable|exists:users,id',
            'assignment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'shift_type' => 'required|in:morning,afternoon,evening,night,full_day',
            'status' => 'required|in:pending,active,completed,cancelled,delayed',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $assignment->update([
                'vehicle_id' => $request->vehicle_id,
                'route_id' => $request->route_id,
                'driver_id' => $request->driver_id,
                'conductor_id' => $request->conductor_id,
                'assignment_date' => $request->assignment_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'shift_type' => $request->shift_type,
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => Auth::id(),
                'is_active' => $request->status === 'active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transport assignment updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified transport assignment
     */
    public function destroy(TransportAssignment $assignment)
    {
        try {
            $assignment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transport assignment deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle assignment status
     */
    public function toggleStatus(Request $request, TransportAssignment $assignment)
    {
        try {
            $newStatus = $assignment->status === 'active' ? 'completed' : 'active';
            $assignment->update([
                'status' => $newStatus,
                'is_active' => $newStatus === 'active',
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Assignment status changed to {$newStatus}!",
                'status' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk actions for assignments
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,complete,cancel,delete',
            'assignments' => 'required|array|min:1',
            'assignments.*' => 'exists:transport_assignments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $assignments = TransportAssignment::whereIn('id', $request->assignments)->get();
            $count = 0;

            foreach ($assignments as $assignment) {
                switch ($request->action) {
                    case 'activate':
                        $assignment->update(['status' => 'active', 'is_active' => true]);
                        break;
                    case 'complete':
                        $assignment->update(['status' => 'completed', 'is_active' => false]);
                        break;
                    case 'cancel':
                        $assignment->update(['status' => 'cancelled', 'is_active' => false]);
                        break;
                    case 'delete':
                        $assignment->delete();
                        break;
                }
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully {$request->action}d {$count} assignment(s)!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        $schoolId = Auth::user()->school_id ?? 1;

        $stats = [
            'total_assignments' => TransportAssignment::where('school_id', $schoolId)->count(),
            'active_assignments' => TransportAssignment::where('school_id', $schoolId)->where('status', 'active')->count(),
            'pending_assignments' => TransportAssignment::where('school_id', $schoolId)->where('status', 'pending')->count(),
            'completed_assignments' => TransportAssignment::where('school_id', $schoolId)->where('status', 'completed')->count(),
            'cancelled_assignments' => TransportAssignment::where('school_id', $schoolId)->where('status', 'cancelled')->count(),
            'delayed_assignments' => TransportAssignment::where('school_id', $schoolId)->where('status', 'delayed')->count(),
            'today_assignments' => TransportAssignment::where('school_id', $schoolId)->whereDate('assignment_date', today())->count(),
            'this_week_assignments' => TransportAssignment::where('school_id', $schoolId)->whereBetween('assignment_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_assignments' => TransportAssignment::where('school_id', $schoolId)->whereMonth('assignment_date', now()->month)->count(),
            'overdue_assignments' => TransportAssignment::where('school_id', $schoolId)
                ->where('status', 'active')
                ->where('assignment_date', '<', today())
                ->count()
        ];

        // Calculate utilization percentage
        $totalVehicles = TransportVehicle::where('school_id', $schoolId)->count();
        $activeAssignments = $stats['active_assignments'];
        $utilizationPercentage = $totalVehicles > 0 ? round(($activeAssignments / $totalVehicles) * 100, 1) : 0;
        $stats['utilization_percentage'] = $utilizationPercentage;

        // Monthly data for charts
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = TransportAssignment::where('school_id', $schoolId)
                ->whereMonth('assignment_date', $i)
                ->whereYear('assignment_date', now()->year)
                ->count();
        }
        $stats['monthly_data'] = $monthlyData;

        return $stats;
    }

    /**
     * Dashboard view
     */
    public function dashboard()
    {
        $stats = $this->getStatistics();
        
        $schoolId = Auth::user()->school_id ?? 1;

        // Recent assignments
        $recentAssignments = TransportAssignment::where('school_id', $schoolId)
            ->with(['vehicle', 'route', 'driver'])
            ->latest()
            ->take(10)
            ->get();

        // Today's schedule
        $todaySchedule = TransportAssignment::where('school_id', $schoolId)
            ->whereDate('assignment_date', today())
            ->with(['vehicle', 'route', 'driver'])
            ->orderBy('start_time')
            ->get();

        // Add total vehicles count to stats
        $stats['total_vehicles'] = TransportVehicle::where('school_id', $schoolId)->count();

        return view('admin.transport.assign.dashboard', compact('stats', 'recentAssignments', 'todaySchedule'));
    }
}
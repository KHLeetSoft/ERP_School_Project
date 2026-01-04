<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportVehicle;
use App\Models\TransportRoute;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TransportVehicleController extends Controller
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
     * Display a listing of transport vehicles.
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        
        // Check if it's a DataTables request
        if ($request->ajax()) {
            return $this->getDataTableData($request, $schoolId);
        }

        // Statistics for dashboard
        $stats = [
            'total_vehicles' => TransportVehicle::where('school_id', $schoolId)->count(),
            'active_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('status', 'active')->where('is_active', true)->count(),
            'maintenance_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('status', 'maintenance')->count(),
            'available_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('is_available', true)->count(),
        ];

        return view('admin.transport.vehicles.index', compact('stats'));
    }

    /**
     * Get DataTables data for vehicles.
     */
    private function getDataTableData(Request $request, $schoolId)
    {
        $query = TransportVehicle::where('school_id', $schoolId);

        // Global search
        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('vehicle_number', 'like', "%{$searchValue}%")
                  ->orWhere('registration_number', 'like', "%{$searchValue}%")
                  ->orWhere('brand', 'like', "%{$searchValue}%")
                  ->orWhere('model', 'like', "%{$searchValue}%")
                  ->orWhere('vehicle_type', 'like', "%{$searchValue}%")
                  ->orWhere('fuel_type', 'like', "%{$searchValue}%");
            });
        }

        $rows = $query->orderBy('id', 'desc')->get();

        return DataTables::of($rows)
            ->addIndexColumn()
            ->addColumn('vehicle_info', function ($data) {
                return '<strong>' . e($data->vehicle_number) . '</strong><br><small class="text-muted">#' . e($data->registration_number) . '</small>';
            })
            ->addColumn('registration', function ($data) {
                return $data->registration_number ?? '-';
            })
            ->addColumn('type', function ($data) {
                return ucfirst($data->vehicle_type ?? '-');
            })
            ->addColumn('brand_model', function ($data) {
                return ($data->brand ?? '') . ' ' . ($data->model ?? '');
            })
            ->addColumn('status', function ($data) {
                if ($data->status === 'active') {
                    return '<span class="badge badge-pill badge-light-success">Active</span>';
                } elseif ($data->status === 'maintenance') {
                    return '<span class="badge badge-pill badge-light-warning">Maintenance</span>';
                } elseif ($data->status === 'repair') {
                    return '<span class="badge badge-pill badge-light-danger">Repair</span>';
                } elseif ($data->status === 'inactive') {
                    return '<span class="badge badge-pill badge-light-secondary">Inactive</span>';
                } else {
                    return '<span class="badge badge-pill badge-light-dark">' . ucfirst($data->status ?? 'Unknown') . '</span>';
                }
            })
            ->addColumn('availability', function ($data) {
                if ($data->is_available) {
                    return '<span class="badge badge-pill badge-light-success">Available</span>';
                } else {
                    return '<span class="badge badge-pill badge-light-danger">Unavailable</span>';
                }
            })
            ->addColumn('created', function ($data) {
                return $data->created_at ? $data->created_at->format('M d, Y') : '-';
            })
            ->addColumn('action', function ($data) {
                $buttons = '<div class="d-flex justify-content">';
                $buttons .= '<a href="' . route('admin.transport.vehicles.show', $data->id) . '" class="text-info me-2" title="View">
                                <i class="bx bx-show"></i>
                            </a>';
                $buttons .= '<a href="' . route('admin.transport.vehicles.edit', $data->id) . '" class="text-primary me-2" title="Edit"> 
                                <i class="bx bxs-edit"></i>
                            </a>';
                $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-vehicle-btn" title="Delete">
                                <i class="bx bx-trash"></i>
                            </a>';
                $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning toggle-status-btn" title="Toggle Status">
                                <i class="bx bx-toggle-right"></i>
                            </a>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['vehicle_info', 'registration', 'type', 'brand_model', 'status', 'availability', 'created', 'action'])
            ->make(true);
    }

    /**
     * Get status badge HTML.
     */
    private function getStatusBadge($status)
    {
        $badgeClass = [
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'maintenance' => 'bg-warning',
            'repair' => 'bg-danger',
            'offline' => 'bg-dark'
        ];

        $badgeClass = $badgeClass[$status] ?? 'bg-secondary';
        return '<span class="badge ' . $badgeClass . '">' . ucfirst($status) . '</span>';
    }

    /**
     * Get availability badge HTML.
     */
    private function getAvailabilityBadge($isAvailable)
    {
        $badgeClass = $isAvailable ? 'bg-success' : 'bg-danger';
        $text = $isAvailable ? 'Available' : 'Unavailable';
        return '<span class="badge ' . $badgeClass . '">' . $text . '</span>';
    }

    /**
     * Get action buttons HTML.
     */
    private function getActionButtons($vehicleId)
    {
        $buttons = '<div class="btn-group" role="group">';
        $buttons .= '<a href="' . route('admin.transport.vehicles.show', $vehicleId) . '" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>';
        $buttons .= '<a href="' . route('admin.transport.vehicles.edit', $vehicleId) . '" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
        $buttons .= '<button class="btn btn-sm btn-danger deleteBtn" data-id="' . $vehicleId . '" title="Delete"><i class="fas fa-trash"></i></button>';
        $buttons .= '</div>';
        
        return $buttons;
    }

    /**
     * Show the form for creating a new transport vehicle.
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
            'repair' => 'Repair',
            'offline' => 'Offline'
        ];

        $vehicleTypes = [
            'bus' => 'Bus',
            'minibus' => 'Mini Bus',
            'van' => 'Van',
            'car' => 'Car',
            'truck' => 'Truck'
        ];

        $fuelTypes = [
            'petrol' => 'Petrol',
            'diesel' => 'Diesel',
            'cng' => 'CNG',
            'electric' => 'Electric',
            'hybrid' => 'Hybrid'
        ];

        $drivers = User::where('school_id', $schoolId)->take(5)->get();
        $conductors = User::where('school_id', $schoolId)->take(5)->get();
        $routes = TransportRoute::where('school_id', $schoolId)->where('status', 'active')->get();

        return view('admin.transport.vehicles.create', compact('statuses', 'vehicleTypes', 'fuelTypes', 'drivers', 'conductors', 'routes'));
    }

    /**
     * Store a newly created transport vehicle.
     */
    public function store(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        
        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required|string|max:50|unique:transport_vehicles,vehicle_number',
            'registration_number' => 'required|string|max:50|unique:transport_vehicles,registration_number',
            'vehicle_type' => 'required|in:bus,minibus,van,car,truck',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year_of_manufacture' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'seating_capacity' => 'required|integer|min:1|max:100',
            'fuel_type' => 'required|in:petrol,diesel,cng,electric,hybrid',
            'fuel_efficiency' => 'nullable|numeric|min:0',
            'insurance_number' => 'nullable|string|max:100',
            'insurance_expiry' => 'nullable|date|after:today',
            'permit_number' => 'nullable|string|max:100',
            'permit_expiry' => 'nullable|date|after:today',
            'fitness_certificate_number' => 'nullable|string|max:100',
            'fitness_expiry' => 'nullable|date|after:today',
            'puc_certificate_number' => 'nullable|string|max:100',
            'puc_expiry' => 'nullable|date|after:today',
            'driver_id' => 'nullable|exists:users,id',
            'conductor_id' => 'nullable|exists:users,id',
            'assigned_route_id' => 'nullable|exists:transport_routes,id',
            'status' => 'required|in:active,inactive,maintenance,repair,offline',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $vehicleData = [
                'school_id' => $schoolId,
                'vehicle_number' => $request->vehicle_number,
                'registration_number' => $request->registration_number,
                'vehicle_type' => $request->vehicle_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'year_of_manufacture' => $request->year_of_manufacture,
                'seating_capacity' => $request->seating_capacity,
                'fuel_type' => $request->fuel_type,
                'fuel_efficiency' => $request->fuel_efficiency,
                'insurance_number' => $request->insurance_number,
                'insurance_expiry' => $request->insurance_expiry,
                'permit_number' => $request->permit_number,
                'permit_expiry' => $request->permit_expiry,
                'fitness_certificate_number' => $request->fitness_certificate_number,
                'fitness_expiry' => $request->fitness_expiry,
                'puc_certificate_number' => $request->puc_certificate_number,
                'puc_expiry' => $request->puc_expiry,
                'driver_id' => $request->driver_id,
                'conductor_id' => $request->conductor_id,
                'assigned_route_id' => $request->assigned_route_id,
                'status' => $request->status,
                'is_active' => $request->status === 'active',
                'is_available' => $request->status === 'active',
                'description' => $request->description,
                'features' => $request->features,
                'created_by' => Auth::id()
            ];

            // Handle image uploads
            if ($request->hasFile('images')) {
                $imageUrls = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('vehicles', 'public');
                    $imageUrls[] = Storage::url($path);
                }
                $vehicleData['images'] = $imageUrls;
            }

            $vehicle = TransportVehicle::create($vehicleData);

            return redirect()->route('admin.transport.vehicles.index')
                ->with('success', 'Transport vehicle created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating transport vehicle: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating transport vehicle. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified transport vehicle.
     */
    public function show(TransportVehicle $vehicle)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('view', $vehicle);
        
        return view('admin.transport.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified transport vehicle.
     */
    public function edit(TransportVehicle $vehicle)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('update', $vehicle);
        
        $schoolId = Auth::user()->school_id;
        
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
            'repair' => 'Repair',
            'offline' => 'Offline'
        ];

        $vehicleTypes = [
            'bus' => 'Bus',
            'minibus' => 'Mini Bus',
            'van' => 'Van',
            'car' => 'Car',
            'truck' => 'Truck'
        ];

        $fuelTypes = [
            'petrol' => 'Petrol',
            'diesel' => 'Diesel',
            'cng' => 'CNG',
            'electric' => 'Electric',
            'hybrid' => 'Hybrid'
        ];

        $drivers = User::where('school_id', $schoolId)->take(5)->get();
        $conductors = User::where('school_id', $schoolId)->take(5)->get();
        $routes = TransportRoute::where('school_id', $schoolId)->where('status', 'active')->get();

        return view('admin.transport.vehicles.edit', compact('vehicle', 'statuses', 'vehicleTypes', 'fuelTypes', 'drivers', 'conductors', 'routes'));
    }

    /**
     * Update the specified transport vehicle.
     */
    public function update(Request $request, TransportVehicle $vehicle)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('update', $vehicle);
        
        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required|string|max:50|unique:transport_vehicles,vehicle_number,' . $vehicle->id,
            'registration_number' => 'required|string|max:50|unique:transport_vehicles,registration_number,' . $vehicle->id,
            'vehicle_type' => 'required|in:bus,minibus,van,car,truck',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year_of_manufacture' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'seating_capacity' => 'required|integer|min:1|max:100',
            'fuel_type' => 'required|in:petrol,diesel,cng,electric,hybrid',
            'fuel_efficiency' => 'nullable|numeric|min:0',
            'insurance_number' => 'nullable|string|max:100',
            'insurance_expiry' => 'nullable|date|after:today',
            'permit_number' => 'nullable|string|max:100',
            'permit_expiry' => 'nullable|date|after:today',
            'fitness_certificate_number' => 'nullable|string|max:100',
            'fitness_expiry' => 'nullable|date|after:today',
            'puc_certificate_number' => 'nullable|string|max:100',
            'puc_expiry' => 'nullable|date|after:today',
            'driver_id' => 'nullable|exists:users,id',
            'conductor_id' => 'nullable|exists:users,id',
            'assigned_route_id' => 'nullable|exists:transport_routes,id',
            'status' => 'required|in:active,inactive,maintenance,repair,offline',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $vehicleData = [
                'vehicle_number' => $request->vehicle_number,
                'registration_number' => $request->registration_number,
                'vehicle_type' => $request->vehicle_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'year_of_manufacture' => $request->year_of_manufacture,
                'seating_capacity' => $request->seating_capacity,
                'fuel_type' => $request->fuel_type,
                'fuel_efficiency' => $request->fuel_efficiency,
                'insurance_number' => $request->insurance_number,
                'insurance_expiry' => $request->insurance_expiry,
                'permit_number' => $request->permit_number,
                'permit_expiry' => $request->permit_expiry,
                'fitness_certificate_number' => $request->fitness_certificate_number,
                'fitness_expiry' => $request->fitness_expiry,
                'puc_certificate_number' => $request->puc_certificate_number,
                'puc_expiry' => $request->puc_expiry,
                'driver_id' => $request->driver_id,
                'conductor_id' => $request->conductor_id,
                'assigned_route_id' => $request->assigned_route_id,
                'status' => $request->status,
                'is_active' => $request->status === 'active',
                'is_available' => $request->status === 'active',
                'description' => $request->description,
                'features' => $request->features,
                'updated_by' => Auth::id()
            ];

            // Handle image uploads
            if ($request->hasFile('images')) {
                $imageUrls = $vehicle->images ?? [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('vehicles', 'public');
                    $imageUrls[] = Storage::url($path);
                }
                $vehicleData['images'] = $imageUrls;
            }

            $vehicle->update($vehicleData);

            return redirect()->route('admin.transport.vehicles.index')
                ->with('success', 'Transport vehicle updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating transport vehicle: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating transport vehicle. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified transport vehicle.
     */
    public function destroy(TransportVehicle $vehicle)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('delete', $vehicle);
        
        try {
            $vehicle->delete();
            return redirect()->route('admin.transport.vehicles.index')
                ->with('success', 'Transport vehicle deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting transport vehicle: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting transport vehicle. Please try again.');
        }
    }

    /**
     * Toggle vehicle status.
     */
    public function toggleStatus(TransportVehicle $vehicle)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('update', $vehicle);
        
        try {
            $newStatus = $vehicle->status === 'active' ? 'inactive' : 'active';
            $vehicle->update([
                'status' => $newStatus,
                'is_active' => $newStatus === 'active',
                'is_available' => $newStatus === 'active'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vehicle status updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating vehicle status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating vehicle status. Please try again.'
            ], 500);
        }
    }

    /**
     * Duplicate vehicle.
     */
    public function duplicate(TransportVehicle $vehicle)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('create', TransportVehicle::class);
        
        try {
            $newVehicle = $vehicle->replicate();
            $newVehicle->vehicle_number = $vehicle->vehicle_number . '_copy';
            $newVehicle->registration_number = $vehicle->registration_number . '_copy';
            $newVehicle->status = 'inactive';
            $newVehicle->is_active = false;
            $newVehicle->is_available = false;
            $newVehicle->current_occupancy = 0;
            $newVehicle->total_distance_covered = 0;
            $newVehicle->created_by = Auth::id();
            $newVehicle->save();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle duplicated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error duplicating vehicle: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating vehicle. Please try again.'
            ], 500);
        }
    }

    /**
     * Show vehicles dashboard.
     */
    public function dashboard()
    {
        $schoolId = Auth::user()->school_id;
        
        $stats = [
            'total_vehicles' => TransportVehicle::where('school_id', $schoolId)->count(),
            'active_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('status', 'active')->count(),
            'maintenance_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('status', 'maintenance')->count(),
            'repair_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('status', 'repair')->count(),
            'available_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('is_available', true)->count(),
            'total_capacity' => TransportVehicle::where('school_id', $schoolId)->sum('seating_capacity'),
            'avg_fuel_efficiency' => TransportVehicle::where('school_id', $schoolId)->avg('fuel_efficiency'),
            'fleet_utilization' => TransportVehicle::where('school_id', $schoolId)->where('is_available', true)->count() / max(TransportVehicle::where('school_id', $schoolId)->count(), 1) * 100,
            'vehicle_type_counts' => [
                'bus' => TransportVehicle::where('school_id', $schoolId)->where('vehicle_type', 'bus')->count(),
                'minibus' => TransportVehicle::where('school_id', $schoolId)->where('vehicle_type', 'minibus')->count(),
                'van' => TransportVehicle::where('school_id', $schoolId)->where('vehicle_type', 'van')->count(),
                'car' => TransportVehicle::where('school_id', $schoolId)->where('vehicle_type', 'car')->count(),
                'truck' => TransportVehicle::where('school_id', $schoolId)->where('vehicle_type', 'truck')->count(),
            ],
            'status_counts' => [
                'active' => TransportVehicle::where('school_id', $schoolId)->where('status', 'active')->count(),
                'inactive' => TransportVehicle::where('school_id', $schoolId)->where('status', 'inactive')->count(),
                'maintenance' => TransportVehicle::where('school_id', $schoolId)->where('status', 'maintenance')->count(),
                'repair' => TransportVehicle::where('school_id', $schoolId)->where('status', 'repair')->count(),
                'offline' => TransportVehicle::where('school_id', $schoolId)->where('status', 'offline')->count(),
            ],
            'expiring_documents' => TransportVehicle::where('school_id', $schoolId)
                ->where(function($query) {
                    $query->where('insurance_expiry', '<=', now()->addDays(30))
                          ->orWhere('fitness_expiry', '<=', now()->addDays(30))
                          ->orWhere('puc_expiry', '<=', now()->addDays(30));
                })->count()
        ];

        // Sample data for dashboard
        $recentActivities = [
            [
                'icon' => 'plus',
                'color' => 'success',
                'title' => 'New Vehicle Added',
                'description' => 'Vehicle V001 has been added to the fleet',
                'time' => '2 hours ago'
            ],
            [
                'icon' => 'tools',
                'color' => 'warning',
                'title' => 'Maintenance Scheduled',
                'description' => 'Vehicle V002 scheduled for maintenance',
                'time' => '4 hours ago'
            ]
        ];

        $topVehicles = [
            [
                'vehicle_number' => 'V001',
                'brand' => 'Tata',
                'model' => 'Starbus',
                'score' => 95
            ],
            [
                'vehicle_number' => 'V002',
                'brand' => 'Ashok Leyland',
                'model' => 'Viking',
                'score' => 88
            ]
        ];

        $maintenanceAlerts = [
            [
                'type' => 'warning',
                'icon' => 'exclamation-triangle',
                'title' => 'Insurance Expiring Soon',
                'message' => 'Vehicle V010 insurance expires in 15 days',
                'due_date' => 'Due: Dec 15, 2024'
            ]
        ];

        return view('admin.transport.vehicles.dashboard', compact('stats', 'recentActivities', 'topVehicles', 'maintenanceAlerts'));
    }

    /**
     * Get vehicle statistics.
     */
    public function getStatistics()
    {
        $schoolId = Auth::user()->school_id;
        
        $stats = [
            'total_vehicles' => TransportVehicle::where('school_id', $schoolId)->count(),
            'active_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('status', 'active')->count(),
            'maintenance_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('status', 'maintenance')->count(),
            'repair_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('status', 'repair')->count(),
            'available_vehicles' => TransportVehicle::where('school_id', $schoolId)->where('is_available', true)->count(),
            'total_capacity' => TransportVehicle::where('school_id', $schoolId)->sum('seating_capacity'),
            'total_distance' => TransportVehicle::where('school_id', $schoolId)->sum('total_distance_covered'),
            'vehicles_needing_maintenance' => TransportVehicle::where('school_id', $schoolId)
                ->where('next_maintenance_date', '<=', now()->addDays(30))
                ->count()
        ];

        return response()->json($stats);
    }

    /**
     * Bulk delete vehicles.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:transport_vehicles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data.'
            ], 400);
        }

        try {
            $vehicles = TransportVehicle::whereIn('id', $request->ids);
            $vehicles->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicles deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error performing bulk delete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting vehicles. Please try again.'
            ], 500);
        }
    }

    /**
     * Bulk actions for vehicles.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete,maintenance',
            'vehicle_ids' => 'required|array|min:1',
            'vehicle_ids.*' => 'exists:transport_vehicles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data.'
            ], 400);
        }

        try {
            $vehicles = TransportVehicle::whereIn('id', $request->vehicle_ids);
            
            switch ($request->action) {
                case 'activate':
                    $vehicles->update(['status' => 'active', 'is_active' => true, 'is_available' => true]);
                    $message = 'Vehicles activated successfully!';
                    break;
                case 'deactivate':
                    $vehicles->update(['status' => 'inactive', 'is_active' => false, 'is_available' => false]);
                    $message = 'Vehicles deactivated successfully!';
                    break;
                case 'maintenance':
                    $vehicles->update(['status' => 'maintenance', 'is_active' => false, 'is_available' => false]);
                    $message = 'Vehicles set to maintenance successfully!';
                    break;
                case 'delete':
                    $vehicles->delete();
                    $message = 'Vehicles deleted successfully!';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action. Please try again.'
            ], 500);
        }
    }
}

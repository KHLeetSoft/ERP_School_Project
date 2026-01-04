<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportDriver;
use App\Models\TransportVehicle;
use App\Models\TransportAssignment;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class TransportDriverController extends Controller
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
     * Display a listing of transport drivers
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $schoolId = Auth::user()->school_id ?? 1;
                
                $query = TransportDriver::with(['school', 'user', 'assignedVehicle'])
                    ->where('school_id', $schoolId);

                // Apply filters
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                if ($request->filled('license_type')) {
                    $query->where('license_type', $request->license_type);
                }

                if ($request->filled('experience_level')) {
                    $query->where('experience_level', $request->experience_level);
                }

                // Global search
                if ($request->filled('search.value')) {
                    $searchValue = $request->input('search.value');
                    $query->where(function($q) use ($searchValue) {
                        $q->where('name', 'like', "%{$searchValue}%")
                          ->orWhere('license_number', 'like', "%{$searchValue}%")
                          ->orWhere('phone', 'like', "%{$searchValue}%")
                          ->orWhere('email', 'like', "%{$searchValue}%")
                          ->orWhere('address', 'like', "%{$searchValue}%");
                    });
                }

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('driver_info', function ($data) {
                        return view('admin.transport.drivers.partials.driver-info', compact('data'))->render();
                    })
                    ->addColumn('contact_info', function ($data) {
                        return view('admin.transport.drivers.partials.contact-info', compact('data'))->render();
                    })
                    ->addColumn('license_info', function ($data) {
                        return view('admin.transport.drivers.partials.license-info', compact('data'))->render();
                    })
                    ->addColumn('vehicle_info', function ($data) {
                        return view('admin.transport.drivers.partials.vehicle-info', compact('data'))->render();
                    })
                    ->addColumn('status_badge', function ($data) {
                        return view('admin.transport.drivers.partials.status-badge', compact('data'))->render();
                    })
                    ->addColumn('actions', function ($data) {
                        return view('admin.transport.drivers.partials.actions', compact('data'))->render();
                    })
                    ->rawColumns(['driver_info', 'contact_info', 'license_info', 'vehicle_info', 'status_badge', 'actions'])
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
        $licenseTypes = ['light_motor', 'heavy_motor', 'commercial', 'passenger', 'special'];
        $experienceLevels = ['beginner', 'intermediate', 'experienced', 'expert'];
        $statuses = ['active', 'inactive', 'suspended', 'on_leave'];

        return view('admin.transport.drivers.index', compact('stats', 'licenseTypes', 'experienceLevels', 'statuses'));
    }

    /**
     * Show the form for creating a new transport driver
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id ?? 1;
        
        $vehicles = TransportVehicle::where('school_id', $schoolId)
            ->where('status', 'active')
            ->where('is_available', true)
            ->get();
            
        $users = User::where('school_id', $schoolId)
            ->whereDoesntHave('transportDriver')
            ->get();

        return view('admin.transport.drivers.create', compact('vehicles', 'users'));
    }

    /**
     * Store a newly created transport driver
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id|unique:transport_drivers,user_id',
            'license_number' => 'required|string|max:50|unique:transport_drivers,license_number',
            'license_type' => 'required|in:light_motor,heavy_motor,commercial,passenger,special',
            'license_expiry_date' => 'required|date|after:today',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date|before:-18 years',
            'date_of_joining' => 'required|date|before_or_equal:today',
            'experience_level' => 'required|in:beginner,intermediate,experienced,expert',
            'years_of_experience' => 'required|integer|min:0|max:50',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relation' => 'required|string|max:100',
            'vehicle_id' => 'nullable|exists:transport_vehicles,id',
            'status' => 'required|in:active,inactive,suspended,on_leave',
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

            $driver = TransportDriver::create([
                'school_id' => Auth::user()->school_id ?? 1,
                'user_id' => $request->user_id,
                'name' => $request->name,
                'license_number' => $request->license_number,
                'license_type' => $request->license_type,
                'license_expiry_date' => $request->license_expiry_date,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'date_of_joining' => $request->date_of_joining,
                'experience_level' => $request->experience_level,
                'years_of_experience' => $request->years_of_experience,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'vehicle_id' => $request->vehicle_id,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            // Update vehicle assignment if provided
            if ($request->vehicle_id) {
                TransportVehicle::where('id', $request->vehicle_id)
                    ->update(['driver_id' => $driver->id]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Driver created successfully!',
                'redirect' => route('admin.transport.drivers.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating driver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified transport driver
     */
    public function show($id)
    {
        $driver = TransportDriver::with(['school', 'user', 'assignedVehicle', 'assignments'])
            ->findOrFail($id);

        return view('admin.transport.drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified transport driver
     */
    public function edit($id)
    {
        $driver = TransportDriver::findOrFail($id);
        $schoolId = Auth::user()->school_id ?? 1;
        
        $vehicles = TransportVehicle::where('school_id', $schoolId)
            ->where('status', 'active')
            ->get();
            
        $users = User::where('school_id', $schoolId)
            ->where(function($query) use ($driver) {
                $query->whereDoesntHave('transportDriver')
                      ->orWhere('id', $driver->user_id);
            })
            ->get();

        return view('admin.transport.drivers.edit', compact('driver', 'vehicles', 'users'));
    }

    /**
     * Update the specified transport driver
     */
    public function update(Request $request, $id)
    {
        $driver = TransportDriver::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id|unique:transport_drivers,user_id,' . $id,
            'license_number' => 'required|string|max:50|unique:transport_drivers,license_number,' . $id,
            'license_type' => 'required|in:light_motor,heavy_motor,commercial,passenger,special',
            'license_expiry_date' => 'required|date',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date|before:-18 years',
            'date_of_joining' => 'required|date',
            'experience_level' => 'required|in:beginner,intermediate,experienced,expert',
            'years_of_experience' => 'required|integer|min:0|max:50',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relation' => 'required|string|max:100',
            'vehicle_id' => 'nullable|exists:transport_vehicles,id',
            'status' => 'required|in:active,inactive,suspended,on_leave',
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

            // Remove previous vehicle assignment
            if ($driver->vehicle_id) {
                TransportVehicle::where('id', $driver->vehicle_id)
                    ->update(['driver_id' => null]);
            }

            $driver->update([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'license_number' => $request->license_number,
                'license_type' => $request->license_type,
                'license_expiry_date' => $request->license_expiry_date,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'date_of_joining' => $request->date_of_joining,
                'experience_level' => $request->experience_level,
                'years_of_experience' => $request->years_of_experience,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'vehicle_id' => $request->vehicle_id,
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => Auth::id(),
            ]);

            // Update new vehicle assignment
            if ($request->vehicle_id) {
                TransportVehicle::where('id', $request->vehicle_id)
                    ->update(['driver_id' => $driver->id]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Driver updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating driver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified transport driver
     */
    public function destroy($id)
    {
        try {
            $driver = TransportDriver::findOrFail($id);

            // Check if driver has active assignments
            $activeAssignments = TransportAssignment::where('driver_id', $driver->user_id)
                ->whereIn('status', ['active', 'pending'])
                ->count();

            if ($activeAssignments > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete driver with active assignments!'
                ], 422);
            }

            // Remove vehicle assignment
            if ($driver->vehicle_id) {
                TransportVehicle::where('id', $driver->vehicle_id)
                    ->update(['driver_id' => null]);
            }

            $driver->delete();

            return response()->json([
                'success' => true,
                'message' => 'Driver deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting driver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle driver status
     */
    public function toggleStatus($id)
    {
        try {
            $driver = TransportDriver::findOrFail($id);
            
            $newStatus = $driver->status === 'active' ? 'inactive' : 'active';
            $driver->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => "Driver status updated to {$newStatus}!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate driver
     */
    public function duplicate($id)
    {
        try {
            $originalDriver = TransportDriver::findOrFail($id);
            
            $newDriver = $originalDriver->replicate();
            $newDriver->name = $originalDriver->name . ' (Copy)';
            $newDriver->license_number = $originalDriver->license_number . '_COPY';
            $newDriver->status = 'inactive';
            $newDriver->vehicle_id = null;
            $newDriver->created_by = Auth::id();
            $newDriver->save();

            return response()->json([
                'success' => true,
                'message' => 'Driver duplicated successfully!',
                'redirect' => route('admin.transport.drivers.edit', $newDriver->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating driver: ' . $e->getMessage()
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
            'total_drivers' => TransportDriver::where('school_id', $schoolId)->count(),
            'active_drivers' => TransportDriver::where('school_id', $schoolId)->where('status', 'active')->count(),
            'inactive_drivers' => TransportDriver::where('school_id', $schoolId)->where('status', 'inactive')->count(),
            'suspended_drivers' => TransportDriver::where('school_id', $schoolId)->where('status', 'suspended')->count(),
            'on_leave_drivers' => TransportDriver::where('school_id', $schoolId)->where('status', 'on_leave')->count(),
            'assigned_drivers' => TransportDriver::where('school_id', $schoolId)->whereNotNull('vehicle_id')->count(),
            'unassigned_drivers' => TransportDriver::where('school_id', $schoolId)->whereNull('vehicle_id')->count(),
            'expiring_licenses' => TransportDriver::where('school_id', $schoolId)
                ->where('license_expiry_date', '<=', now()->addDays(30))
                ->where('license_expiry_date', '>', now())
                ->count(),
            'expired_licenses' => TransportDriver::where('school_id', $schoolId)
                ->where('license_expiry_date', '<', now())
                ->count(),
        ];

        // Calculate utilization percentage
        $totalDrivers = $stats['total_drivers'];
        $assignedDrivers = $stats['assigned_drivers'];
        $utilizationPercentage = $totalDrivers > 0 ? round(($assignedDrivers / $totalDrivers) * 100, 1) : 0;
        $stats['utilization_percentage'] = $utilizationPercentage;

        // Monthly data for charts
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = TransportDriver::where('school_id', $schoolId)
                ->whereMonth('date_of_joining', $i)
                ->whereYear('date_of_joining', now()->year)
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
        
        // Recent drivers
        $recentDrivers = TransportDriver::where('school_id', $schoolId)
            ->with(['assignedVehicle'])
            ->latest()
            ->take(10)
            ->get();

        // Expiring licenses
        $expiringLicenses = TransportDriver::where('school_id', $schoolId)
            ->where('license_expiry_date', '<=', now()->addDays(30))
            ->where('license_expiry_date', '>', now())
            ->orderBy('license_expiry_date')
            ->take(10)
            ->get();

        return view('admin.transport.drivers.dashboard', compact('stats', 'recentDrivers', 'expiringLicenses'));
    }

    /**
     * Bulk action
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,suspend,delete',
            'drivers' => 'required|array|min:1',
            'drivers.*' => 'exists:transport_drivers,id'
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

            $drivers = TransportDriver::whereIn('id', $request->drivers)->get();
            $count = 0;

            foreach ($drivers as $driver) {
                switch ($request->action) {
                    case 'activate':
                        $driver->update(['status' => 'active']);
                        break;
                    case 'deactivate':
                        $driver->update(['status' => 'inactive']);
                        break;
                    case 'suspend':
                        $driver->update(['status' => 'suspended']);
                        break;
                    case 'delete':
                        // Check for active assignments
                        $activeAssignments = TransportAssignment::where('driver_id', $driver->user_id)
                            ->whereIn('status', ['active', 'pending'])
                            ->count();

                        if ($activeAssignments === 0) {
                            // Remove vehicle assignment
                            if ($driver->vehicle_id) {
                                TransportVehicle::where('id', $driver->vehicle_id)
                                    ->update(['driver_id' => null]);
                            }
                            $driver->delete();
                        }
                        break;
                }
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully {$request->action}d {$count} driver(s)!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ], 500);
        }
    }
}

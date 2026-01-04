<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportRoute;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TransportRouteController extends Controller
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
     * Display a listing of transport routes.
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        
        $query = TransportRoute::where('school_id', $schoolId);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('route_name', 'like', "%{$search}%")
                  ->orWhere('route_number', 'like', "%{$search}%")
                  ->orWhere('start_location', 'like', "%{$search}%")
                  ->orWhere('end_location', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by route type
        if ($request->filled('route_type')) {
            $query->where('route_type', $request->route_type);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $routes = $query->paginate(15);

        // Statistics for dashboard
        $stats = [
            'total_routes' => TransportRoute::where('school_id', $schoolId)->count(),
            'active_routes' => TransportRoute::where('school_id', $schoolId)->where('status', 'active')->where('is_active', true)->count(),
            'inactive_routes' => TransportRoute::where('school_id', $schoolId)->where('status', 'inactive')->count(),
            'maintenance_routes' => TransportRoute::where('school_id', $schoolId)->where('status', 'maintenance')->count(),
        ];

        return view('admin.transport.tproutes.index', compact('routes', 'stats'));
    }

    /**
     * Show the form for creating a new transport route.
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
            'suspended' => 'Suspended'
        ];

        $routeTypes = [
            'regular' => 'Regular',
            'express' => 'Express',
            'special' => 'Special',
            'school' => 'School',
            'college' => 'College'
        ];

        return view('admin.transport.tproutes.create', compact('statuses', 'routeTypes'));
    }

    /**
     * Store a newly created transport route.
     */
    public function store(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        
        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255',
            'route_number' => 'required|string|max:50|unique:transport_routes,route_number',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'total_distance' => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:0',
            'vehicle_capacity' => 'required|integer|min:1',
            'route_type' => 'required|in:regular,express,special,school,college',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance,suspended'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $route = TransportRoute::create([
                'school_id' => $schoolId,
                'route_name' => $request->route_name,
                'route_number' => $request->route_number,
                'start_location' => $request->start_location,
                'end_location' => $request->end_location,
                'total_distance' => $request->total_distance,
                'estimated_duration' => $request->estimated_duration,
                'vehicle_capacity' => $request->vehicle_capacity,
                'route_type' => $request->route_type,
                'description' => $request->description,
                'status' => $request->status,
                'is_active' => $request->status === 'active',
                'created_by' => Auth::id()
            ]);

            return redirect()->route('admin.transport.tproutes.index')
                ->with('success', 'Transport route created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating transport route: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating transport route. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified transport route.
     */
    public function show(TransportRoute $route)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('view', $route);
        
        return view('admin.transport.tproutes.show', compact('route'));
    }

    /**
     * Show the form for editing the specified transport route.
     */
    public function edit(TransportRoute $route)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('update', $route);
        
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
            'suspended' => 'Suspended'
        ];

        $routeTypes = [
            'regular' => 'Regular',
            'express' => 'Express',
            'special' => 'Special',
            'school' => 'School',
            'college' => 'College'
        ];

        return view('admin.transport.tproutes.edit', compact('route', 'statuses', 'routeTypes'));
    }

    /**
     * Update the specified transport route.
     */
    public function update(Request $request, TransportRoute $route)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('update', $route);
        
        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255',
            'route_number' => 'required|string|max:50|unique:transport_routes,route_number,' . $route->id,
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'total_distance' => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:0',
            'vehicle_capacity' => 'required|integer|min:1',
            'route_type' => 'required|in:regular,express,special,school,college',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance,suspended'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $route->update([
                'route_name' => $request->route_name,
                'route_number' => $request->route_number,
                'start_location' => $request->start_location,
                'end_location' => $request->end_location,
                'total_distance' => $request->total_distance,
                'estimated_duration' => $request->estimated_duration,
                'vehicle_capacity' => $request->vehicle_capacity,
                'route_type' => $request->route_type,
                'description' => $request->description,
                'status' => $request->status,
                'is_active' => $request->status === 'active',
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('admin.transport.tproutes.index')
                ->with('success', 'Transport route updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating transport route: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating transport route. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified transport route.
     */
    public function destroy(TransportRoute $route)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('delete', $route);
        
        try {
            $route->delete();
            return redirect()->route('admin.transport.tproutes.index')
                ->with('success', 'Transport route deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting transport route: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting transport route. Please try again.');
        }
    }

    /**
     * Toggle route status.
     */
    public function toggleStatus(TransportRoute $route)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('update', $route);
        
        try {
            $newStatus = $route->status === 'active' ? 'inactive' : 'active';
            $route->update([
                'status' => $newStatus,
                'is_active' => $newStatus === 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Route status updated successfully!',
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling route status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating route status. Please try again.'
            ], 500);
        }
    }

    /**
     * Duplicate a transport route.
     */
    public function duplicate(TransportRoute $route)
    {
        // Temporarily comment out authorization until policy is created
        // $this->authorize('create', TransportRoute::class);
        
        try {
            $newRoute = $route->replicate();
            $newRoute->route_name = $route->route_name . ' (Copy)';
            $newRoute->route_number = $route->route_number . '_copy';
            $newRoute->status = 'inactive';
            $newRoute->created_by = Auth::id();
            $newRoute->save();

            return response()->json([
                'success' => true,
                'message' => 'Route duplicated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error duplicating route: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating route. Please try again.'
            ], 500);
        }
    }

    /**
     * Get route statistics.
     */
    public function getStatistics()
    {
        $schoolId = Auth::user()->school_id;
        
        $stats = [
            'total_routes' => TransportRoute::where('school_id', $schoolId)->count(),
            'active_routes' => TransportRoute::where('school_id', $schoolId)->where('status', 'active')->count(),
            'inactive_routes' => TransportRoute::where('school_id', $schoolId)->where('status', 'inactive')->count(),
            'total_fare' => TransportRoute::where('school_id', $schoolId)->sum('fare'),
            'average_fare' => TransportRoute::where('school_id', $schoolId)->avg('fare'),
            'routes_by_status' => TransportRoute::where('school_id', $schoolId)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray()
        ];

        return response()->json($stats);
    }

    /**
     * Bulk actions for routes.
     */
    public function bulkAction(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $action = $request->input('action');
        $routeIds = $request->input('route_ids', []);

        if (empty($routeIds)) {
            return redirect()->back()->with('error', 'Please select routes to perform the action.');
        }

        $routes = TransportRoute::where('school_id', $schoolId)->whereIn('id', $routeIds);

        try {
            switch ($action) {
                case 'delete':
                    $routes->delete();
                    $message = 'Selected routes deleted successfully!';
                    break;

                case 'activate':
                    $routes->update(['status' => 'active']);
                    $message = 'Selected routes activated successfully!';
                    break;

                case 'deactivate':
                    $routes->update(['status' => 'inactive']);
                    $message = 'Selected routes deactivated successfully!';
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid action specified.');
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error performing bulk action. Please try again.');
        }
    }
}

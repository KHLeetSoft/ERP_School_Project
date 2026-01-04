<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentTransport;
use App\Models\StudentDetail;
use App\Models\TransportRoute;
use App\Models\TransportVehicle;
use App\Models\SchoolClass;
use Yajra\DataTables\Facades\DataTables;

class StudentTransportController extends Controller
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
            $query = StudentTransport::with(['student.user', 'route', 'vehicle', 'schoolClass'])->latest();

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->filled('route_id')) {
                $query->where('route_id', $request->route_id);
            }
            if ($request->filled('vehicle_id')) {
                $query->where('vehicle_id', $request->vehicle_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    $student = $row->student;
                    $name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                    return e($name ?: ($student->user->name ?? '-'));
                })
                ->addColumn('class_name', fn($row) => e($row->schoolClass->name ?? '-'))
                ->addColumn('route', fn($row) => e($row->route->name ?? '-'))
                ->addColumn('vehicle', fn($row) => e($row->vehicle->vehicle_no ?? '-'))
                ->addColumn('fare', fn($row) => number_format($row->fare, 2))
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.students.transport.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.transport.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-transport" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        $routes = TransportRoute::all();
        $vehicles = TransportVehicle::all();
        return view('admin.students.transport.index', compact('classes', 'students', 'routes', 'vehicles'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        $routes = TransportRoute::all();
        $vehicles = TransportVehicle::all();
        return view('admin.students.transport.create', compact('classes', 'students', 'routes', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'route_id' => 'nullable|exists:transport_routes,id',
            'vehicle_id' => 'nullable|exists:transport_vehicles,id',
            'pickup_point' => 'nullable|string|max:255',
            'drop_point' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'fare' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['school_id'] = auth()->user()->school_id ?? null;
        StudentTransport::create($data);
        return redirect()->route('admin.students.transport.index')->with('success', 'Transport assignment created.');
    }

    public function show($id)
    {
        $record = StudentTransport::with(['student.user','route','vehicle','schoolClass'])->findOrFail($id);
        return view('admin.students.transport.show', compact('record'));
    }

    public function edit($id)
    {
        $record = StudentTransport::findOrFail($id);
        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        $routes = TransportRoute::all();
        $vehicles = TransportVehicle::all();
        return view('admin.students.transport.edit', compact('record','classes','students','routes','vehicles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'route_id' => 'nullable|exists:transport_routes,id',
            'vehicle_id' => 'nullable|exists:transport_vehicles,id',
            'pickup_point' => 'nullable|string|max:255',
            'drop_point' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'fare' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
        ]);

        $record = StudentTransport::findOrFail($id);
        $record->update($request->all());
        return redirect()->route('admin.students.transport.index')->with('success', 'Transport assignment updated.');
    }

    public function destroy($id)
    {
        StudentTransport::findOrFail($id)->delete();
        return response()->json(['message' => 'Transport assignment deleted.']);
    }
}



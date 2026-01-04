<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Exports\StaffExport;
use App\Imports\StaffImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkrole:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeSchool();

        $query = Staff::with(['class', 'createdBy', 'updatedBy'])
            ->where('school_id', auth()->user()->school_id ?? 1);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $staff = $query->orderBy('created_at', 'desc')->paginate(25);

        $departments = Staff::where('school_id', auth()->user()->school_id ?? 1)
            ->distinct()->pluck('department')->filter();
        
        $designations = Staff::where('school_id', auth()->user()->school_id ?? 1)
            ->distinct()->pluck('designation')->filter();

        return view('admin.hr.staff.index', compact('staff', 'departments', 'designations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeSchool();

        $classes = SchoolClass::where('school_id', auth()->user()->school_id ?? 1)
            ->orderBy('name')->get();

        $departments = [
            'Administration', 'Academic', 'IT', 'Finance', 'Human Resources', 
            'Marketing', 'Operations', 'Research', 'Student Services', 'Facilities'
        ];

        $designations = [
            'Principal', 'Vice Principal', 'Head of Department', 'Teacher', 
            'Administrative Staff', 'IT Staff', 'Accountant', 'Receptionist', 
            'Librarian', 'Maintenance Staff', 'Security Staff'
        ];

        $employmentTypes = [
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Contract',
            'intern' => 'Intern'
        ];

        $genders = [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other'
        ];

        return view('admin.hr.staff.create', compact('classes', 'departments', 'designations', 'employmentTypes', 'genders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeSchool();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'employment_type' => 'required|in:full-time,part-time,contract,intern',
            'hire_date' => 'required|date|before_or_equal:today',
            'salary' => 'required|numeric|min:0',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|in:active,inactive,on_leave,terminated',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['school_id'] = auth()->user()->school_id ?? 1;
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            // Generate employee ID if not provided
            if (empty($data['employee_id'])) {
                $data['employee_id'] = $this->generateEmployeeId();
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/staff/photos', $photoName);
                $data['photo'] = $photoName;
            }

            $staff = Staff::create($data);

            DB::commit();

            return redirect()->route('admin.hr.staff.index')
                ->with('success', 'Staff member created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating staff member: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        $this->authorizeSchool();
        $this->authorize('view', $staff);

        $staff->load(['class', 'createdBy', 'updatedBy']);

        return view('admin.hr.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $this->authorizeSchool();
        $this->authorize('update', $staff);

        $classes = SchoolClass::where('school_id', auth()->user()->school_id ?? 1)
            ->orderBy('name')->get();

        $departments = [
            'Administration', 'Academic', 'IT', 'Finance', 'Human Resources', 
            'Marketing', 'Operations', 'Research', 'Student Services', 'Facilities'
        ];

        $designations = [
            'Principal', 'Vice Principal', 'Head of Department', 'Teacher', 
            'Administrative Staff', 'IT Staff', 'Accountant', 'Receptionist', 
            'Librarian', 'Maintenance Staff', 'Security Staff'
        ];

        $employmentTypes = [
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Contract',
            'intern' => 'Intern'
        ];

        $genders = [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other'
        ];

        return view('admin.hr.staff.edit', compact('staff', 'classes', 'departments', 'designations', 'employmentTypes', 'genders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $this->authorizeSchool();
        $this->authorize('update', $staff);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('staff')->ignore($staff->id)],
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'employment_type' => 'required|in:full-time,part-time,contract,intern',
            'hire_date' => 'required|date|before_or_equal:today',
            'salary' => 'required|numeric|min:0',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'class_id' => 'nullable|exists:school_classes,id',
            'status' => 'required|in:active,inactive,on_leave,terminated',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['updated_by'] = auth()->id();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($staff->photo && Storage::exists('public/staff/photos/' . $staff->photo)) {
                    Storage::delete('public/staff/photos/' . $staff->photo);
                }

                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/staff/photos', $photoName);
                $data['photo'] = $photoName;
            }

            $staff->update($data);

            DB::commit();

            return redirect()->route('admin.hr.staff.index')
                ->with('success', 'Staff member updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating staff member: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $this->authorizeSchool();
        $this->authorize('delete', $staff);

        try {
            // Delete photo if exists
            if ($staff->photo && Storage::exists('public/staff/photos/' . $staff->photo)) {
                Storage::delete('public/staff/photos/' . $staff->photo);
            }

            $staff->delete();

            return redirect()->route('admin.hr.staff.index')
                ->with('success', 'Staff member deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting staff member: ' . $e->getMessage());
        }
    }

    /**
     * Display the staff dashboard with statistics and charts.
     */
    public function dashboard()
    {
        $this->authorizeSchool();

        $schoolId = auth()->user()->school_id ?? 1;

        // Basic counts
        $totalStaff = Staff::where('school_id', $schoolId)->count();
        $activeStaff = Staff::where('school_id', $schoolId)->where('status', 'active')->count();
        $onLeaveStaff = Staff::where('school_id', $schoolId)->where('status', 'on_leave')->count();
        $terminatedStaff = Staff::where('school_id', $schoolId)->where('status', 'terminated')->count();

        // Department distribution
        $departmentStats = Staff::where('school_id', $schoolId)
            ->selectRaw('department, COUNT(*) as count')
            ->groupBy('department')
            ->orderBy('count', 'desc')
            ->get();

        // Designation distribution
        $designationStats = Staff::where('school_id', $schoolId)
            ->selectRaw('designation, COUNT(*) as count')
            ->groupBy('designation')
            ->orderBy('count', 'desc')
            ->get();

        // Employment type distribution
        $employmentTypeStats = Staff::where('school_id', $schoolId)
            ->selectRaw('employment_type, COUNT(*) as count')
            ->groupBy('employment_type')
            ->orderBy('count', 'desc')
            ->get();

        // Gender distribution
        $genderStats = Staff::where('school_id', $schoolId)
            ->selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->orderBy('count', 'desc')
            ->get();

        // Salary statistics
        $salaryStats = Staff::where('school_id', $schoolId)
            ->selectRaw('
                AVG(salary) as avg_salary,
                MIN(salary) as min_salary,
                MAX(salary) as max_salary,
                SUM(salary) as total_salary
            ')
            ->first();

        // Monthly hiring trend (last 12 months)
        $monthlyHiring = Staff::where('school_id', $schoolId)
            ->where('hire_date', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(hire_date, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Age distribution
        $ageRanges = [
            '18-25' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 25')
                ->count(),
            '26-35' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 35')
                ->count(),
            '36-45' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 45')
                ->count(),
            '46-55' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 46 AND 55')
                ->count(),
            '56+' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 56')
                ->count(),
        ];

        // Experience distribution
        $experienceRanges = [
            '0-2 years' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 0 AND 2')
                ->count(),
            '3-5 years' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 3 AND 5')
                ->count(),
            '6-10 years' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 6 AND 10')
                ->count(),
            '11-15 years' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 11 AND 15')
                ->count(),
            '15+ years' => Staff::where('school_id', $schoolId)
                ->whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) >= 15')
                ->count(),
        ];

        // Top departments by salary
        $topDepartmentsBySalary = Staff::where('school_id', $schoolId)
            ->selectRaw('department, AVG(salary) as avg_salary, COUNT(*) as staff_count')
            ->groupBy('department')
            ->orderBy('avg_salary', 'desc')
            ->limit(10)
            ->get();

        // Recent hires (last 30 days)
        $recentHires = Staff::where('school_id', $schoolId)
            ->where('hire_date', '>=', Carbon::now()->subDays(30))
            ->orderBy('hire_date', 'desc')
            ->limit(10)
            ->get();

        // Staff turnover rate (last 12 months)
        $currentYear = Carbon::now()->year;
        $staffAtStartOfYear = Staff::where('school_id', $schoolId)
            ->where('hire_date', '<', Carbon::createFromDate($currentYear, 1, 1))
            ->count();
        
        $staffLeftThisYear = Staff::where('school_id', $schoolId)
            ->where('status', 'terminated')
            ->whereYear('updated_at', $currentYear)
            ->count();

        $turnoverRate = $staffAtStartOfYear > 0 ? ($staffLeftThisYear / $staffAtStartOfYear) * 100 : 0;

        return view('admin.hr.staff.dashboard', compact(
            'totalStaff',
            'activeStaff',
            'onLeaveStaff',
            'terminatedStaff',
            'departmentStats',
            'designationStats',
            'employmentTypeStats',
            'genderStats',
            'salaryStats',
            'monthlyHiring',
            'ageRanges',
            'experienceRanges',
            'topDepartmentsBySalary',
            'recentHires',
            'turnoverRate'
        ));
    }

    /**
     * Export staff data to Excel.
     */
    public function export(Request $request)
    {
        $this->authorizeSchool();

        $format = $request->get('format', 'xlsx');
        $filename = 'staff_' . date('Y-m-d_H-i-s');

        if ($format === 'csv') {
            return Excel::download(new StaffExport, $filename . '.csv');
        }

        return Excel::download(new StaffExport, $filename . '.xlsx');
    }

    /**
     * Import staff data from Excel.
     */
    public function import(Request $request)
    {
        $this->authorizeSchool();

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new StaffImport, $request->file('file'));

            return redirect()->route('admin.hr.staff.index')
                ->with('success', 'Staff data imported successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing staff data: ' . $e->getMessage());
        }
    }

    /**
     * Toggle staff status.
     */
    public function toggleStatus(Staff $staff)
    {
        $this->authorizeSchool();
        $this->authorize('update', $staff);

        $newStatus = $staff->status === 'active' ? 'inactive' : 'active';
        $staff->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $newStatus
        ]);
    }

    /**
     * Get staff by department.
     */
    public function getByDepartment(Request $request)
    {
        $this->authorizeSchool();

        $department = $request->get('department');
        
        $staff = Staff::where('school_id', auth()->user()->school_id ?? 1)
            ->where('department', $department)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'designation', 'email']);

        return response()->json($staff);
    }

    /**
     * Get staff by designation.
     */
    public function getByDesignation(Request $request)
    {
        $this->authorizeSchool();

        $designation = $request->get('designation');
        
        $staff = Staff::where('school_id', auth()->user()->school_id ?? 1)
            ->where('designation', $designation)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'department', 'email']);

        return response()->json($staff);
    }

    /**
     * Generate unique employee ID.
     */
    private function generateEmployeeId()
    {
        $prefix = 'EMP';
        $year = date('Y');
        $month = date('m');
        
        $lastStaff = Staff::where('school_id', auth()->user()->school_id ?? 1)
            ->where('employee_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('employee_id', 'desc')
            ->first();

        if ($lastStaff) {
            $lastNumber = (int) substr($lastStaff->employee_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Authorize school access.
     */
    private function authorizeSchool()
    {
        if (!auth()->user()->school_id) {
            abort(403, 'School access not authorized.');
        }
    }
}

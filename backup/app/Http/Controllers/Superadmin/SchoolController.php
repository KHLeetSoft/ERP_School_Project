<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::with('admin')->latest()->paginate(10);
        return view('superadmin.schools.index', compact('schools'));
    }

    public function create()
    {
        $availableAdmins = User::whereDoesntHave('managedSchool')->get();
        return view('superadmin.schools.create', compact('availableAdmins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:schools,email',
            'website' => 'nullable|url',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'logo_position' => 'nullable|in:left,center,right',
        ]);

        // Create admin user first
        $admin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
        ]);

        // Assign admin role
        $admin->assignRole('admin');

        // Create school first
        $school = School::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'admin_id' => $admin->id,
            'theme_settings' => [
                'primary_color' => $request->primary_color ?? '#007bff',
                'secondary_color' => $request->secondary_color ?? '#6c757d',
                'logo_position' => $request->logo_position ?? 'left',
            ]
        ]);

        // Create school folder structure
        $fileManager = new FileManagerService();
        $folderStructure = $fileManager->createSchoolFolderStructure($school->id, $school->name);

        // Handle logo upload to school's logo folder
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $fileManager->uploadToSchoolFolder($school->id, 'logo', $request->file('logo'));
            $school->update(['logo' => $logoPath]);
        }

        // Update admin's school_id
        $admin->update(['school_id' => $school->id]);

        return redirect()->route('superadmin.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function show(School $school)
    {
        $school->load('admin', 'users');
        return view('superadmin.schools.show', compact('school'));
    }

    public function edit(School $school)
    {
        $availableAdmins = User::whereDoesntHave('managedSchool')
            ->orWhere('id', $school->admin_id)
            ->get();
        return view('superadmin.schools.edit', compact('school', 'availableAdmins'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:schools,email,' . $school->id,
            'website' => 'nullable|url',
            'admin_id' => 'nullable|exists:users,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'logo_position' => 'nullable|in:left,center,right',
            'status' => 'boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($school->logo) {
                Storage::disk('public')->delete($school->logo);
            }
            
            // Upload to school's logo folder
            $fileManager = new FileManagerService();
            $logoPath = $fileManager->uploadToSchoolFolder($school->id, 'logo', $request->file('logo'));
            $school->logo = $logoPath;
        }

        // Update school
        $school->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'admin_id' => $request->admin_id,
            'status' => $request->has('status'),
            'theme_settings' => [
                'primary_color' => $request->primary_color ?? '#007bff',
                'secondary_color' => $request->secondary_color ?? '#6c757d',
                'logo_position' => $request->logo_position ?? 'left',
            ]
        ]);

        // Update admin's school_id if admin changed
        if ($request->admin_id && $request->admin_id != $school->getOriginal('admin_id')) {
            // Remove old admin's school association
            if ($school->getOriginal('admin_id')) {
                User::where('id', $school->getOriginal('admin_id'))->update(['school_id' => null]);
            }
            // Set new admin's school association
            User::where('id', $request->admin_id)->update(['school_id' => $school->id]);
        }

        return redirect()->route('superadmin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        // Delete school folder and all contents
        $fileManager = new FileManagerService();
        $fileManager->deleteSchoolFolder($school->id);

        // Remove admin association
        if ($school->admin) {
            $school->admin->update(['school_id' => null]);
        }

        $school->delete();

        return redirect()->route('superadmin.schools.index')
            ->with('success', 'School deleted successfully.');
    }

    public function users(School $school)
    {
        $users = $school->users()->paginate(10);
        return view('superadmin.schools.users', compact('school', 'users'));
    }

    public function toggleStatus(School $school)
    {
        $school->update(['status' => !$school->status]);
        
        return response()->json([
            'success' => true,
            'status' => $school->status,
            'message' => 'School status updated successfully.'
        ]);
    }

    public function folderStructure(School $school)
    {
        $students = $school->users()->where('role_id', 6)->get();
        return view('superadmin.schools.folder-structure', compact('school', 'students'));
    }
}

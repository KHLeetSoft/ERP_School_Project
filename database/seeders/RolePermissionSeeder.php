<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles already exist, just get them
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $admin = Role::where('name', 'Admin')->first();
        $teacher = Role::where('name', 'Teacher')->first();
        $student = Role::where('name', 'Student')->first();
        $parent = Role::where('name', 'Parent')->first();
        $accountant = Role::where('name', 'Accountant')->first();
        $librarian = Role::where('name', 'Librarian')->first();

        // Create permissions using Spatie structure
        $permissions = [
            // Teacher permissions
            'teacher.view', 'teacher.create', 'teacher.edit', 'teacher.delete', 'teacher.export',
            // Student permissions
            'student.view', 'student.create', 'student.edit', 'student.delete', 'student.export',
            // Parent permissions
            'parent.view', 'parent.create', 'parent.edit', 'parent.delete', 'parent.export',
            // Accountant permissions
            'accountant.view', 'accountant.create', 'accountant.edit', 'accountant.delete', 'accountant.export',
            // Librarian permissions
            'librarian.view', 'librarian.create', 'librarian.edit', 'librarian.delete', 'librarian.export',
            // Payment permissions
            'payment.view', 'payment.create', 'payment.edit', 'payment.delete', 'payment.export', 'payment.approve',
            // Attendance permissions
            'attendance.view', 'attendance.create', 'attendance.edit', 'attendance.delete', 'attendance.export',
            // Exam permissions
            'exam.view', 'exam.create', 'exam.edit', 'exam.delete', 'exam.export',
            // Library permissions
            'library.view', 'library.create', 'library.edit', 'library.delete', 'library.export',
            // Transport permissions
            'transport.view', 'transport.create', 'transport.edit', 'transport.delete', 'transport.export',
            // Hostel permissions
            'hostel.view', 'hostel.create', 'hostel.edit', 'hostel.delete', 'hostel.export',
            // Report permissions
            'report.view', 'report.export',
            // Setting permissions
            'setting.view', 'setting.edit',
            // Role permissions
            'role.view', 'role.create', 'role.edit', 'role.delete'
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission],
                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ]
            );
        }

        // Assign permissions to roles
        $this->assignRolePermissions();

        // Create a super admin user if not exists
        $this->createSuperAdminUser();
    }

    private function assignRolePermissions()
    {
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $admin = Role::where('name', 'Admin')->first();
        $teacher = Role::where('name', 'Teacher')->first();
        $student = Role::where('name', 'Student')->first();
        $parent = Role::where('name', 'Parent')->first();
        $accountant = Role::where('name', 'Accountant')->first();
        $librarian = Role::where('name', 'Librarian')->first();

        // Super Admin gets all permissions
        if ($superAdmin) {
            $allPermissions = Permission::all();
            $superAdmin->syncPermissions($allPermissions->pluck('id')->toArray());
        }

        // Admin permissions
        if ($admin) {
            $adminPermissions = Permission::whereIn('name', [
                'teacher.view', 'teacher.create', 'teacher.edit', 'teacher.export',
                'student.view', 'student.create', 'student.edit', 'student.export',
                'parent.view', 'parent.create', 'parent.edit', 'parent.export',
                'accountant.view', 'accountant.create', 'accountant.edit', 'accountant.export',
                'librarian.view', 'librarian.create', 'librarian.edit', 'librarian.export',
                'attendance.view', 'attendance.create', 'attendance.edit', 'attendance.export',
                'exam.view', 'exam.create', 'exam.edit', 'exam.export',
                'library.view', 'library.create', 'library.edit', 'library.export',
                'transport.view', 'transport.create', 'transport.edit', 'transport.export',
                'hostel.view', 'hostel.create', 'hostel.edit', 'hostel.export',
                'report.view', 'report.export'
            ])->get();
            $admin->syncPermissions($adminPermissions->pluck('id')->toArray());
        }

        // Teacher permissions
        if ($teacher) {
            $teacherPermissions = Permission::whereIn('name', [
                'student.view', 'student.create', 'student.edit', 'student.export',
                'attendance.view', 'attendance.create', 'attendance.edit', 'attendance.export',
                'exam.view', 'exam.create', 'exam.edit', 'exam.export',
                'library.view', 'library.create', 'library.edit', 'library.export'
            ])->get();
            $teacher->syncPermissions($teacherPermissions->pluck('id')->toArray());
        }

        // Student permissions
        if ($student) {
            $studentPermissions = Permission::whereIn('name', [
                'student.view', 'attendance.view', 'exam.view', 'library.view'
            ])->get();
            $student->syncPermissions($studentPermissions->pluck('id')->toArray());
        }

        // Parent permissions
        if ($parent) {
            $parentPermissions = Permission::whereIn('name', [
                'student.view', 'attendance.view', 'exam.view', 'payment.view'
            ])->get();
            $parent->syncPermissions($parentPermissions->pluck('id')->toArray());
        }

        // Accountant permissions
        if ($accountant) {
            $accountantPermissions = Permission::whereIn('name', [
                'payment.view', 'payment.create', 'payment.edit', 'payment.export', 'payment.approve',
                'student.view', 'student.create', 'student.edit', 'student.export',
                'report.view', 'report.export'
            ])->get();
            $accountant->syncPermissions($accountantPermissions->pluck('id')->toArray());
        }

        // Librarian permissions
        if ($librarian) {
            $librarianPermissions = Permission::whereIn('name', [
                'library.view', 'library.create', 'library.edit', 'library.delete', 'library.export',
                'student.view', 'student.create', 'student.edit', 'student.export'
            ])->get();
            $librarian->syncPermissions($librarianPermissions->pluck('id')->toArray());
        }
    }

    private function createSuperAdminUser()
    {
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        
        if ($superAdminRole) {
            $superAdminUser = User::where('email', 'superadmin@example.com')->first();
            
            if (!$superAdminUser) {
                $superAdminUser = User::create([
                    'name' => 'Super Admin',
                    'email' => 'superadmin@example.com',
                    'password' => Hash::make('password'),
                    'role_id' => $superAdminRole->id,
                    'status' => true
                ]);
            }

            // Assign super admin role
            if (!$superAdminUser->roles()->where('role_id', $superAdminRole->id)->exists()) {
                $superAdminUser->roles()->attach($superAdminRole->id);
            }
        }
    }
}
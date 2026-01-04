<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Super Admin — no school_id or admin_id
        $superAdmin = User::create([
            'name'      => 'Super Admin',
            'email'     => 'superadmin@example.com',
            'password'  => Hash::make('superad@123'),
            'role_id'   => 1,   // Super Admin
            'status'    => true,
            'school_id' => null,
            'admin_id'  => null,
        ]);

        // 2) School Admin — under Super Admin, assigned to school #1
        $schoolAdmin = User::create([
            'name'      => 'School Admin',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('admin@123'),
            'role_id'   => 2,   // Admin
            'status'    => true,
            'school_id' => 1,   // your school record
            'admin_id'  => $superAdmin->id,
        ]);

        // 3) Teacher — under School Admin, same school
        User::create([
            'name'      => 'John Teacher',
            'email'     => 'teacher@example.com',
            'password'  => Hash::make('teacher@123'),
            'role_id'   => 3,   // Teacher
            'status'    => true,
            'school_id' => 1,
            'admin_id'  => $schoolAdmin->id,
        ]);

        // 4) Librarian
        User::create([
            'name'      => 'Librarian Lisa',
            'email'     => 'librarian@example.com',
            'password'  => Hash::make('librarian@123'),
            'role_id'   => 4,
            'status'    => true,
            'school_id' => 1,
            'admin_id'  => $schoolAdmin->id,
        ]);

        // 5) Accountant
        User::create([
            'name'      => 'Accountant Alex',
            'email'     => 'accountant@example.com',
            'password'  => Hash::make('accountant@123'),
            'role_id'   => 5,
            'status'    => true,
            'school_id' => 1,
            'admin_id'  => $schoolAdmin->id,
        ]);

        // 6) Student
        User::create([
            'name'      => 'Student Steve',
            'email'     => 'student@example.com',
            'password'  => Hash::make('student@123'),
            'role_id'   => 6,
            'status'    => true,
            'school_id' => 1,
            'admin_id'  => $schoolAdmin->id,
        ]);

        // 7) Parent
        User::create([
            'name'      => 'Parent Paula',
            'email'     => 'parent@example.com',
            'password'  => Hash::make('parent@123'),
            'role_id'   => 7,
            'status'    => true,
            'school_id' => 1,
            'admin_id'  => $schoolAdmin->id,
        ]);
    }
}

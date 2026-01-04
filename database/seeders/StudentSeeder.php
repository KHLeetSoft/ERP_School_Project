<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample student users (authentication through users table)
        $students = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@student.com',
                'admission_no' => 'STU001',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob.smith@student.com',
                'admission_no' => 'STU002',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie.brown@student.com',
                'admission_no' => 'STU003',
            ],
        ];

        foreach ($students as $studentData) {
            // Create user account for authentication
            $user = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password123'),
                'role_id' => 6, // Student role
                'status' => 1, // Active status
            ]);

            // Create basic student profile (only essential fields)
            $student = Student::create([
                'school_id' => 1, // Default school ID
                'admission_no' => $studentData['admission_no'],
                'first_name' => explode(' ', $studentData['name'])[0],
                'last_name' => explode(' ', $studentData['name'])[1],
                'email' => $studentData['email'],
                'gender' => 'male', // Default gender
                'status' => 'active',
                'user_id' => $user->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }

        $this->command->info('Sample student users created successfully!');
    }
}



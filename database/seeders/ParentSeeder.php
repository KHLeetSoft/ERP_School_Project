<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ParentDetails as ParentModel;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent users
        $parents = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'password' => Hash::make('password'),
                'role_id' => 7, // Parent role ID
                'status' => true,
                'phone' => '+1234567890',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'password' => Hash::make('password'),
                'role_id' => 7, // Parent role ID
                'status' => true,
                'phone' => '+1234567891',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@example.com',
                'password' => Hash::make('password'),
                'role_id' => 7, // Parent role ID
                'status' => true,
                'phone' => '+1234567892',
            ],
        ];

        foreach ($parents as $parentData) {
            // Create user
            $user = User::create($parentData);

            // Create parent details
            $parent = ParentModel::create([
                'user_id' => $user->id,
                'school_id' => 1, // Assuming school_id = 1
                'primary_contact_name' => $parentData['name'],
                'father_name' => $parentData['name'],
                'phone_primary' => $parentData['phone'],
                'email_primary' => $parentData['email'],
                'address' => '123 Main Street, City, State', // Default address
                'occupation_father' => 'Software Engineer',
                'income_range' => '50000-100000',
                'emergency_contact_name' => 'Emergency Contact',
                'emergency_contact_phone' => '+1234567899',
                'status' => 'active',
            ]);

            // Attach students to parent (assuming students exist)
            $students = Student::where('school_id', 1)->take(2)->get();
            foreach ($students as $student) {
                $parent->students()->attach($student->id, [
                    'relation' => 'father',
                    'notes' => 'Primary guardian'
                ]);
            }
        }

        $this->command->info('Parent users created successfully!');
    }
}

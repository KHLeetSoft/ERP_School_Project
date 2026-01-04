<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample teachers
        $teachers = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@school.com',
                'password' => Hash::make('password123'),
                'role_id' => 3, // Teacher role
                'status' => true,
            ],
            [
                'name' => 'Prof. Michael Chen',
                'email' => 'michael.chen@school.com',
                'password' => Hash::make('password123'),
                'role_id' => 3, // Teacher role
                'status' => true,
            ],
            [
                'name' => 'Ms. Emily Davis',
                'email' => 'emily.davis@school.com',
                'password' => Hash::make('password123'),
                'role_id' => 3, // Teacher role
                'status' => true,
            ],
        ];

        foreach ($teachers as $teacherData) {
            User::updateOrCreate(
                ['email' => $teacherData['email']],
                $teacherData
            );
        }

        $this->command->info('Teachers seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: sarah.johnson@school.com');
        $this->command->info('Password: password123');
    }
}
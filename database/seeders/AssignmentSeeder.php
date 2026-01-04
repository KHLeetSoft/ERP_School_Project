<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating sample assignments...');

        // Get teacher user
        $teacher = User::where('email', 'teacher@example.com')->first();
        if (!$teacher) {
            $this->command->warn('Teacher user not found. Please run UserSeeder first.');
            return;
        }

        // Get classes and sections
        $classes = SchoolClass::take(3)->get();
        $sections = Section::take(2)->get();

        if ($classes->isEmpty()) {
            $this->command->warn('Classes not found. Please run respective seeders first.');
            return;
        }

        // Use hardcoded subjects since subjects table might not exist
        $subjects = [
            ['id' => 1, 'name' => 'Mathematics'],
            ['id' => 2, 'name' => 'Science'],
            ['id' => 3, 'name' => 'English'],
            ['id' => 4, 'name' => 'History'],
            ['id' => 5, 'name' => 'Physics'],
            ['id' => 6, 'name' => 'Chemistry'],
        ];

        $assignments = [
            [
                'title' => 'Mathematics Problem Set 1',
                'description' => 'Solve the following algebraic equations and show your work step by step.',
                'priority' => 'high',
                'status' => 'pending',
                'max_marks' => 100,
                'passing_marks' => 40,
            ],
            [
                'title' => 'Science Project - Solar System',
                'description' => 'Create a model of the solar system with accurate distances and planet sizes.',
                'priority' => 'medium',
                'status' => 'submitted',
                'max_marks' => 150,
                'passing_marks' => 60,
            ],
            [
                'title' => 'English Essay - My Favorite Book',
                'description' => 'Write a 500-word essay about your favorite book and why you recommend it.',
                'priority' => 'medium',
                'status' => 'checked',
                'max_marks' => 80,
                'passing_marks' => 32,
            ],
            [
                'title' => 'History Research Paper',
                'description' => 'Research and write about the impact of World War II on modern society.',
                'priority' => 'high',
                'status' => 'pending',
                'max_marks' => 200,
                'passing_marks' => 80,
            ],
            [
                'title' => 'Physics Lab Report',
                'description' => 'Conduct experiments on simple harmonic motion and document your findings.',
                'priority' => 'medium',
                'status' => 'completed',
                'max_marks' => 120,
                'passing_marks' => 48,
            ],
            [
                'title' => 'Chemistry Quiz - Periodic Table',
                'description' => 'Answer questions about element properties and periodic trends.',
                'priority' => 'low',
                'status' => 'submitted',
                'max_marks' => 50,
                'passing_marks' => 20,
            ],
        ];

        foreach ($assignments as $index => $assignmentData) {
            $class = $classes[$index % $classes->count()];
            $section = $sections->count() > 0 ? $sections[$index % $sections->count()] : null;
            $subject = $subjects[$index % count($subjects)];

            Assignment::create([
                'school_id' => 1, // Default school ID
                'class_id' => $class->id,
                'section_id' => $section ? $section->id : null,
                'subject_id' => $subject['id'],
                'teacher_id' => $teacher->id,
                'title' => $assignmentData['title'],
                'description' => $assignmentData['description'],
                'file' => null,
                'assigned_date' => now()->subDays(rand(1, 10))->format('Y-m-d'),
                'due_date' => now()->addDays(rand(1, 14))->format('Y-m-d'),
                'priority' => $assignmentData['priority'],
                'status' => $assignmentData['status'],
                'max_marks' => $assignmentData['max_marks'],
                'passing_marks' => $assignmentData['passing_marks'],
            ]);
        }

        $this->command->info('Sample assignments created successfully!');
    }
}

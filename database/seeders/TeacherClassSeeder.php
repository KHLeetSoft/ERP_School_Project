<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeacherClass;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;

class TeacherClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teacher user
        $teacher = User::where('email', 'teacher@example.com')->first();
        
        if (!$teacher) {
            $this->command->warn('Teacher user not found. Please run UserSeeder first.');
            return;
        }

        // Create sample classes
        $classes = [
            [
                'teacher_id' => $teacher->id,
                'class_id' => null,
                'subject_id' => null,
                'class_name' => 'Grade 10A',
                'subject_name' => 'Mathematics',
                'room_number' => 'Room 101',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'day_of_week' => 'Monday',
                'total_students' => 25,
                'description' => 'Advanced Mathematics for Grade 10 students',
                'status' => 'active',
            ],
            [
                'teacher_id' => $teacher->id,
                'class_id' => null,
                'subject_id' => null,
                'class_name' => 'Grade 10A',
                'subject_name' => 'Mathematics',
                'room_number' => 'Room 101',
                'start_time' => '10:30:00',
                'end_time' => '11:30:00',
                'day_of_week' => 'Wednesday',
                'total_students' => 25,
                'description' => 'Advanced Mathematics for Grade 10 students',
                'status' => 'active',
            ],
            [
                'teacher_id' => $teacher->id,
                'class_id' => null,
                'subject_id' => null,
                'class_name' => 'Grade 11B',
                'subject_name' => 'Physics',
                'room_number' => 'Lab 2',
                'start_time' => '14:00:00',
                'end_time' => '15:00:00',
                'day_of_week' => 'Tuesday',
                'total_students' => 20,
                'description' => 'Physics Laboratory Session',
                'status' => 'active',
            ],
            [
                'teacher_id' => $teacher->id,
                'class_id' => null,
                'subject_id' => null,
                'class_name' => 'Grade 9C',
                'subject_name' => 'Mathematics',
                'room_number' => 'Room 105',
                'start_time' => '15:30:00',
                'end_time' => '16:30:00',
                'day_of_week' => 'Thursday',
                'total_students' => 30,
                'description' => 'Basic Mathematics for Grade 9 students',
                'status' => 'active',
            ],
            [
                'teacher_id' => $teacher->id,
                'class_id' => null,
                'subject_id' => null,
                'class_name' => 'Grade 12A',
                'subject_name' => 'Physics',
                'room_number' => 'Room 201',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'day_of_week' => 'Friday',
                'total_students' => 18,
                'description' => 'Advanced Physics for Grade 12 students',
                'status' => 'active',
            ],
        ];

        foreach ($classes as $classData) {
            TeacherClass::create($classData);
        }

        $this->command->info('Teacher classes seeded successfully!');
    }
}

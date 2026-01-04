<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\User;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating sample schedules...');

        // Get teacher user
        $teacher = User::where('email', 'teacher@example.com')->first();
        if (!$teacher) {
            $this->command->warn('Teacher user not found. Please run UserSeeder first.');
            return;
        }

        $schedules = [
            // Monday
            [
                'class_name' => 'Grade 10A',
                'subject_name' => 'Mathematics',
                'room_number' => 'Room 101',
                'day_of_week' => 'monday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'schedule_type' => 'regular',
                'description' => 'Algebra and Geometry',
            ],
            [
                'class_name' => 'Grade 11B',
                'subject_name' => 'Physics',
                'room_number' => 'Lab 201',
                'day_of_week' => 'monday',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'schedule_type' => 'regular',
                'description' => 'Mechanics and Thermodynamics',
            ],
            [
                'class_name' => 'Grade 9C',
                'subject_name' => 'Chemistry',
                'room_number' => 'Room 102',
                'day_of_week' => 'monday',
                'start_time' => '14:00',
                'end_time' => '15:00',
                'schedule_type' => 'regular',
                'description' => 'Organic Chemistry',
            ],

            // Tuesday
            [
                'class_name' => 'Grade 10A',
                'subject_name' => 'Mathematics',
                'room_number' => 'Room 101',
                'day_of_week' => 'tuesday',
                'start_time' => '09:00',
                'end_time' => '10:00',
                'schedule_type' => 'regular',
                'description' => 'Calculus and Trigonometry',
            ],
            [
                'class_name' => 'Grade 12A',
                'subject_name' => 'Advanced Mathematics',
                'room_number' => 'Room 103',
                'day_of_week' => 'tuesday',
                'start_time' => '11:00',
                'end_time' => '12:00',
                'schedule_type' => 'regular',
                'description' => 'Differential Equations',
            ],
            [
                'class_name' => 'Grade 11B',
                'subject_name' => 'Physics',
                'room_number' => 'Lab 201',
                'day_of_week' => 'tuesday',
                'start_time' => '15:00',
                'end_time' => '16:00',
                'schedule_type' => 'regular',
                'description' => 'Electromagnetism',
            ],

            // Wednesday
            [
                'class_name' => 'Grade 9C',
                'subject_name' => 'Chemistry',
                'room_number' => 'Lab 202',
                'day_of_week' => 'wednesday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'schedule_type' => 'regular',
                'description' => 'Practical Chemistry Lab',
            ],
            [
                'class_name' => 'Grade 10A',
                'subject_name' => 'Mathematics',
                'room_number' => 'Room 101',
                'day_of_week' => 'wednesday',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'schedule_type' => 'regular',
                'description' => 'Statistics and Probability',
            ],
            [
                'class_name' => 'Grade 11B',
                'subject_name' => 'Physics',
                'room_number' => 'Room 104',
                'day_of_week' => 'wednesday',
                'start_time' => '14:00',
                'end_time' => '15:00',
                'schedule_type' => 'regular',
                'description' => 'Waves and Oscillations',
            ],

            // Thursday
            [
                'class_name' => 'Grade 12A',
                'subject_name' => 'Advanced Mathematics',
                'room_number' => 'Room 103',
                'day_of_week' => 'thursday',
                'start_time' => '09:00',
                'end_time' => '10:00',
                'schedule_type' => 'regular',
                'description' => 'Linear Algebra',
            ],
            [
                'class_name' => 'Grade 9C',
                'subject_name' => 'Chemistry',
                'room_number' => 'Room 102',
                'day_of_week' => 'thursday',
                'start_time' => '11:00',
                'end_time' => '12:00',
                'schedule_type' => 'regular',
                'description' => 'Inorganic Chemistry',
            ],
            [
                'class_name' => 'Grade 10A',
                'subject_name' => 'Mathematics',
                'room_number' => 'Room 101',
                'day_of_week' => 'thursday',
                'start_time' => '15:00',
                'end_time' => '16:00',
                'schedule_type' => 'regular',
                'description' => 'Coordinate Geometry',
            ],

            // Friday
            [
                'class_name' => 'Grade 11B',
                'subject_name' => 'Physics',
                'room_number' => 'Lab 201',
                'day_of_week' => 'friday',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'schedule_type' => 'regular',
                'description' => 'Modern Physics',
            ],
            [
                'class_name' => 'Grade 12A',
                'subject_name' => 'Advanced Mathematics',
                'room_number' => 'Room 103',
                'day_of_week' => 'friday',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'schedule_type' => 'regular',
                'description' => 'Complex Analysis',
            ],
            [
                'class_name' => 'Grade 9C',
                'subject_name' => 'Chemistry',
                'room_number' => 'Room 102',
                'day_of_week' => 'friday',
                'start_time' => '14:00',
                'end_time' => '15:00',
                'schedule_type' => 'regular',
                'description' => 'Physical Chemistry',
            ],
        ];

        foreach ($schedules as $scheduleData) {
            Schedule::create(array_merge($scheduleData, [
                'teacher_id' => $teacher->id,
                'effective_from' => now()->startOfMonth(),
                'effective_until' => now()->endOfMonth()->addMonths(3),
                'is_active' => true,
            ]));
        }

        $this->command->info('Sample schedules created successfully!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timetable;

class TimetableSeeder extends Seeder
{
    public function run()
    {
        $timetables = [
            [
                'class_id' => 1,
                'section_id' => 1,
                'subject_id' => 1,
                'teacher_id' => 1,
                'day' => 'Monday',
                'start_time' => '08:00:00',
                'end_time' => '09:00:00',
                'room_number' => '101',
                'status' => 'active',
            ],
            // Add more sample data...
        ];

        foreach ($timetables as $timetable) {
            Timetable::create($timetable);
        }
    }
}
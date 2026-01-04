<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('academic_calendars')->insert([
            [
                'school_id' => 1,
                'title' => 'First Day of School',
                'description' => 'Opening day ceremony and orientation',
                'date' => Carbon::now()->addDays(10)->toDateString(),
                'start_time' => Carbon::now()->addDays(10)->setTime(9, 0),
                'end_time' => Carbon::now()->addDays(10)->setTime(11, 0),
                'status' => 'scheduled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'title' => 'Midterm Exams Begin',
                'description' => 'Midterm examinations week',
                'date' => Carbon::now()->addDays(30)->toDateString(),
                'start_time' => Carbon::now()->addDays(30)->setTime(8, 0),
                'end_time' => Carbon::now()->addDays(30)->setTime(15, 0),
                'status' => 'scheduled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}



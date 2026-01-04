<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamSchedule;
use App\Models\Exam;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ExamScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $exams = Exam::inRandomOrder()->take(5)->get();
        if ($exams->isEmpty()) return;

        foreach ($exams as $exam) {
            for ($i=0; $i<8; $i++) {
                $date = Carbon::parse($exam->start_date ?: now())->addDays($i);
                ExamSchedule::create([
                    'school_id' => $exam->school_id,
                    'exam_id' => $exam->id,
                    'class_name' => 'Class '.$faker->numberBetween(1,12),
                    'section_name' => $faker->randomElement(['A','B','C','D']),
                    'subject_name' => $faker->randomElement(['Math','Science','English','Social Science','Hindi','Computer']),
                    'exam_date' => $date->format('Y-m-d'),
                    'start_time' => '09:00:00',
                    'end_time' => '12:00:00',
                    'room_no' => (string) $faker->numberBetween(101, 120),
                    'max_marks' => 100,
                    'pass_marks' => 33,
                    'invigilator_name' => $faker->name(),
                    'status' => $faker->randomElement(['scheduled','completed','postponed']),
                    'notes' => $faker->optional()->sentence(6),
                ]);
            }
        }
    }
}



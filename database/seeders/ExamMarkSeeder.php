<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamMark;
use App\Models\Exam;
use Faker\Factory as Faker;

class ExamMarkSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $exams = Exam::inRandomOrder()->take(3)->get();
        if ($exams->isEmpty()) return;

        foreach ($exams as $exam) {
            for ($i=0; $i<50; $i++) {
                $max = 100; $obt = $faker->numberBetween(30, 100);
                $pct = round(($obt/$max)*100,2);
                $grade = $pct>=90?'A+':($pct>=75?'A':($pct>=60?'B':'C'));
                ExamMark::create([
                    'school_id' => $exam->school_id,
                    'exam_id' => $exam->id,
                    'class_name' => 'Class '.$faker->numberBetween(1,12),
                    'section_name' => $faker->randomElement(['A','B','C','D']),
                    'student_id' => null,
                    'student_name' => $faker->name(),
                    'admission_no' => (string)$faker->numerify('ADM####'),
                    'roll_no' => (string)$faker->numerify('##'),
                    'subject_name' => $faker->randomElement(['Math','Science','English','Hindi','Computer']),
                    'max_marks' => $max,
                    'obtained_marks' => $obt,
                    'percentage' => $pct,
                    'grade' => $grade,
                    'result_status' => $pct>=40?'pass':'fail',
                    'remarks' => $faker->optional()->sentence(6),
                    'status' => $faker->randomElement(['published','draft']),
                ]);
            }
        }
    }
}



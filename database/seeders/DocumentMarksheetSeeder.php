<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentMarksheet;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use App\Models\Section;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DocumentMarksheetSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $students = StudentDetail::inRandomOrder()->limit(20)->get();

        if ($students->isEmpty()) {
            $this->command?->info('No student_details found. Skipping DocumentMarksheetSeeder.');
            return;
        }

        foreach ($students as $student) {
            $className = $student->class_id ? (SchoolClass::find($student->class_id)->name ?? null) : null;
            $sectionName = $student->section_id ? (Section::find($student->section_id)->name ?? null) : null;

            $total = 500;
            $obtained = $faker->numberBetween(250, 500);
            $percentage = round(($obtained / $total) * 100, 2);
            $grade = $percentage >= 90 ? 'A+' : ($percentage >= 75 ? 'A' : ($percentage >= 60 ? 'B' : 'C'));

            DocumentMarksheet::create([
                'school_id' => $student->school_id,
                'student_id' => $student->id,
                'student_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: $faker->name(),
                'admission_no' => $student->admission_no,
                'roll_no' => (string) $faker->numerify('##'),
                'class_name' => $className,
                'section_name' => $sectionName,
                'exam_name' => $faker->randomElement(['Term Examination','Final Examination','Unit Test']),
                'term' => $faker->randomElement(['Term 1','Term 2','Annual']),
                'academic_year' => Carbon::now()->subYears(0)->format('Y') . '-' . Carbon::now()->addYear()->format('Y'),
                'ms_number' => strtoupper($faker->unique()->bothify('MS-####-??')),
                'issue_date' => Carbon::parse($faker->dateTimeBetween('-6 months', 'now'))->format('Y-m-d'),
                'total_marks' => $total,
                'obtained_marks' => $obtained,
                'percentage' => $percentage,
                'grade' => $grade,
                'result_status' => $faker->randomElement(['pass','fail']),
                'remarks' => $faker->optional()->sentence(6),
                'marks_json' => json_encode([
                    ['subject' => 'Math', 'marks' => $faker->numberBetween(50, 100)],
                    ['subject' => 'Science', 'marks' => $faker->numberBetween(50, 100)],
                    ['subject' => 'English', 'marks' => $faker->numberBetween(50, 100)],
                    ['subject' => 'Social Science', 'marks' => $faker->numberBetween(50, 100)],
                    ['subject' => 'Hindi', 'marks' => $faker->numberBetween(50, 100)],
                ]),
                'status' => $faker->randomElement(['issued','draft','cancelled']),
            ]);
        }
    }
}



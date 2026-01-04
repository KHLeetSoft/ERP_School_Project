<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentLeavingCertificate;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use App\Models\Section;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DocumentLeavingCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $students = StudentDetail::inRandomOrder()->limit(20)->get();

        if ($students->isEmpty()) {
            $this->command?->info('No student_details found. Skipping DocumentLeavingCertificateSeeder.');
            return;
        }

        foreach ($students as $student) {
            $className = $student->class_id ? (SchoolClass::find($student->class_id)->name ?? null) : null;
            $sectionName = $student->section_id ? (Section::find($student->section_id)->name ?? null) : null;

            DocumentLeavingCertificate::create([
                'school_id' => $student->school_id,
                'student_id' => $student->id,
                'student_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: $faker->name(),
                'admission_no' => $student->admission_no,
                'class_name' => $className,
                'section_name' => $sectionName,
                'date_of_birth' => $student->dob ?: $faker->date('Y-m-d', '2012-12-31'),
                'father_name' => $faker->name('male'),
                'mother_name' => $faker->name('female'),
                'reason_for_leaving' => $faker->randomElement(['Family relocation','Completed class','Personal reasons','Other']),
                'conduct' => $faker->randomElement(['Good','Very Good','Satisfactory']),
                'lc_number' => strtoupper($faker->unique()->bothify('LC-####-??')),
                'issue_date' => Carbon::parse($faker->dateTimeBetween('-1 years', 'now'))->format('Y-m-d'),
                'remarks' => $faker->optional()->sentence(6),
                'status' => $faker->randomElement(['issued','draft','cancelled']),
            ]);
        }
    }
}



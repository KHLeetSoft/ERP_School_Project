<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentBonafideCertificate;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use App\Models\Section;
use Faker\Factory as Faker;

class DocumentBonafideCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $students = StudentDetail::inRandomOrder()->limit(20)->get();

        if ($students->isEmpty()) {
            $this->command?->info('No student_details found. Skipping DocumentBonafideCertificateSeeder.');
            return;
        }

        foreach ($students as $student) {
            $className = null;
            if ($student->class_id) {
                $className = SchoolClass::find($student->class_id)->name ?? null;
            }

            $sectionName = null;
            if ($student->section_id) {
                $sectionName = Section::find($student->section_id)->name ?? null;
            }

            DocumentBonafideCertificate::create([
                'school_id' => $student->school_id,
                'student_id' => $student->id,
                'student_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: $faker->name(),
                'admission_no' => $student->admission_no,
                'class_name' => $className,
                'section_name' => $sectionName,
                'date_of_birth' => $student->dob,
                'father_name' => $faker->name('male'),
                'mother_name' => $faker->name('female'),
                'purpose' => $faker->randomElement(['Scholarship', 'Passport', 'Bank account', 'Other']),
                'bc_number' => strtoupper($faker->unique()->bothify('BC-####-??')),
                'issue_date' => $faker->date('Y-m-d'),
                'remarks' => $faker->optional()->sentence(6),
                'status' => $faker->randomElement(['issued','draft','cancelled']),
            ]);
        }
    }
}



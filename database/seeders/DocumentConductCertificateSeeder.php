<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentConductCertificate;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use App\Models\Section;
use Faker\Factory as Faker;

class DocumentConductCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $students = StudentDetail::inRandomOrder()->limit(20)->get();

        if ($students->isEmpty()) {
            $this->command?->info('No student_details found. Skipping DocumentConductCertificateSeeder.');
            return;
        }

        foreach ($students as $student) {
            $className = $student->class_id ? (SchoolClass::find($student->class_id)->name ?? null) : null;
            $sectionName = $student->section_id ? (Section::find($student->section_id)->name ?? null) : null;

            DocumentConductCertificate::create([
                'school_id' => $student->school_id,
                'student_id' => $student->id,
                'student_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: $faker->name(),
                'admission_no' => $student->admission_no,
                'roll_no' => (string) $faker->numerify('##'),
                'class_name' => $className,
                'section_name' => $sectionName,
                'date_of_birth' => $student->dob,
                'father_name' => $faker->name('male'),
                'mother_name' => $faker->name('female'),
                'conduct' => $faker->randomElement(['Excellent','Good','Satisfactory']),
                'cc_number' => strtoupper($faker->unique()->bothify('CC-####-??')),
                'issue_date' => $faker->date('Y-m-d'),
                'remarks' => $faker->optional()->sentence(6),
                'status' => $faker->randomElement(['issued','draft','cancelled']),
            ]);
        }
    }
}



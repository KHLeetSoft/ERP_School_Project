<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentTransferCertificate;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use App\Models\Section;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DocumentTransferCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $students = StudentDetail::inRandomOrder()->limit(20)->get();

        if ($students->isEmpty()) {
            $this->command?->info('No student_details found. Skipping DocumentTransferCertificateSeeder.');
            return;
        }

        foreach ($students as $student) {
            $admissionDate = Carbon::parse($faker->dateTimeBetween('-6 years', '-2 years'));
            $leavingDate = (clone $admissionDate)->addYears($faker->numberBetween(1, 4))->addDays($faker->numberBetween(0, 60));

            $className = null;
            if ($student->class_id) {
                $className = SchoolClass::find($student->class_id)->name ?? null;
            }

            $sectionName = null;
            if ($student->section_id) {
                $sectionName = Section::find($student->section_id)->name ?? null;
            }

            DocumentTransferCertificate::create([
                'school_id' => $student->school_id,
                'student_id' => $student->id,
                'student_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: $faker->name(),
                'admission_no' => $student->admission_no,
                'class_name' => $className,
                'section_name' => $sectionName,
                'date_of_birth' => $student->dob ?: $faker->date('Y-m-d', '2012-12-31'),
                'father_name' => $faker->name('male'),
                'mother_name' => $faker->name('female'),
                'admission_date' => $admissionDate->format('Y-m-d'),
                'leaving_date' => $leavingDate->format('Y-m-d'),
                'reason_for_leaving' => $faker->randomElement(['Family relocation', 'Completed class', 'Personal reasons', 'Other']),
                'conduct' => $faker->randomElement(['Good', 'Very Good', 'Satisfactory']),
                'tc_number' => strtoupper($faker->unique()->bothify('TC-####-??')),
                'issue_date' => $leavingDate->format('Y-m-d'),
                'remarks' => $faker->optional()->sentence(6),
                'status' => $faker->randomElement(['issued','draft','cancelled']),
            ]);
        }
    }
}




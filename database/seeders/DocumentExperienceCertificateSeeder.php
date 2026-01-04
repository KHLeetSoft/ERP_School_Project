<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentExperienceCertificate;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DocumentExperienceCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i=0; $i<20; $i++) {
            $join = Carbon::parse($faker->dateTimeBetween('-8 years', '-2 years'));
            $relieve = (clone $join)->addYears($faker->numberBetween(1,6))->addDays($faker->numberBetween(0,60));
            $diffYears = $join->diff($relieve);
            $experience = $diffYears->y.' Years '. $diffYears->m.' Months';

            DocumentExperienceCertificate::create([
                'school_id' => null,
                'employee_id' => $faker->numberBetween(1000, 9999),
                'employee_name' => $faker->name(),
                'designation' => $faker->randomElement(['Teacher','Accountant','Librarian','Clerk']),
                'department' => $faker->randomElement(['Academics','Accounts','Library','Administration']),
                'joining_date' => $join->format('Y-m-d'),
                'relieving_date' => $relieve->format('Y-m-d'),
                'total_experience' => $experience,
                'ec_number' => strtoupper($faker->unique()->bothify('EC-####-??')),
                'issue_date' => $relieve->format('Y-m-d'),
                'remarks' => $faker->optional()->sentence(6),
                'status' => $faker->randomElement(['issued','draft','cancelled']),
            ]);
        }
    }
}



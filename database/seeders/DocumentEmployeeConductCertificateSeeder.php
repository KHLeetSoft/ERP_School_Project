<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentEmployeeConductCertificate;
use Faker\Factory as Faker;

class DocumentEmployeeConductCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        for ($i=0; $i<20; $i++) {
            DocumentEmployeeConductCertificate::create([
                'school_id' => null,
                'employee_id' => $faker->numberBetween(1000, 9999),
                'employee_name' => $faker->name(),
                'designation' => $faker->randomElement(['Teacher','Accountant','Librarian','Clerk']),
                'department' => $faker->randomElement(['Academics','Accounts','Library','Administration']),
                'conduct' => $faker->randomElement(['Excellent','Good','Satisfactory']),
                'ecc_number' => strtoupper($faker->unique()->bothify('ECC-####-??')),
                'issue_date' => $faker->date('Y-m-d'),
                'remarks' => $faker->optional()->sentence(6),
                'status' => $faker->randomElement(['issued','draft','cancelled']),
            ]);
        }
    }
}



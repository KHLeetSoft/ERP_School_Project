<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdmissionEnquiry;
use Faker\Factory as Faker;

class AdmissionEnquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 25) as $i) {
            AdmissionEnquiry::create([
                'student_name'   => $faker->name,
                'parent_name'    => $faker->name,
                'contact_number' => $faker->phoneNumber,
                'email'          => $faker->optional()->safeEmail,
                'address'        => $faker->optional()->address,
                'class'          => $faker->randomElement(['Nursery','KG-1','KG-2','Class 1','Class 2','Class 3','Class 4','Class 5']),
                'date'           => $faker->date(),
                'status'         => $faker->randomElement(['New','In Progress','Converted','Closed']),
                'admin_id'       => \App\Models\User::inRandomOrder()->first()->id ?? 1,
                'note'           => $faker->optional()->sentence(),
            ]);
        }
    }
} 
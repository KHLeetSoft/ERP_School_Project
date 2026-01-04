<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentsHealth;
use App\Models\StudentDetail;
use App\Models\School;
use Faker\Factory as Faker;

class StudentsHealthSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $students = StudentDetail::pluck('id')->toArray();
        $schools = School::pluck('id')->toArray();

        if (empty($students)) {
            $this->command->info('No students found for StudentsHealthSeeder.');
            return;
        }

        foreach (array_slice($students, 0, 50) as $sid) {
            StudentsHealth::create([
                'school_id' => $faker->randomElement($schools) ?? null,
                'student_id' => $sid,
                'height_cm' => $faker->randomFloat(2, 90, 180),
                'weight_kg' => $faker->randomFloat(2, 10, 90),
                'blood_group' => $faker->randomElement(['A+', 'A-', 'B+', 'B-','O+','O-','AB+','AB-']),
                'allergies' => $faker->randomElement(['None', 'Peanuts', 'Pollen', 'Dust']),
                'medical_conditions' => $faker->randomElement(['None', 'Asthma', 'Diabetes']),
                'immunizations' => 'BCG, Polio',
                'last_checkup_date' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                'notes' => $faker->sentence,
            ]);
        }
    }
}

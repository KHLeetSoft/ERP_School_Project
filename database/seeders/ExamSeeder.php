<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        for ($i=0; $i<15; $i++) {
            $start = Carbon::parse($faker->dateTimeBetween('-2 months', '+2 months'));
            $end = (clone $start)->addDays($faker->numberBetween(1,7));
            Exam::create([
                'school_id' => null,
                'title' => $faker->randomElement(['Unit Test','Term Exam','Final Exam']).' '.($i+1),
                'exam_type' => $faker->randomElement(['Unit','Term','Final']),
                'academic_year' => now()->format('Y').'-'.now()->addYear()->format('Y'),
                'description' => $faker->optional()->sentence(8),
                'start_date' => $start->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
                'status' => $faker->randomElement(['scheduled','completed','cancelled','draft']),
            ]);
        }
    }
}



<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ComplaintBox;
use App\Models\User;
use App\Models\School;
use Faker\Factory as Faker;

class ComplaintBoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $faker = Faker::create();

        foreach (range(1, 20) as $i) {
            $userId = \App\Models\User::inRandomOrder()->first()->id ?? 1;
            $schoolId = \App\Models\School::inRandomOrder()->first()->id ?? 1;
            ComplaintBox::create([
                'user_id'     => $userId,
                'school_id'   => $schoolId,
                'title'       => $faker->sentence(5),
                'description' => $faker->paragraph,
                'status'      => $faker->randomElement(['pending', 'resolved', 'rejected']),
            ]);
        }
    }
}

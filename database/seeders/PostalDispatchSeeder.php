<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostalDispatch;
use Faker\Factory as Faker;

class PostalDispatchSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        foreach(range(1,30) as $i){
            $userId = \App\Models\User::inRandomOrder()->first()->id ?? 1;
            $schoolId = \App\Models\School::inRandomOrder()->first()->id ?? 1;
            PostalDispatch::create([
                'user_id'      => $userId,
                'school_id'     => $schoolId,
                'to_title'     => $faker->name,
                'reference_no' => 'REF'.$faker->numberBetween(100,999),
                'address'      => $faker->address,
                'from_title'   => $faker->company,
                'date'         => $faker->date(),
                'note'         => $faker->optional()->sentence(),
            ]);
        }
    }
} 
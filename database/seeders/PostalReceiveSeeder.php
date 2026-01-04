<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostalReceive; // ✅ ADD THIS LINE
use App\Models\User;
use App\Models\School;
use Faker\Factory as Faker;


class PostalReceiveSeeder extends Seeder
{
    
    public function run(): void
    {
        $faker = Faker::create();
        foreach(range(1,30) as $i){
            $userId = \App\Models\User::inRandomOrder()->first()->id ?? 1;
            $schoolId = \App\Models\School::inRandomOrder()->first()->id ?? 1;
            PostalReceive::create([
                'user_id'     => $userId,
                'school_id'   => $schoolId,
                'from_title'  => $faker->company,
                'to_title'    => $faker->name,
                'reference_no'=> 'REF'.$faker->numberBetween(100,999),
                'address'     => $faker->address,
                'date'        => $faker->date(),
                'note'        => $faker->optional()->sentence(),
            ]);
        }
    }
}

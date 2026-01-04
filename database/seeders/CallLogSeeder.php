<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CallLog;
use Faker\Factory as Faker;

class CallLogSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        foreach(range(1,40) as $i){
            $userId = \App\Models\User::inRandomOrder()->first()->id ?? 1;
            $schoolId = \App\Models\School::inRandomOrder()->first()->id ?? 1;
            CallLog::create([
                'user_id'     => $userId,
                'school_id'   => $schoolId,
                'caller_name' => $faker->name,
                'purpose'     => $faker->randomElement(['Enquiry','Follow-up','Support','Other']),
                'phone'       => $faker->phoneNumber,
                'date'        => $faker->date(),
                'time'        => $faker->time('H:i'),
                'duration'    => $faker->numberBetween(1,30) . ' mins',
                'note'        => $faker->optional()->sentence(),
            ]);
        }
    }
} 
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use Faker\Factory as Faker;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        foreach(range(1,30) as $i){
            $user = \App\Models\User::inRandomOrder()->first()->id ?? 1;
            Visitor::create([
                'user_id'     => $user,
                'school_id'   => \App\Models\User::find($user)->school_id ?? 1,
                'visitor_name' => $faker->name,
                'purpose'      => $faker->randomElement(['Enquiry','Delivery','Meeting']),
                'phone'        => $faker->phoneNumber,
                'date'         => $faker->date(),
                'in_time'      => $faker->time('H:i'),
                'out_time'     => $faker->time('H:i'),
                'note'         => $faker->sentence,
            ]);
        }
    }
} 
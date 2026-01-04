<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LibraryMember;
use Faker\Factory as Faker;
use Carbon\Carbon;

class LibraryMemberSeeder extends Seeder
{
	public function run(): void
	{
		$faker = Faker::create();
		foreach (range(1, 30) as $i) {
			LibraryMember::create([
				'school_id' => 1,
				'membership_no' => strtoupper($faker->bothify('MBR####')),
				'name' => $faker->name(),
				'email' => $faker->unique()->safeEmail(),
				'phone' => $faker->numerify('98########'),
				'address' => $faker->address(),
				'member_type' => $faker->randomElement(['student','teacher','staff','external']),
				'joined_at' => Carbon::now()->subDays(rand(1, 400)),
				'expiry_at' => Carbon::now()->addDays(rand(30, 400)),
				'status' => $faker->randomElement(['active','inactive','expired']),
				'notes' => $faker->optional()->sentence(),
			]);
		}
	}
}



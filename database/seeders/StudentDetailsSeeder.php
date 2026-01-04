<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentDetail;
use App\Models\User;
use App\Models\School;

class StudentDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $i) {
            $userId = User::inRandomOrder()->first()->id ?? 1;
            $schoolId = School::inRandomOrder()->first()->id ?? 1;
             $classId = SchoolClass::inRandomOrder()->first()->id ?? 1;
            $sectionId = Section::inRandomOrder()->first()->id ?? 1;
            StudentDetail::create([
                'user_id'            => $userId,
                'school_id'          => $schoolId, 
                'class_id'           => $classId, // You can randomize if needed
                'section_id'         => $sectionId,
                'first_name'         => $faker->firstName(),
                 'last_name'         => $faker->lastName(),
                'roll_no'            => str_pad($i, 2, '0', STR_PAD_LEFT), // 01, 02, ...
                'admission_no'       => 'ADM2025' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'dob'                => $faker->date('Y-m-d', '2010-12-31'),
                'gender'             => $faker->randomElement(['Male', 'Female']),
                'blood_group'        => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'religion'           => 'Hindu', // Fixed as per sample
                'nationality'        => 'Indian',
                'category'           => 'General',
                'guardian_name'      => $faker->name('male'),
                'guardian_contact'   => $faker->numerify('98########'),
                'address'            => $faker->address,
               'profile_image_path' => $faker->imageUrl(640, 480, 'people', true, 'Faker'), // Random image URL
                'profile_image'      => 'https://via.placeholder.com/150', // Placeholder image
                 'status'             => $faker->randomElement(['active', 'inactive']), //
                // Optional: other fields if exis in table
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }
    }
}

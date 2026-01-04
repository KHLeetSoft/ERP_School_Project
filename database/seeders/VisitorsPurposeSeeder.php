<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisitorsPurpose;
use App\Models\User;
use Faker\Factory as Faker;

class VisitorsPurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $adminUsers = User::where('role_id', 1)->get(); // Assuming role_id 1 is for admin

        if ($adminUsers->isEmpty()) {
            // If no admin users found, create at least one purpose with a default user
            VisitorsPurpose::create([
                'user_id' => 1,
                'school_id' => 1,
                'name' => 'Meeting',
                'description' => 'General meeting with staff',
                'status' => true,
            ]);
            return;
        }

        // Common visitor purposes
        $purposes = [
            ['name' => 'Meeting', 'description' => 'General meeting with staff'],
            ['name' => 'Enquiry', 'description' => 'General enquiry about the school'],
            ['name' => 'Admission', 'description' => 'Admission related visit'],
            ['name' => 'Parent-Teacher Meeting', 'description' => 'Meeting between parents and teachers'],
            ['name' => 'Delivery', 'description' => 'Delivery of items or documents'],
            ['name' => 'Maintenance', 'description' => 'Maintenance or repair work'],
            ['name' => 'Official', 'description' => 'Official visit from authorities'],
            ['name' => 'Event', 'description' => 'Attending school event'],
            ['name' => 'Other', 'description' => 'Other purposes not listed'],
        ];

        foreach ($adminUsers as $admin) {
            foreach ($purposes as $purpose) {
                VisitorsPurpose::create([
                    'user_id' => $admin->id,
                    'school_id' => $admin->school_id,
                    'name' => $purpose['name'],
                    'description' => $purpose['description'],
                    'status' => $faker->boolean(90), // 90% chance of being active
                ]);
            }
        }
    }
}
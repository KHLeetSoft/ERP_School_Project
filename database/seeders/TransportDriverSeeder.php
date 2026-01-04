<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportDriver;
use App\Models\School;
use App\Models\User;
use App\Models\TransportVehicle;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TransportDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all schools
        $schools = School::all();
        
        // License types
        $licenseTypes = ['light_motor', 'heavy_motor', 'commercial', 'passenger', 'special'];
        
        // Experience levels
        $experienceLevels = ['beginner', 'intermediate', 'experienced', 'expert'];
        
        // Status options
        $statuses = ['active', 'inactive', 'suspended', 'on_leave'];
        
        // Emergency contact relations
        $relations = ['Spouse', 'Father', 'Mother', 'Brother', 'Sister', 'Son', 'Daughter', 'Friend'];

        foreach ($schools as $school) {
            // Create 15 drivers per school
            for ($i = 1; $i <= 15; $i++) {
                $licenseType = $faker->randomElement($licenseTypes);
                $experienceLevel = $faker->randomElement($experienceLevels);
                $status = $faker->randomElement($statuses);
                
                // Generate realistic experience years based on level
                $yearsOfExperience = match($experienceLevel) {
                    'beginner' => $faker->numberBetween(0, 2),
                    'intermediate' => $faker->numberBetween(3, 5),
                    'experienced' => $faker->numberBetween(6, 10),
                    'expert' => $faker->numberBetween(11, 20),
                    default => $faker->numberBetween(0, 5)
                };

                // Generate license expiry date (some expired, some expiring soon, some valid)
                $licenseExpiryOptions = [
                    now()->subDays($faker->numberBetween(1, 365)), // Expired
                    now()->addDays($faker->numberBetween(1, 30)),   // Expiring soon
                    now()->addDays($faker->numberBetween(31, 365)), // Valid
                    now()->addDays($faker->numberBetween(366, 1095)) // Long term valid
                ];
                $licenseExpiryDate = $faker->randomElement($licenseExpiryOptions);

                // Generate joining date (within last 10 years)
                $joiningDate = $faker->dateTimeBetween('-10 years', 'now');
                
                // Generate birth date (18-65 years old)
                $birthDate = $faker->dateTimeBetween('-65 years', '-18 years');

                // Get available vehicles for assignment
                $availableVehicles = TransportVehicle::where('school_id', $school->id)
                    ->where('status', 'active')
                    ->whereNull('driver_id')
                    ->get();

                $vehicleId = null;
                if ($availableVehicles->isNotEmpty() && $faker->boolean(70)) { // 70% chance of assignment
                    $vehicleId = $availableVehicles->random()->id;
                }

                // Get available users for linking
                $availableUsers = User::where('school_id', $school->id)
                    ->whereDoesntHave('transportDriver')
                    ->get();

                $userId = null;
                if ($availableUsers->isNotEmpty() && $faker->boolean(60)) { // 60% chance of user link
                    $userId = $availableUsers->random()->id;
                }

                $driver = TransportDriver::create([
                    'school_id' => $school->id,
                    'user_id' => $userId,
                    'name' => $faker->name(),
                    'license_number' => strtoupper($faker->bothify('??##??##??')),
                    'license_type' => $licenseType,
                    'license_expiry_date' => $licenseExpiryDate,
                    'phone' => $faker->phoneNumber(),
                    'email' => $faker->optional(0.8)->email(),
                    'address' => $faker->address(),
                    'date_of_birth' => $birthDate,
                    'date_of_joining' => $joiningDate,
                    'experience_level' => $experienceLevel,
                    'years_of_experience' => $yearsOfExperience,
                    'emergency_contact_name' => $faker->name(),
                    'emergency_contact_phone' => $faker->phoneNumber(),
                    'emergency_contact_relation' => $faker->randomElement($relations),
                    'vehicle_id' => $vehicleId,
                    'status' => $status,
                    'notes' => $faker->optional(0.3)->sentence(),
                    'created_by' => User::where('school_id', $school->id)->inRandomOrder()->first()?->id ?? 1,
                ]);

                // Update vehicle assignment if driver was assigned
                if ($vehicleId) {
                    TransportVehicle::where('id', $vehicleId)
                        ->update(['driver_id' => $driver->id]);
                }
            }
        }

        $this->command->info('Transport drivers seeded successfully!');
    }
}

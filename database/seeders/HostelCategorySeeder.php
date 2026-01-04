<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HostelCategory;
use App\Models\School;
use App\Models\User;

class HostelCategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $schools = School::all();
        $users = User::where('role_id', 1)->get(); // Admin users

        if ($schools->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No schools or admin users found. Skipping hostel categories seeding.');
            return;
        }

        $facilities = [
            ['WiFi', 'Air Conditioning', 'Study Desk', 'Wardrobe', 'Bed'],
            ['WiFi', 'Fan', 'Study Desk', 'Wardrobe', 'Bed', 'Chair'],
            ['WiFi', 'Air Conditioning', 'Study Desk', 'Wardrobe', 'Bed', 'Chair', 'Bookshelf'],
            ['WiFi', 'Fan', 'Study Desk', 'Wardrobe', 'Bed', 'Chair', 'Bookshelf', 'Mirror'],
            ['WiFi', 'Air Conditioning', 'Study Desk', 'Wardrobe', 'Bed', 'Chair', 'Bookshelf', 'Mirror', 'Balcony'],
        ];

        $rules = [
            ['No smoking', 'No alcohol', 'Quiet hours 10 PM - 6 AM', 'No pets allowed'],
            ['No smoking', 'No alcohol', 'Quiet hours 11 PM - 6 AM', 'No pets allowed', 'No cooking in rooms'],
            ['No smoking', 'No alcohol', 'Quiet hours 10 PM - 6 AM', 'No pets allowed', 'No cooking in rooms', 'Visitors allowed till 8 PM'],
            ['No smoking', 'No alcohol', 'Quiet hours 11 PM - 6 AM', 'No pets allowed', 'No cooking in rooms', 'Visitors allowed till 9 PM', 'Keep room clean'],
            ['No smoking', 'No alcohol', 'Quiet hours 10 PM - 6 AM', 'No pets allowed', 'No cooking in rooms', 'Visitors allowed till 8 PM', 'Keep room clean', 'Respect other residents'],
        ];

        $categoryTypes = [
            [
                'name' => 'Standard Single Room',
                'description' => 'Comfortable single occupancy room with basic amenities',
                'monthly_fee' => 15000,
                'security_deposit' => 30000,
                'capacity' => 20,
            ],
            [
                'name' => 'Standard Double Room',
                'description' => 'Shared double occupancy room with essential facilities',
                'monthly_fee' => 12000,
                'security_deposit' => 25000,
                'capacity' => 30,
            ],
            [
                'name' => 'Deluxe Single Room',
                'description' => 'Premium single room with enhanced amenities and comfort',
                'monthly_fee' => 20000,
                'security_deposit' => 40000,
                'capacity' => 15,
            ],
            [
                'name' => 'Deluxe Double Room',
                'description' => 'Premium shared room with modern facilities',
                'monthly_fee' => 16000,
                'security_deposit' => 35000,
                'capacity' => 25,
            ],
            [
                'name' => 'Executive Suite',
                'description' => 'Luxury accommodation with premium amenities and services',
                'monthly_fee' => 30000,
                'security_deposit' => 60000,
                'capacity' => 10,
            ],
        ];

        foreach ($schools as $school) {
            $adminUser = $users->first();
            
            foreach ($categoryTypes as $index => $categoryData) {
                $availableRooms = rand(0, $categoryData['capacity']);
                
                HostelCategory::create([
                    'school_id' => $school->id,
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                    'monthly_fee' => $categoryData['monthly_fee'],
                    'security_deposit' => $categoryData['security_deposit'],
                    'capacity' => $categoryData['capacity'],
                    'available_rooms' => $availableRooms,
                    'facilities' => $facilities[$index],
                    'rules' => $rules[$index],
                    'status' => $this->getRandomStatus(),
                    'created_by' => $adminUser->id,
                    'updated_by' => null,
                ]);
            }
        }

        $this->command->info('Hostel categories seeded successfully for ' . $schools->count() . ' schools.');
    }

    private function getRandomStatus()
    {
        $statuses = ['active', 'active', 'active', 'inactive', 'maintenance'];
        return $statuses[array_rand($statuses)];
    }
}
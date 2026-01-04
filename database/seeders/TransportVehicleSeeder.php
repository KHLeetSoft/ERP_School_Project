<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransportVehicle;
use App\Models\User;
use App\Models\TransportRoute;
use Carbon\Carbon;

class TransportVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample users for driver and conductor assignments
        // Since we don't have specific role columns, we'll just get some users
        $drivers = User::take(5)->get();
        $conductors = User::take(5)->get();
        $routes = TransportRoute::take(10)->get();

        // Sample vehicle data
        $vehicles = [
            // Buses
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V001',
                'registration_number' => 'MH12AB1234',
                'vehicle_type' => 'bus',
                'brand' => 'Tata',
                'model' => 'Starbus',
                'year_of_manufacture' => 2020,
                'seating_capacity' => 35,
                'fuel_type' => 'diesel',
                'fuel_efficiency' => 8.5,
                'insurance_number' => 'INS001',
                'insurance_expiry' => Carbon::now()->addMonths(6),
                'permit_number' => 'PER001',
                'permit_expiry' => Carbon::now()->addYear(),
                'fitness_certificate_number' => 'FIT001',
                'fitness_expiry' => Carbon::now()->addMonths(3),
                'puc_certificate_number' => 'PUC001',
                'puc_expiry' => Carbon::now()->addMonths(1),
                'status' => 'active',
                'description' => 'Modern air-conditioned bus with comfortable seating',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => $conductors->count() > 0 ? $conductors->random()->id : null,
                'assigned_route_id' => $routes->count() > 0 ? $routes->random()->id : null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V002',
                'registration_number' => 'MH12CD5678',
                'vehicle_type' => 'bus',
                'brand' => 'Ashok Leyland',
                'model' => 'Viking',
                'year_of_manufacture' => 2019,
                'seating_capacity' => 42,
                'fuel_type' => 'diesel',
                'fuel_efficiency' => 7.8,
                'insurance_number' => 'INS002',
                'insurance_expiry' => Carbon::now()->addMonths(8),
                'permit_number' => 'PER002',
                'permit_expiry' => Carbon::now()->addMonths(10),
                'fitness_certificate_number' => 'FIT002',
                'fitness_expiry' => Carbon::now()->addMonths(4),
                'puc_certificate_number' => 'PUC002',
                'puc_expiry' => Carbon::now()->addMonths(2),
                'status' => 'active',
                'description' => 'Reliable bus for long-distance routes',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => $conductors->count() > 0 ? $conductors->random()->id : null,
                'assigned_route_id' => $routes->count() > 0 ? $routes->random()->id : null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V003',
                'registration_number' => 'MH12EF9012',
                'vehicle_type' => 'minibus',
                'brand' => 'Force',
                'model' => 'Traveller',
                'year_of_manufacture' => 2021,
                'seating_capacity' => 20,
                'fuel_type' => 'diesel',
                'fuel_efficiency' => 9.2,
                'insurance_number' => 'INS003',
                'insurance_expiry' => Carbon::now()->addMonths(12),
                'permit_number' => 'PER003',
                'permit_expiry' => Carbon::now()->addMonths(15),
                'fitness_certificate_number' => 'FIT003',
                'fitness_expiry' => Carbon::now()->addMonths(6),
                'puc_certificate_number' => 'PUC003',
                'puc_expiry' => Carbon::now()->addMonths(3),
                'status' => 'active',
                'description' => 'Compact minibus for short routes',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => $conductors->count() > 0 ? $conductors->random()->id : null,
                'assigned_route_id' => $routes->count() > 0 ? $routes->random()->id : null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V004',
                'registration_number' => 'MH12GH3456',
                'vehicle_type' => 'van',
                'brand' => 'Mahindra',
                'model' => 'Bolero',
                'year_of_manufacture' => 2022,
                'seating_capacity' => 12,
                'fuel_type' => 'diesel',
                'fuel_efficiency' => 10.5,
                'insurance_number' => 'INS004',
                'insurance_expiry' => Carbon::now()->addMonths(18),
                'permit_number' => 'PER004',
                'permit_expiry' => Carbon::now()->addMonths(20),
                'fitness_certificate_number' => 'FIT004',
                'fitness_expiry' => Carbon::now()->addMonths(8),
                'puc_certificate_number' => 'PUC004',
                'puc_expiry' => Carbon::now()->addMonths(4),
                'status' => 'active',
                'description' => 'Versatile van for various transport needs',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => $conductors->count() > 0 ? $conductors->random()->id : null,
                'assigned_route_id' => $routes->count() > 0 ? $routes->random()->id : null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V005',
                'registration_number' => 'MH12IJ7890',
                'vehicle_type' => 'car',
                'brand' => 'Maruti Suzuki',
                'model' => 'Swift Dzire',
                'year_of_manufacture' => 2023,
                'seating_capacity' => 5,
                'fuel_type' => 'petrol',
                'fuel_efficiency' => 15.2,
                'insurance_number' => 'INS005',
                'insurance_expiry' => Carbon::now()->addMonths(24),
                'permit_number' => 'PER005',
                'permit_expiry' => Carbon::now()->addMonths(25),
                'fitness_certificate_number' => 'FIT005',
                'fitness_expiry' => Carbon::now()->addMonths(12),
                'puc_certificate_number' => 'PUC005',
                'puc_expiry' => Carbon::now()->addMonths(6),
                'status' => 'active',
                'description' => 'Comfortable sedan for executive transport',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => null, // Cars typically don't have conductors
                'assigned_route_id' => $routes->count() > 0 ? $routes->random()->id : null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V006',
                'registration_number' => 'MH12KL1234',
                'vehicle_type' => 'truck',
                'brand' => 'Tata',
                'model' => '407',
                'year_of_manufacture' => 2020,
                'seating_capacity' => 3,
                'fuel_type' => 'diesel',
                'fuel_efficiency' => 6.8,
                'insurance_number' => 'INS006',
                'insurance_expiry' => Carbon::now()->addMonths(4),
                'permit_number' => 'PER006',
                'permit_expiry' => Carbon::now()->addMonths(6),
                'fitness_certificate_number' => 'FIT006',
                'fitness_expiry' => Carbon::now()->addMonths(2),
                'puc_certificate_number' => 'PUC006',
                'puc_expiry' => Carbon::now()->addMonths(1),
                'status' => 'maintenance',
                'description' => 'Heavy-duty truck for cargo transport',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => null, // Trucks typically don't have conductors
                'assigned_route_id' => null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V007',
                'registration_number' => 'MH12MN5678',
                'vehicle_type' => 'bus',
                'brand' => 'Volvo',
                'model' => 'B9R',
                'year_of_manufacture' => 2021,
                'seating_capacity' => 45,
                'fuel_type' => 'diesel',
                'fuel_efficiency' => 7.5,
                'insurance_number' => 'INS007',
                'insurance_expiry' => Carbon::now()->addMonths(10),
                'permit_number' => 'PER007',
                'permit_expiry' => Carbon::now()->addMonths(12),
                'fitness_certificate_number' => 'FIT007',
                'fitness_expiry' => Carbon::now()->addMonths(5),
                'puc_certificate_number' => 'PUC007',
                'puc_expiry' => Carbon::now()->addMonths(2),
                'status' => 'active',
                'description' => 'Luxury bus with premium amenities',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => $conductors->count() > 0 ? $conductors->random()->id : null,
                'assigned_route_id' => $routes->count() > 0 ? $routes->random()->id : null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V008',
                'registration_number' => 'MH12OP9012',
                'vehicle_type' => 'minibus',
                'brand' => 'BharatBenz',
                'model' => 'Mini Bus',
                'year_of_manufacture' => 2022,
                'seating_capacity' => 18,
                'fuel_type' => 'diesel',
                'fuel_efficiency' => 8.8,
                'insurance_number' => 'INS008',
                'insurance_expiry' => Carbon::now()->addMonths(16),
                'permit_number' => 'PER008',
                'permit_expiry' => Carbon::now()->addMonths(18),
                'fitness_certificate_number' => 'FIT008',
                'fitness_expiry' => Carbon::now()->addMonths(7),
                'puc_certificate_number' => 'PUC008',
                'puc_expiry' => Carbon::now()->addMonths(3),
                'status' => 'inactive',
                'description' => 'School transport minibus',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => $conductors->count() > 0 ? $conductors->random()->id : null,
                'assigned_route_id' => null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V009',
                'registration_number' => 'MH12QR3456',
                'vehicle_type' => 'car',
                'brand' => 'Honda',
                'model' => 'City',
                'year_of_manufacture' => 2023,
                'seating_capacity' => 5,
                'fuel_type' => 'petrol',
                'fuel_efficiency' => 14.8,
                'insurance_number' => 'INS009',
                'insurance_expiry' => Carbon::now()->addMonths(22),
                'permit_number' => 'PER009',
                'permit_expiry' => Carbon::now()->addMonths(23),
                'fitness_certificate_number' => 'FIT009',
                'fitness_expiry' => Carbon::now()->addMonths(11),
                'puc_certificate_number' => 'PUC009',
                'puc_expiry' => Carbon::now()->addMonths(5),
                'status' => 'active',
                'description' => 'Premium sedan for VIP transport',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => null,
                'assigned_route_id' => $routes->count() > 0 ? $routes->random()->id : null,
            ],
            [
                'school_id' => 1, // Default school ID
                'vehicle_number' => 'V010',
                'registration_number' => 'MH12ST7890',
                'vehicle_type' => 'bus',
                'brand' => 'Eicher',
                'model' => 'Skyline',
                'year_of_manufacture' => 2018,
                'seating_capacity' => 38,
                'fuel_type' => 'diesel',
                'fuel_efficiency' => 8.0,
                'insurance_number' => 'INS010',
                'insurance_expiry' => Carbon::now()->addMonths(2),
                'permit_number' => 'PER010',
                'permit_expiry' => Carbon::now()->addMonths(4),
                'fitness_certificate_number' => 'FIT010',
                'fitness_expiry' => Carbon::now()->addMonths(1),
                'puc_certificate_number' => 'PUC010',
                'puc_expiry' => Carbon::now()->addDays(15),
                'status' => 'repair',
                'description' => 'City bus for local routes',
                'driver_id' => $drivers->count() > 0 ? $drivers->random()->id : null,
                'conductor_id' => $conductors->count() > 0 ? $conductors->random()->id : null,
                'assigned_route_id' => null,
            ],
        ];

        // Create vehicles
        foreach ($vehicles as $vehicleData) {
            TransportVehicle::create($vehicleData);
        }

        $this->command->info('Transport vehicles seeded successfully!');
        $this->command->info('Created ' . count($vehicles) . ' vehicles');
    }
}

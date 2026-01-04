<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransportRoute;
use Illuminate\Support\Facades\Auth;

class TransportRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the school ID from the authenticated user or use a default
        $schoolId = Auth::check() ? Auth::user()->school_id : 1;

        $routes = [
            [
                'route_name' => 'North City Loop',
                'route_number' => 'R001',
                'start_location' => 'North Gate',
                'end_location' => 'Main Campus',
                'total_distance' => 12.5,
                'estimated_duration' => 45,
                'vehicle_capacity' => 35,
                'current_occupancy' => 0,
                'route_type' => 'regular',
                'description' => 'Covers northern residential blocks and main campus area',
                'status' => 'active',
                'is_active' => true
            ],
            [
                'route_name' => 'South Express',
                'route_number' => 'R002',
                'start_location' => 'South Terminal',
                'end_location' => 'Central Station',
                'total_distance' => 8.2,
                'estimated_duration' => 25,
                'vehicle_capacity' => 28,
                'current_occupancy' => 0,
                'route_type' => 'express',
                'description' => 'Express route connecting south terminal to central station',
                'status' => 'active',
                'is_active' => true
            ],
            [
                'route_name' => 'East-West Corridor',
                'route_number' => 'R003',
                'start_location' => 'East Market',
                'end_location' => 'West Campus',
                'total_distance' => 15.8,
                'estimated_duration' => 55,
                'vehicle_capacity' => 40,
                'current_occupancy' => 0,
                'route_type' => 'regular',
                'description' => 'Major east-west corridor serving multiple districts',
                'status' => 'active',
                'is_active' => true
            ],
            [
                'route_name' => 'Downtown Shuttle',
                'route_number' => 'R004',
                'start_location' => 'City Center',
                'end_location' => 'Business District',
                'total_distance' => 3.5,
                'estimated_duration' => 15,
                'vehicle_capacity' => 20,
                'current_occupancy' => 0,
                'route_type' => 'special',
                'description' => 'Short distance shuttle service in downtown area',
                'status' => 'active',
                'is_active' => true
            ],
            [
                'route_name' => 'Airport Express',
                'route_number' => 'R005',
                'start_location' => 'Central Station',
                'end_location' => 'International Airport',
                'total_distance' => 28.5,
                'estimated_duration' => 75,
                'vehicle_capacity' => 45,
                'current_occupancy' => 0,
                'route_type' => 'express',
                'description' => 'Premium express service to international airport',
                'status' => 'active',
                'is_active' => true
            ],
            [
                'route_name' => 'University Route',
                'route_number' => 'R006',
                'start_location' => 'Student Housing',
                'end_location' => 'University Campus',
                'total_distance' => 4.2,
                'estimated_duration' => 18,
                'vehicle_capacity' => 25,
                'current_occupancy' => 0,
                'route_type' => 'school',
                'description' => 'Student-focused route connecting housing to campus',
                'status' => 'active',
                'is_active' => true
            ],
            [
                'route_name' => 'Hospital Line',
                'route_number' => 'R007',
                'start_location' => 'Central Station',
                'end_location' => 'City Hospital',
                'total_distance' => 6.8,
                'estimated_duration' => 22,
                'vehicle_capacity' => 30,
                'current_occupancy' => 0,
                'route_type' => 'special',
                'description' => 'Medical staff and patient transport service',
                'status' => 'active',
                'is_active' => true
            ],
            [
                'route_name' => 'Shopping Mall Express',
                'route_number' => 'R008',
                'start_location' => 'Residential Area',
                'end_location' => 'Mega Mall',
                'total_distance' => 7.5,
                'estimated_duration' => 28,
                'vehicle_capacity' => 32,
                'current_occupancy' => 0,
                'route_type' => 'special',
                'description' => 'Weekend shopping and entertainment route',
                'status' => 'inactive',
                'is_active' => false
            ],
            [
                'route_name' => 'Industrial Zone',
                'route_number' => 'R009',
                'start_location' => 'Worker Colony',
                'end_location' => 'Industrial Park',
                'total_distance' => 11.2,
                'estimated_duration' => 38,
                'vehicle_capacity' => 38,
                'current_occupancy' => 0,
                'route_type' => 'regular',
                'description' => 'Industrial worker transport during shift changes',
                'status' => 'active',
                'is_active' => true
            ],
            [
                'route_name' => 'Night Service',
                'route_number' => 'R010',
                'start_location' => 'Night Market',
                'end_location' => 'Late Night Areas',
                'total_distance' => 9.8,
                'estimated_duration' => 35,
                'vehicle_capacity' => 25,
                'current_occupancy' => 0,
                'route_type' => 'special',
                'description' => 'Late night transport service for night workers',
                'status' => 'inactive',
                'is_active' => false
            ]
        ];

        foreach ($routes as $routeData) {
            TransportRoute::create([
                'school_id' => $schoolId,
                'route_name' => $routeData['route_name'],
                'route_number' => $routeData['route_number'],
                'start_location' => $routeData['start_location'],
                'end_location' => $routeData['end_location'],
                'total_distance' => $routeData['total_distance'],
                'estimated_duration' => $routeData['estimated_duration'],
                'vehicle_capacity' => $routeData['vehicle_capacity'],
                'current_occupancy' => $routeData['current_occupancy'],
                'route_type' => $routeData['route_type'],
                'description' => $routeData['description'],
                'status' => $routeData['status'],
                'is_active' => $routeData['is_active'],
                'created_by' => 1
            ]);
        }

        $this->command->info('Transport routes seeded successfully!');
    }
}

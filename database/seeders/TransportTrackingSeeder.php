<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportTracking;
use App\Models\TransportVehicle;
use App\Models\TransportRoute;
use App\Models\TransportDriver;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;

class TransportTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        $statuses = ['on_time', 'delayed', 'early', 'stopped', 'moving'];

        foreach ($schools as $school) {
            $vehicles = TransportVehicle::where('school_id', $school->id)->get();
            $routes = TransportRoute::where('school_id', $school->id)->get();
            $drivers = TransportDriver::where('school_id', $school->id)->get();
            $admin = User::where('school_id', $school->id)->where('role_id', 2)->first();

            if ($vehicles->isEmpty() || $routes->isEmpty() || $drivers->isEmpty()) {
                continue;
            }

            // Generate tracking data for the last 30 days
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::now()->subDays($i);
                
                // Generate 3-8 tracking records per day
                $recordsPerDay = rand(3, 8);
                
                for ($j = 0; $j < $recordsPerDay; $j++) {
                    $vehicle = $vehicles->random();
                    $route = $routes->random();
                    $driver = $drivers->random();
                    $status = $statuses[array_rand($statuses)];
                    
                    // Generate realistic time (between 6 AM and 8 PM)
                    $hour = rand(6, 20);
                    $minute = rand(0, 59);
                    $time = Carbon::createFromTime($hour, $minute, 0);
                    
                    // Generate realistic coordinates (example: around a school area)
                    $baseLat = 28.6139 + (rand(-100, 100) / 10000); // Delhi area
                    $baseLng = 77.2090 + (rand(-100, 100) / 10000);
                    
                    // Generate speed based on status
                    $speed = match($status) {
                        'stopped' => 0,
                        'moving' => rand(20, 60),
                        'on_time' => rand(15, 45),
                        'delayed' => rand(10, 35),
                        'early' => rand(25, 50),
                        default => rand(0, 40)
                    };

                    TransportTracking::create([
                        'school_id' => $school->id,
                        'vehicle_id' => $vehicle->id,
                        'route_id' => $route->id,
                        'driver_id' => $driver->id,
                        'tracking_date' => $date->toDateString(),
                        'tracking_time' => $time->toTimeString(),
                        'latitude' => $baseLat,
                        'longitude' => $baseLng,
                        'speed' => $speed,
                        'status' => $status,
                        'notes' => $this->generateNotes($status),
                        'created_by' => $admin ? $admin->id : null,
                    ]);
                }
            }

            // Generate some real-time tracking data for today
            $today = Carbon::today();
            $vehiclesToTrack = $vehicles->take(rand(2, 4)); // Track 2-4 vehicles today
            
            foreach ($vehiclesToTrack as $vehicle) {
                $route = $routes->random();
                $driver = $drivers->random();
                
                // Generate 2-5 tracking points for today
                $pointsToday = rand(2, 5);
                
                for ($k = 0; $k < $pointsToday; $k++) {
                    $hour = rand(7, 18);
                    $minute = rand(0, 59);
                    $time = Carbon::createFromTime($hour, $minute, 0);
                    
                    $baseLat = 28.6139 + (rand(-50, 50) / 10000);
                    $baseLng = 77.2090 + (rand(-50, 50) / 10000);
                    
                    $status = $statuses[array_rand($statuses)];
                    $speed = match($status) {
                        'stopped' => 0,
                        'moving' => rand(20, 60),
                        'on_time' => rand(15, 45),
                        'delayed' => rand(10, 35),
                        'early' => rand(25, 50),
                        default => rand(0, 40)
                    };

                    TransportTracking::create([
                        'school_id' => $school->id,
                        'vehicle_id' => $vehicle->id,
                        'route_id' => $route->id,
                        'driver_id' => $driver->id,
                        'tracking_date' => $today->toDateString(),
                        'tracking_time' => $time->toTimeString(),
                        'latitude' => $baseLat,
                        'longitude' => $baseLng,
                        'speed' => $speed,
                        'status' => $status,
                        'notes' => $this->generateNotes($status),
                        'created_by' => $admin ? $admin->id : null,
                    ]);
                }
            }
        }
    }

    private function generateNotes($status)
    {
        $notes = [
            'on_time' => [
                'Vehicle running on schedule',
                'All students picked up on time',
                'Route completed as planned',
                'No delays encountered',
                'Smooth journey'
            ],
            'delayed' => [
                'Traffic congestion on main road',
                'Student pickup delay',
                'Vehicle breakdown - minor issue resolved',
                'Weather conditions causing delay',
                'Route deviation due to road closure'
            ],
            'early' => [
                'Light traffic conditions',
                'All students ready for pickup',
                'Efficient route completion',
                'No stops required',
                'Ahead of schedule'
            ],
            'stopped' => [
                'Student pickup in progress',
                'Vehicle maintenance break',
                'Driver rest period',
                'Waiting at designated stop',
                'Emergency stop'
            ],
            'moving' => [
                'Vehicle in motion',
                'En route to next stop',
                'Returning to school',
                'Following planned route',
                'Active transportation'
            ]
        ];

        $statusNotes = $notes[$status] ?? ['No notes available'];
        return $statusNotes[array_rand($statusNotes)];
    }
}
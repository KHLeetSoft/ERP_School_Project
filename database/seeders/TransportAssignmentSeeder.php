<?php

namespace Database\Seeders;

use App\Models\TransportAssignment;
use App\Models\TransportVehicle;
use App\Models\TransportRoute;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransportAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try to infer a school_id from existing vehicles; fallback to 1
        $vehicleSample = TransportVehicle::query()->inRandomOrder()->first();
        if (!$vehicleSample) {

            $this->command?->warn('No transport vehicles found. Skipping TransportAssignment seeding.');
            return;
            
        }

        $schoolId = (int) ($vehicleSample->school_id ?? 1);

        $vehicles = TransportVehicle::query()
            ->where('school_id', $schoolId)
            ->get();

        $routes = TransportRoute::query()
            ->where('school_id', $schoolId)
            ->get();

        if ($vehicles->isEmpty() || $routes->isEmpty()) {
            $this->command?->warn('Vehicles or routes missing for the detected school. Skipping TransportAssignment seeding.');
            return;
        }

        $users = User::query()
            ->where('school_id', $schoolId)
            ->get();

        // Prepare a pool of drivers and conductors (any users for demo purposes)
        $drivers = $users->shuffle();
        $conductors = $users->shuffle();

        // Define shifts and default time windows
        $shiftWindows = [
            TransportAssignment::SHIFT_MORNING   => ['06:00', '12:00'],
            TransportAssignment::SHIFT_AFTERNOON => ['12:00', '18:00'],
            TransportAssignment::SHIFT_EVENING   => ['18:00', '22:00'],
            TransportAssignment::SHIFT_NIGHT     => ['22:00', '23:59'],
            TransportAssignment::SHIFT_FULL_DAY  => ['00:00', '23:59'],
        ];

        $statuses = [
            TransportAssignment::STATUS_PENDING,
            TransportAssignment::STATUS_ACTIVE,
            TransportAssignment::STATUS_COMPLETED,
            TransportAssignment::STATUS_CANCELLED,
            TransportAssignment::STATUS_DELAYED,
        ];

        DB::transaction(function () use ($schoolId, $vehicles, $routes, $drivers, $conductors, $shiftWindows, $statuses) {
            // Create 30 demo assignments across past, today, and future dates
            for ($i = 0; $i < 30; $i++) {
                $vehicle = $vehicles->random();
                $route = $routes->random();
                $shift = array_rand($shiftWindows);

                // Random date within -5 to +10 days from today
                $date = Carbon::today()->addDays(random_int(-5, 10));
                [$start, $end] = $shiftWindows[$shift];

                $status = $statuses[array_rand($statuses)];

                // Optional staff assignments
                $driver = $drivers->isNotEmpty() ? $drivers->random() : null;
                $conductor = $conductors->isNotEmpty() ? $conductors->random() : null;

                $isActive = in_array($status, [TransportAssignment::STATUS_ACTIVE, TransportAssignment::STATUS_PENDING], true);

                TransportAssignment::create([
                    'school_id'       => $schoolId,
                    'vehicle_id'      => $vehicle->id,
                    'route_id'        => $route->id,
                    'driver_id'       => $driver?->id,
                    'conductor_id'    => $conductor?->id,
                    'assignment_date' => $date->toDateString(),
                    'start_time'      => Carbon::parse($date->toDateString().' '.$start),
                    'end_time'        => Carbon::parse($date->toDateString().' '.$end),
                    'shift_type'      => $shift,
                    'status'          => $status,
                    'is_active'       => $isActive,
                    'notes'           => 'Auto-generated demo assignment '.Str::random(6),
                    'assigned_by'     => $driver?->id,
                    'assigned_at'     => $status === TransportAssignment::STATUS_ACTIVE ? Carbon::now()->subHours(random_int(1, 48)) : null,
                    'completed_at'    => $status === TransportAssignment::STATUS_COMPLETED ? Carbon::now()->subHours(random_int(1, 48)) : null,
                    'created_by'      => $driver?->id,
                    'updated_by'      => $driver?->id,
                ]);
            }
        });
    }
}



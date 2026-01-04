<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentTransport;
use App\Models\StudentDetail;
use App\Models\TransportRoute;
use App\Models\TransportVehicle;
use App\Models\SchoolClass;
use Illuminate\Support\Carbon;

class StudentTransportSeeder extends Seeder
{
    public function run(): void
    {
        $students = StudentDetail::limit(10)->get();
        if ($students->isEmpty()) {
            return;
        }

        $route = TransportRoute::first();
        $vehicle = TransportVehicle::first();
        $class = SchoolClass::first();

        foreach ($students as $student) {
            StudentTransport::updateOrCreate(
                [
                    'student_id' => $student->id,
                ],
                [
                    'school_id' => $student->school_id,
                    'class_id' => $class?->id,
                    'route_id' => $route?->id,
                    'vehicle_id' => $vehicle?->id,
                    'pickup_point' => 'Stop A',
                    'drop_point' => 'Stop B',
                    'start_date' => now()->subDays(rand(1, 60))->format('Y-m-d'),
                    'end_date' => null,
                    'fare' => $route?->fare ?? 300.00,
                    'status' => 'active',
                    'remarks' => 'Auto-assigned by seeder',
                ]
            );
        }
    }
}



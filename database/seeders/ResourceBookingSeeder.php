<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResourceBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('resource_bookings')->insert([
            [
                'resource_type'   => 'room',
                'resource_id'     => 101,
                'resource_name'   => 'Conference Room A',
                'title'           => 'Parent-Teacher Meeting',
                'description'     => 'Monthly PTM',
                'booked_by'       => 1,
                'school_id'       => 1,
                'start_time'      => Carbon::now()->addDay()->setTime(10, 0),
                'end_time'        => Carbon::now()->addDay()->setTime(12, 0),
                'status'          => 'approved',
                'approved_by'     => 3, // Example: admin id
                'approved_at'     => Carbon::now(),
                'rejection_reason'=> null,
                'deleted_at'      => null,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'resource_type'   => 'equipment',
                'resource_id'     => 12,
                'resource_name'   => 'Projector X1',
                'title'           => 'Guest Lecture',
                'description'     => 'Need projector for the lecture',
                'booked_by'       => 2,
                'school_id'       => 1,
                'start_time'      => Carbon::now()->addDays(2)->setTime(9, 30),
                'end_time'        => Carbon::now()->addDays(2)->setTime(11, 0),
                'status'          => 'pending',
                'approved_by'     => null,
                'approved_at'     => null,
                'rejection_reason'=> null,
                'deleted_at'      => null,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
        ]);
    }
    
}



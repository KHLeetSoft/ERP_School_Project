<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PtmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ptms')->insert([
            [
                'school_id' => 1,
                'title' => 'Monthly PTM - Primary',
                'description' => 'Discussion on academics and activities',
                'date' => Carbon::now()->addDays(7)->toDateString(),
                'start_time' => Carbon::now()->addDays(7)->setTime(10, 0),
                'end_time' => Carbon::now()->addDays(7)->setTime(13, 0),
                'status' => 'scheduled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'title' => 'Quarterly PTM - Secondary',
                'description' => 'Progress review and feedback',
                'date' => Carbon::now()->addDays(20)->toDateString(),
                'start_time' => Carbon::now()->addDays(20)->setTime(9, 30),
                'end_time' => Carbon::now()->addDays(20)->setTime(12, 30),
                'status' => 'scheduled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}



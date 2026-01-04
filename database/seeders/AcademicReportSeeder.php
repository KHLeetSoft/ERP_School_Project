<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicReportSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('academic_reports')->insert([
            [
                'school_id' => 1,
                'title' => 'Monthly Performance - September',
                'description' => 'Summary of academic performance for September',
                'report_date' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                'type' => 'performance',
                'status' => 'published',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'title' => 'Attendance Overview - Q3',
                'description' => 'Attendance report for Q3',
                'report_date' => Carbon::now()->subMonths(2)->startOfQuarter()->toDateString(),
                'type' => 'attendance',
                'status' => 'draft',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}



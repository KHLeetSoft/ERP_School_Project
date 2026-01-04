<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DocumentIdCardSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('document_id_cards')->insert([
            [
                'school_id' => 1,
                'student_id' => 1,
                'student_name' => 'Aarav Sharma',
                'class_name' => 'Class 8',
                'section_name' => 'A',
                'roll_number' => '18',
                'date_of_birth' => '2012-05-14',
                'blood_group' => 'B+',
                'address' => '123, Green Park, Delhi',
                'phone' => '9876543210',
                'guardian_name' => 'Rajesh Sharma',
                'issue_date' => Carbon::now()->subMonths(1)->toDateString(),
                'expiry_date' => Carbon::now()->addYear()->toDateString(),
                'photo_path' => null,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'student_id' => 2,
                'student_name' => 'Ishita Verma',
                'class_name' => 'Class 10',
                'section_name' => 'B',
                'roll_number' => '07',
                'date_of_birth' => '2010-01-21',
                'blood_group' => 'O-',
                'address' => '45, Lake View, Jaipur',
                'phone' => '9988776655',
                'guardian_name' => 'Suresh Verma',
                'issue_date' => Carbon::now()->subWeeks(2)->toDateString(),
                'expiry_date' => Carbon::now()->addYear()->toDateString(),
                'photo_path' => null,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}



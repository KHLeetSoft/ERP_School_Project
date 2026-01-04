<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentPayment;
use App\Models\StudentDetail;
use Carbon\Carbon;

class StudentPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;
        $students = StudentDetail::where('school_id', $schoolId)->inRandomOrder()->limit(10)->get();
        if ($students->isEmpty()) {
            return;
        }
        foreach (range(1, 20) as $n) {
            $student = $students->random();
            $date = Carbon::today()->subDays(rand(0, 60));
            StudentPayment::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'payment_date' => $date->toDateString(),
                'amount' => rand(500, 5000),
                'method' => collect(['cash','card','bank','online'])->random(),
                'reference' => 'REF-'.str_pad((string)rand(1,999999), 6, '0', STR_PAD_LEFT),
                'status' => collect(['completed','pending'])->random(),
                'notes' => 'Auto seeded payment',
                'created_by' => null,
                'updated_by' => null,
            ]);
        }
    }
}



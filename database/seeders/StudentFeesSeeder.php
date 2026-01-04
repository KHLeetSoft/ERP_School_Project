<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentFee;
use App\Models\StudentDetail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentFeesSeeder extends Seeder
{
    public function run(): void
    {
        $students = StudentDetail::all();
        if ($students->isEmpty()) {
            return;
        }

        $paymentModes = ['Cash', 'Card', 'Online', 'UPI', 'Bank'];

        foreach ($students as $student) {
            if (empty($student->class_id)) {
                continue;
            }

            $numFees = rand(1, 3);
            for ($i = 0; $i < $numFees; $i++) {
                StudentFee::create([
                    'student_id' => $student->id,
                    'class_id' => $student->class_id,
                    'amount' => mt_rand(50000, 150000) / 100, // 500.00 - 1500.00
                    'fee_date' => Carbon::now()->subDays(rand(0, 180))->format('Y-m-d'),
                    'payment_mode' => $paymentModes[array_rand($paymentModes)],
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                    'remarks' => 'Seeded fee record',
                ]);
            }
        }
    }
}



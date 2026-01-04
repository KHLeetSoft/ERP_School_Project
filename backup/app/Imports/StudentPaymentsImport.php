<?php

namespace App\Imports;

use App\Models\StudentPayment;
use App\Models\StudentDetail;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class StudentPaymentsImport implements ToCollection, WithHeadingRow
{
    public function __construct(private int $schoolId)
    {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $admissionNo = (string) ($row['admission_no'] ?? '');
            if ($admissionNo === '') {
                continue;
            }
            $student = StudentDetail::where('school_id', $this->schoolId)
                ->where('admission_no', $admissionNo)
                ->first();
            if (!$student) {
                continue;
            }

            $method = in_array(($row['method'] ?? 'cash'), ['cash','card','bank','online']) ? $row['method'] : 'cash';
            $status = in_array(($row['status'] ?? 'completed'), ['pending','completed','failed','refunded']) ? $row['status'] : 'completed';

            StudentPayment::create([
                'school_id' => $this->schoolId,
                'student_id' => $student->id,
                'payment_date' => $row['payment_date'] ?? now()->toDateString(),
                'amount' => (float) ($row['amount'] ?? 0),
                'method' => $method,
                'reference' => $row['reference'] ?? null,
                'status' => $status,
                'notes' => $row['notes'] ?? null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }
    }
}



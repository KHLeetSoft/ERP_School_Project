<?php

namespace App\Imports;

use App\Models\FeeStructure;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class FeeStructureImport implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(private int $schoolId) {}

    public function model(array $row)
    {
        // Find class by name
        $class = SchoolClass::where('school_id', $this->schoolId)
            ->where('name', $row['class'] ?? '')
            ->first();

        if (!$class) {
            return null; // Skip if class not found
        }

        return new FeeStructure([
            'school_id' => $this->schoolId,
            'class_id' => $class->id,
            'academic_year' => $row['academic_year'] ?? date('Y') . '-' . (date('Y') + 1),
            'fee_type' => $row['fee_type'] ?? 'Other Fee',
            'amount' => (float)($row['amount'] ?? 0),
            'frequency' => $this->normalizeFrequency($row['frequency'] ?? 'monthly'),
            'due_date' => $this->parseDate($row['due_date'] ?? null),
            'late_fee' => (float)($row['late_fee'] ?? 0),
            'discount_applicable' => strtolower($row['discount_applicable'] ?? 'no') === 'yes',
            'max_discount' => (float)($row['max_discount'] ?? 0),
            'description' => $row['description'] ?? null,
            'is_active' => strtolower($row['status'] ?? 'active') === 'active',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'class' => 'required|string',
            'academic_year' => 'nullable|string',
            'fee_type' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'nullable|string',
            'due_date' => 'nullable|string',
            'late_fee' => 'nullable|numeric|min:0',
            'discount_applicable' => 'nullable|string',
            'max_discount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|string'
        ];
    }

    private function normalizeFrequency(string $frequency): string
    {
        $frequency = strtolower(trim($frequency));
        
        return match($frequency) {
            'monthly', 'month' => 'monthly',
            'quarterly', 'quarter' => 'quarterly',
            'half_yearly', 'half yearly', 'half-yearly' => 'half_yearly',
            'yearly', 'year' => 'yearly',
            'one_time', 'one time', 'onetime' => 'one_time',
            default => 'monthly'
        };
    }

    private function parseDate(?string $date): ?string
    {
        if (!$date) return null;
        
        // Try different date formats
        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'd.m.Y'];
        
        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $date);
            if ($parsed !== false) {
                return $parsed->format('Y-m-d');
            }
        }
        
        return null;
    }
}

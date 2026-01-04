<?php

namespace App\Imports;

use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Carbon\Carbon;

class StaffImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsErrors
{
    public function __construct(private int $schoolId) {}

    public function model(array $row)
    {
        // Generate unique employee ID if not provided
        $employeeId = $row['employee_id'] ?? $this->generateEmployeeId();
        
        // Parse dates
        $dateOfBirth = $this->parseDate($row['date_of_birth'] ?? null);
        $joiningDate = $this->parseDate($row['joining_date'] ?? null);
        $contractEndDate = $this->parseDate($row['contract_end_date'] ?? null);
        
        // Normalize employment type
        $employmentType = $this->normalizeEmploymentType($row['employment_type'] ?? 'full_time');
        
        // Normalize gender
        $gender = $this->normalizeGender($row['gender'] ?? null);
        
        return new Staff([
            'school_id' => $this->schoolId,
            'employee_id' => $employeeId,
            'first_name' => $row['first_name'] ?? '',
            'last_name' => $row['last_name'] ?? '',
            'email' => $row['email'] ?? '',
            'phone' => $row['phone'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'gender' => $gender,
            'address' => $row['address'] ?? null,
            'city' => $row['city'] ?? null,
            'state' => $row['state'] ?? null,
            'country' => $row['country'] ?? 'India',
            'postal_code' => $row['postal_code'] ?? null,
            'designation' => $row['designation'] ?? 'Staff',
            'department' => $row['department'] ?? 'General',
            'joining_date' => $joiningDate ?? now(),
            'contract_end_date' => $contractEndDate,
            'salary' => (float)($row['salary'] ?? 0),
            'employment_type' => $employmentType,
            'status' => strtolower($row['status'] ?? 'active') === 'active',
            'emergency_contact_name' => $row['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $row['emergency_contact_phone'] ?? null,
            'bank_name' => $row['bank_name'] ?? null,
            'bank_account_number' => $row['bank_account_number'] ?? null,
            'ifsc_code' => $row['ifsc_code'] ?? null,
            'pan_number' => $row['pan_number'] ?? null,
            'aadhar_number' => $row['aadhar_number'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'designation' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
        ];
    }

    private function generateEmployeeId(): string
    {
        $prefix = 'EMP';
        $year = date('Y');
        $count = Staff::where('school_id', $this->schoolId)->count() + 1;
        return $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    private function parseDate(?string $date): ?string
    {
        if (!$date) return null;
        
        // Try different date formats
        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'd/m/Y H:i', 'd-m-Y H:i'];
        
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }
        
        return null;
    }

    private function normalizeEmploymentType(string $type): string
    {
        $type = strtolower(trim($type));
        
        return match($type) {
            'full time', 'fulltime', 'full' => 'full_time',
            'part time', 'parttime', 'part' => 'part_time',
            'contract', 'contractual' => 'contract',
            'intern', 'internship', 'trainee' => 'intern',
            default => 'full_time'
        };
    }

    private function normalizeGender(?string $gender): ?string
    {
        if (!$gender) return null;
        
        $gender = strtolower(trim($gender));
        
        return match($gender) {
            'm', 'male', 'm.' => 'male',
            'f', 'female', 'f.' => 'female',
            'o', 'other', 'o.' => 'other',
            default => null
        };
    }
}

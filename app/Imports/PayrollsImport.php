<?php

namespace App\Imports;

use App\Models\Payroll;
use App\Models\Staff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PayrollsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsErrors
{
    protected $schoolId;
    protected $errors = [];

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function model(array $row)
    {
        // Find staff by employee ID
        $staff = Staff::where('school_id', $this->schoolId)
            ->where('employee_id', $row['employee_id'])
            ->first();

        if (!$staff) {
            $this->errors[] = "Staff with Employee ID '{$row['employee_id']}' not found.";
            return null;
        }

        // Parse month and year from payroll period
        $payrollPeriod = $row['payroll_period'] ?? '';
        $month = null;
        $year = null;

        if (preg_match('/(\w+)\s+(\d{4})/', $payrollPeriod, $matches)) {
            $monthName = $matches[1];
            $year = (int) $matches[2];
            
            $monthMap = [
                'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
                'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
                'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
            ];
            
            $month = $monthMap[strtolower($monthName)] ?? null;
        }

        if (!$month || !$year) {
            $this->errors[] = "Invalid payroll period format for Employee ID '{$row['employee_id']}'. Expected format: 'Month Year' (e.g., 'January 2025')";
            return null;
        }

        // Check if payroll already exists
        $existingPayroll = Payroll::where('school_id', $this->schoolId)
            ->where('staff_id', $staff->id)
            ->where('payroll_month', $month)
            ->where('payroll_year', $year)
            ->first();

        if ($existingPayroll) {
            $this->errors[] = "Payroll already exists for Employee ID '{$row['employee_id']}' for {$payrollPeriod}";
            return null;
        }

        // Calculate gross and net salary
        $basicSalary = $this->parseAmount($row['basic_salary']);
        $houseRentAllowance = $this->parseAmount($row['house_rent_allowance'] ?? 0);
        $dearnessAllowance = $this->parseAmount($row['dearness_allowance'] ?? 0);
        $conveyanceAllowance = $this->parseAmount($row['conveyance_allowance'] ?? 0);
        $medicalAllowance = $this->parseAmount($row['medical_allowance'] ?? 0);
        $specialAllowance = $this->parseAmount($row['special_allowance'] ?? 0);
        $overtimePay = $this->parseAmount($row['overtime_pay'] ?? 0);
        $bonus = $this->parseAmount($row['bonus'] ?? 0);
        $incentives = $this->parseAmount($row['incentives'] ?? 0);
        $arrears = $this->parseAmount($row['arrears'] ?? 0);

        $grossSalary = $basicSalary + $houseRentAllowance + $dearnessAllowance + 
                      $conveyanceAllowance + $medicalAllowance + $specialAllowance + 
                      $overtimePay + $bonus + $incentives + $arrears;

        $providentFund = $this->parseAmount($row['provident_fund'] ?? 0);
        $taxDeduction = $this->parseAmount($row['tax_deduction'] ?? 0);
        $insuranceDeduction = $this->parseAmount($row['insurance_deduction'] ?? 0);
        $loanDeduction = $this->parseAmount($row['loan_deduction'] ?? 0);
        $otherDeductions = $this->parseAmount($row['other_deductions'] ?? 0);

        $netSalary = $grossSalary - $providentFund - $taxDeduction - 
                     $insuranceDeduction - $loanDeduction - $otherDeductions;

        // Parse payment date
        $paymentDate = null;
        if (!empty($row['payment_date']) && $row['payment_date'] !== 'N/A') {
            try {
                $paymentDate = Carbon::createFromFormat('d/m/Y', $row['payment_date']);
            } catch (\Exception $e) {
                $paymentDate = null;
            }
        }

        return new Payroll([
            'school_id' => $this->schoolId,
            'staff_id' => $staff->id,
            'payroll_month' => $month,
            'payroll_year' => $year,
            'basic_salary' => $basicSalary,
            'house_rent_allowance' => $houseRentAllowance,
            'dearness_allowance' => $dearnessAllowance,
            'conveyance_allowance' => $conveyanceAllowance,
            'medical_allowance' => $medicalAllowance,
            'special_allowance' => $specialAllowance,
            'overtime_pay' => $overtimePay,
            'bonus' => $bonus,
            'incentives' => $incentives,
            'arrears' => $arrears,
            'gross_salary' => $grossSalary,
            'provident_fund' => $providentFund,
            'tax_deduction' => $taxDeduction,
            'insurance_deduction' => $insuranceDeduction,
            'loan_deduction' => $loanDeduction,
            'other_deductions' => $otherDeductions,
            'net_salary' => $netSalary,
            'payment_method' => $this->normalizePaymentMethod($row['payment_method'] ?? 'bank_transfer'),
            'bank_name' => $row['bank_name'] ?? null,
            'account_number' => $row['account_number'] ?? null,
            'ifsc_code' => $row['ifsc_code'] ?? null,
            'status' => $this->normalizeStatus($row['status'] ?? 'pending'),
            'payment_date' => $paymentDate,
            'remarks' => $row['remarks'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required',
            'payroll_period' => 'required',
            'basic_salary' => 'required|numeric|min:0',
            'house_rent_allowance' => 'nullable|numeric|min:0',
            'dearness_allowance' => 'nullable|numeric|min:0',
            'conveyance_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'incentives' => 'nullable|numeric|min:0',
            'arrears' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'insurance_deduction' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:bank_transfer,cash,cheque,online',
            'status' => 'nullable|in:pending,approved,paid,rejected',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'employee_id.required' => 'Employee ID is required.',
            'payroll_period.required' => 'Payroll period is required.',
            'basic_salary.required' => 'Basic salary is required.',
            'basic_salary.numeric' => 'Basic salary must be a number.',
            'basic_salary.min' => 'Basic salary cannot be negative.',
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function parseAmount($value)
    {
        if (empty($value) || $value === 'N/A') {
            return 0;
        }

        // Remove currency symbols and commas
        $cleanValue = preg_replace('/[^\d.-]/', '', $value);
        
        return is_numeric($cleanValue) ? (float) $cleanValue : 0;
    }

    private function normalizePaymentMethod($method)
    {
        $methodMap = [
            'bank transfer' => 'bank_transfer',
            'bank_transfer' => 'bank_transfer',
            'cash' => 'cash',
            'cheque' => 'cheque',
            'check' => 'cheque',
            'online' => 'online',
        ];

        $normalized = strtolower(trim($method));
        return $methodMap[$normalized] ?? 'bank_transfer';
    }

    private function normalizeStatus($status)
    {
        $statusMap = [
            'pending' => 'pending',
            'approved' => 'approved',
            'paid' => 'paid',
            'rejected' => 'rejected',
        ];

        $normalized = strtolower(trim($status));
        return $statusMap[$normalized] ?? 'pending';
    }
}

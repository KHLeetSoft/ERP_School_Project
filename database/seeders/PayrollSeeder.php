<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payroll;
use App\Models\Staff;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = School::first()?->id ?? 1;
        $userId = User::first()?->id ?? 1;

        // Get all active staff
        $staff = Staff::where('school_id', $schoolId)->get();

        if ($staff->isEmpty()) {
            $this->command->info('No staff found. Please run StaffSeeder first.');
            return;
        }

        $payrollData = [];
        $currentYear = date('Y');
        $currentMonth = date('n');

        // Generate payrolls for the last 12 months
        for ($month = 1; $month <= 12; $month++) {
            $year = $month > $currentMonth ? $currentYear - 1 : $currentYear;
            
            foreach ($staff as $staffMember) {
                // Skip if payroll already exists for this staff and period
                $existingPayroll = Payroll::where('staff_id', $staffMember->id)
                    ->where('payroll_month', $month)
                    ->where('payroll_year', $year)
                    ->where('school_id', $schoolId)
                    ->exists();

                if ($existingPayroll) {
                    continue;
                }

                // Generate realistic salary data based on staff designation and department
                $salaryData = $this->generateSalaryData($staffMember, $month, $year);

                $payrollData[] = [
                    'school_id' => $schoolId,
                    'staff_id' => $staffMember->id,
                    'payroll_month' => $month,
                    'payroll_year' => $year,
                    'basic_salary' => $salaryData['basic_salary'],
                    'house_rent_allowance' => $salaryData['house_rent_allowance'],
                    'dearness_allowance' => $salaryData['dearness_allowance'],
                    'conveyance_allowance' => $salaryData['conveyance_allowance'],
                    'medical_allowance' => $salaryData['medical_allowance'],
                    'special_allowance' => $salaryData['special_allowance'],
                    'overtime_pay' => $salaryData['overtime_pay'],
                    'bonus' => $salaryData['bonus'],
                    'incentives' => $salaryData['incentives'],
                    'arrears' => $salaryData['arrears'],
                    'gross_salary' => $salaryData['gross_salary'],
                    'provident_fund' => $salaryData['provident_fund'],
                    'tax_deduction' => $salaryData['tax_deduction'],
                    'insurance_deduction' => $salaryData['insurance_deduction'],
                    'loan_deduction' => $salaryData['loan_deduction'],
                    'other_deductions' => $salaryData['other_deductions'],
                    'net_salary' => $salaryData['net_salary'],
                    'payment_method' => $salaryData['payment_method'],
                    'bank_name' => $staffMember->bank_name,
                    'account_number' => $staffMember->bank_account_number,
                    'ifsc_code' => $staffMember->ifsc_code,
                    'status' => $salaryData['status'],
                    'payment_date' => $salaryData['payment_date'],
                    'remarks' => $salaryData['remarks'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($payrollData, 100) as $chunk) {
            Payroll::insert($chunk);
        }

        $this->command->info('Payroll seeder completed successfully!');
        $this->command->info('Created ' . count($payrollData) . ' payroll records.');
    }

    private function generateSalaryData($staff, $month, $year): array
    {
        $basicSalary = $staff->salary;
        
        // Calculate allowances based on basic salary
        $houseRentAllowance = $basicSalary * (rand(20, 40) / 100);
        $dearnessAllowance = $basicSalary * (rand(10, 20) / 100);
        $conveyanceAllowance = rand(800, 1600);
        $medicalAllowance = rand(500, 1000);
        $specialAllowance = $basicSalary * (rand(15, 25) / 100);
        
        // Overtime pay (only for some months)
        $overtimePay = rand(1, 10) <= 3 ? rand(1000, 5000) : 0;
        
        // Bonus (only for certain months)
        $bonus = in_array($month, [3, 9, 12]) ? rand(5000, 15000) : 0;
        
        // Incentives (performance based)
        $incentives = rand(1, 10) <= 4 ? rand(1000, 8000) : 0;
        
        // Arrears (occasionally)
        $arrears = rand(1, 20) <= 2 ? rand(2000, 10000) : 0;
        
        // Calculate gross salary
        $grossSalary = $basicSalary + $houseRentAllowance + $dearnessAllowance + 
                      $conveyanceAllowance + $medicalAllowance + $specialAllowance + 
                      $overtimePay + $bonus + $incentives + $arrears;
        
        // Calculate deductions
        $providentFund = $basicSalary * 0.12;
        $taxDeduction = $this->calculateTaxDeduction($grossSalary);
        $insuranceDeduction = rand(200, 800);
        $loanDeduction = rand(1, 10) <= 3 ? rand(1000, 5000) : 0;
        $otherDeductions = rand(1, 10) <= 2 ? rand(500, 2000) : 0;
        
        // Calculate net salary
        $netSalary = $grossSalary - $providentFund - $taxDeduction - 
                     $insuranceDeduction - $loanDeduction - $otherDeductions;
        
        // Determine status based on month and year
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
            $status = 'paid';
            $paymentDate = Carbon::create($year, $month, rand(25, 30));
        } elseif ($year == $currentYear && $month == $currentMonth) {
            $status = 'pending';
            $paymentDate = null;
        } else {
            $status = 'pending';
            $paymentDate = null;
        }
        
        // Payment method
        $paymentMethods = ['bank_transfer', 'cash', 'cheque', 'online'];
        $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
        
        // Remarks
        $remarks = $this->generateRemarks($month, $year, $status, $bonus, $incentives);
        
        return [
            'basic_salary' => round($basicSalary, 2),
            'house_rent_allowance' => round($houseRentAllowance, 2),
            'dearness_allowance' => round($dearnessAllowance, 2),
            'conveyance_allowance' => round($conveyanceAllowance, 2),
            'medical_allowance' => round($medicalAllowance, 2),
            'special_allowance' => round($specialAllowance, 2),
            'overtime_pay' => round($overtimePay, 2),
            'bonus' => round($bonus, 2),
            'incentives' => round($incentives, 2),
            'arrears' => round($arrears, 2),
            'gross_salary' => round($grossSalary, 2),
            'provident_fund' => round($providentFund, 2),
            'tax_deduction' => round($taxDeduction, 2),
            'insurance_deduction' => round($insuranceDeduction, 2),
            'loan_deduction' => round($loanDeduction, 2),
            'other_deductions' => round($otherDeductions, 2),
            'net_salary' => round($netSalary, 2),
            'payment_method' => $paymentMethod,
            'status' => $status,
            'payment_date' => $paymentDate,
            'remarks' => $remarks,
        ];
    }

    private function calculateTaxDeduction($grossSalary): float
    {
        if ($grossSalary <= 250000) {
            return 0;
        } elseif ($grossSalary <= 500000) {
            return ($grossSalary - 250000) * 0.05;
        } elseif ($grossSalary <= 1000000) {
            return 12500 + ($grossSalary - 500000) * 0.2;
        } else {
            return 112500 + ($grossSalary - 1000000) * 0.3;
        }
    }

    private function generateRemarks($month, $year, $status, $bonus, $incentives): string
    {
        $remarks = [];

        if ($bonus > 0) {
            $remarks[] = "Festival bonus included";
        }

        if ($incentives > 0) {
            $remarks[] = "Performance incentives added";
        }

        if ($month == 3) {
            $remarks[] = "Financial year end processing";
        }

        if ($month == 12) {
            $remarks[] = "Year-end bonus and adjustments";
        }

        if ($status === 'paid') {
            $remarks[] = "Payment completed on time";
        }

        if (empty($remarks)) {
            $remarks[] = "Regular monthly payroll";
        }

        return implode(', ', $remarks);
    }
}

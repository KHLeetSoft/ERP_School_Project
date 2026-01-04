<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $schoolId;
    protected $filters;

    public function __construct($schoolId, $filters = [])
    {
        $this->schoolId = $schoolId;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Payroll::with(['staff', 'createdBy', 'updatedBy'])
            ->where('school_id', $this->schoolId);

        // Apply filters
        if (!empty($this->filters['month'])) {
            $query->where('payroll_month', $this->filters['month']);
        }

        if (!empty($this->filters['year'])) {
            $query->where('payroll_year', $this->filters['year']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['staff_id'])) {
            $query->where('staff_id', $this->filters['staff_id']);
        }

        return $query->orderBy('payroll_year', 'desc')
                    ->orderBy('payroll_month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee ID',
            'Employee Name',
            'Payroll Period',
            'Basic Salary',
            'House Rent Allowance',
            'Dearness Allowance',
            'Conveyance Allowance',
            'Medical Allowance',
            'Special Allowance',
            'Overtime Pay',
            'Bonus',
            'Incentives',
            'Arrears',
            'Gross Salary',
            'Provident Fund',
            'Tax Deduction',
            'Insurance Deduction',
            'Loan Deduction',
            'Other Deductions',
            'Net Salary',
            'Payment Method',
            'Bank Name',
            'Account Number',
            'IFSC Code',
            'Status',
            'Payment Date',
            'Remarks',
            'Created By',
            'Created At',
            'Updated By',
            'Updated At'
        ];
    }

    public function map($payroll): array
    {
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return [
            $payroll->id,
            $payroll->staff ? $payroll->staff->employee_id : 'N/A',
            $payroll->staff ? $payroll->staff->first_name . ' ' . $payroll->staff->last_name : 'N/A',
            $monthNames[$payroll->payroll_month] . ' ' . $payroll->payroll_year,
            number_format($payroll->basic_salary, 2),
            number_format($payroll->house_rent_allowance, 2),
            number_format($payroll->dearness_allowance, 2),
            number_format($payroll->conveyance_allowance, 2),
            number_format($payroll->medical_allowance, 2),
            number_format($payroll->special_allowance, 2),
            number_format($payroll->overtime_pay, 2),
            number_format($payroll->bonus, 2),
            number_format($payroll->incentives, 2),
            number_format($payroll->arrears, 2),
            number_format($payroll->gross_salary, 2),
            number_format($payroll->provident_fund, 2),
            number_format($payroll->tax_deduction, 2),
            number_format($payroll->insurance_deduction, 2),
            number_format($payroll->loan_deduction, 2),
            number_format($payroll->other_deductions, 2),
            number_format($payroll->net_salary, 2),
            ucfirst(str_replace('_', ' ', $payroll->payment_method)),
            $payroll->bank_name ?? 'N/A',
            $payroll->account_number ?? 'N/A',
            $payroll->ifsc_code ?? 'N/A',
            ucfirst($payroll->status),
            $payroll->payment_date ? $payroll->payment_date->format('d/m/Y') : 'N/A',
            $payroll->remarks ?? 'N/A',
            $payroll->createdBy ? $payroll->createdBy->name : 'N/A',
            $payroll->created_at->format('d/m/Y H:i:s'),
            $payroll->updatedBy ? $payroll->updatedBy->name : 'N/A',
            $payroll->updated_at->format('d/m/Y H:i:s')
        ];
    }
}

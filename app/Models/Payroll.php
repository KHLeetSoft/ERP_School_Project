<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id', 'staff_id', 'payroll_month', 'payroll_year', 'basic_salary',
        'house_rent_allowance', 'dearness_allowance', 'conveyance_allowance',
        'medical_allowance', 'special_allowance', 'overtime_pay', 'bonus',
        'incentives', 'arrears', 'gross_salary', 'provident_fund', 'tax_deduction',
        'insurance_deduction', 'loan_deduction', 'other_deductions', 'net_salary',
        'payment_method', 'bank_name', 'account_number', 'ifsc_code', 'payment_date',
        'status', 'remarks', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'payroll_month' => 'integer',
        'payroll_year' => 'integer',
        'basic_salary' => 'decimal:2',
        'house_rent_allowance' => 'decimal:2',
        'dearness_allowance' => 'decimal:2',
        'conveyance_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'special_allowance' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonus' => 'decimal:2',
        'incentives' => 'decimal:2',
        'arrears' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'provident_fund' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
        'status' => 'string'
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getPayrollPeriodAttribute(): string
    {
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        return $monthNames[$this->payroll_month] . ' ' . $this->payroll_year;
    }

    public function getTotalAllowancesAttribute(): float
    {
        return $this->house_rent_allowance + $this->dearness_allowance + 
               $this->conveyance_allowance + $this->medical_allowance + 
               $this->special_allowance + $this->overtime_pay + 
               $this->bonus + $this->incentives + $this->arrears;
    }

    public function getTotalDeductionsAttribute(): float
    {
        return $this->provident_fund + $this->tax_deduction + 
               $this->insurance_deduction + $this->loan_deduction + 
               $this->other_deductions;
    }

    public function getFormattedBasicSalaryAttribute(): string
    {
        return '₹' . number_format($this->basic_salary, 2);
    }

    public function getFormattedGrossSalaryAttribute(): string
    {
        return '₹' . number_format($this->gross_salary, 2);
    }

    public function getFormattedNetSalaryAttribute(): string
    {
        return '₹' . number_format($this->net_salary, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-info">Approved</span>',
            'paid' => '<span class="badge bg-success">Paid</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }

    // Scopes
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    public function scopeByMonth($query, $month)
    {
        return $query->where('payroll_month', $month);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('payroll_year', $year);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Methods
    public function calculateGrossSalary(): float
    {
        return $this->basic_salary + $this->getTotalAllowancesAttribute();
    }

    public function calculateNetSalary(): float
    {
        return $this->gross_salary - $this->getTotalDeductionsAttribute();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    public function canBePaid(): bool
    {
        return $this->status === 'approved';
    }

    public function canBeRejected(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }
}

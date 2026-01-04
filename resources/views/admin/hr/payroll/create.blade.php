@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Create New Payroll</h6>
        <a href="{{ route('admin.hr.payroll.index') }}" class="btn btn-sm btn-secondary">
            <i class="bx bx-left-arrow-alt"></i> Back to List
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header">
            <h6 class="mb-0">Payroll Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hr.payroll.store') }}" method="POST" id="payrollForm">
                @csrf
                
                <div class="row g-3">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <label for="staff_id" class="form-label">Staff Member <span class="text-danger">*</span></label>
                        <select name="staff_id" id="staff_id" class="form-select @error('staff_id') is-invalid @enderror" required>
                            <option value="">Select Staff Member</option>
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}" {{ old('staff_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->first_name }} {{ $member->last_name }} ({{ $member->employee_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('staff_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="payroll_month" class="form-label">Month <span class="text-danger">*</span></label>
                        <select name="payroll_month" id="payroll_month" class="form-select @error('payroll_month') is-invalid @enderror" required>
                            <option value="">Select Month</option>
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ old('payroll_month') == $key ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                        @error('payroll_month')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="payroll_year" class="form-label">Year <span class="text-danger">*</span></label>
                        <select name="payroll_year" id="payroll_year" class="form-select @error('payroll_year') is-invalid @enderror" required>
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ old('payroll_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        @error('payroll_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Salary Structure -->
                    <div class="col-12">
                        <h6 class="text-primary mt-3 mb-2">Salary Structure</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="basic_salary" class="form-label">Basic Salary <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="basic_salary" id="basic_salary" 
                                   class="form-control @error('basic_salary') is-invalid @enderror" 
                                   value="{{ old('basic_salary') }}" required>
                        </div>
                        @error('basic_salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="house_rent_allowance" class="form-label">House Rent Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="house_rent_allowance" id="house_rent_allowance" 
                                   class="form-control @error('house_rent_allowance') is-invalid @enderror" 
                                   value="{{ old('house_rent_allowance', 0) }}">
                        </div>
                        @error('house_rent_allowance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="dearness_allowance" class="form-label">Dearness Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="dearness_allowance" id="dearness_allowance" 
                                   class="form-control @error('dearness_allowance') is-invalid @enderror" 
                                   value="{{ old('dearness_allowance', 0) }}">
                        </div>
                        @error('dearness_allowance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="conveyance_allowance" class="form-label">Conveyance Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="conveyance_allowance" id="conveyance_allowance" 
                                   class="form-control @error('conveyance_allowance') is-invalid @enderror" 
                                   value="{{ old('conveyance_allowance', 0) }}">
                        </div>
                        @error('conveyance_allowance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="medical_allowance" class="form-label">Medical Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="medical_allowance" id="medical_allowance" 
                                   class="form-control @error('medical_allowance') is-invalid @enderror" 
                                   value="{{ old('medical_allowance', 0) }}">
                        </div>
                        @error('medical_allowance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="special_allowance" class="form-label">Special Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="special_allowance" id="special_allowance" 
                                   class="form-control @error('special_allowance') is-invalid @enderror" 
                                   value="{{ old('special_allowance', 0) }}">
                        </div>
                        @error('special_allowance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="overtime_pay" class="form-label">Overtime Pay</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="overtime_pay" id="overtime_pay" 
                                   class="form-control @error('overtime_pay') is-invalid @enderror" 
                                   value="{{ old('overtime_pay', 0) }}">
                        </div>
                        @error('overtime_pay')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bonus" class="form-label">Bonus</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="bonus" id="bonus" 
                                   class="form-control @error('bonus') is-invalid @enderror" 
                                   value="{{ old('bonus', 0) }}">
                        </div>
                        @error('bonus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="incentives" class="form-label">Incentives</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="incentives" id="incentives" 
                                   class="form-control @error('incentives') is-invalid @enderror" 
                                   value="{{ old('incentives', 0) }}">
                        </div>
                        @error('incentives')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="arrears" class="form-label">Arrears</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="arrears" id="arrears" 
                                   class="form-control @error('arrears') is-invalid @enderror" 
                                   value="{{ old('arrears', 0) }}">
                        </div>
                        @error('arrears')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deductions -->
                    <div class="col-12">
                        <h6 class="text-primary mt-3 mb-2">Deductions</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="provident_fund" class="form-label">Provident Fund</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="provident_fund" id="provident_fund" 
                                   class="form-control @error('provident_fund') is-invalid @enderror" 
                                   value="{{ old('provident_fund', 0) }}">
                        </div>
                        @error('provident_fund')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tax_deduction" class="form-label">Tax Deduction</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="tax_deduction" id="tax_deduction" 
                                   class="form-control @error('tax_deduction') is-invalid @enderror" 
                                   value="{{ old('tax_deduction', 0) }}">
                        </div>
                        @error('tax_deduction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="insurance_deduction" class="form-label">Insurance Deduction</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="insurance_deduction" id="insurance_deduction" 
                                   class="form-control @error('insurance_deduction') is-invalid @enderror" 
                                   value="{{ old('insurance_deduction', 0) }}">
                        </div>
                        @error('insurance_deduction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="loan_deduction" class="form-label">Loan Deduction</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="loan_deduction" id="loan_deduction" 
                                   class="form-control @error('loan_deduction') is-invalid @enderror" 
                                   value="{{ old('loan_deduction', 0) }}">
                        </div>
                        @error('loan_deduction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="other_deductions" class="form-label">Other Deductions</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" name="other_deductions" id="other_deductions" 
                                   class="form-control @error('other_deductions') is-invalid @enderror" 
                                   value="{{ old('other_deductions', 0) }}">
                        </div>
                        @error('other_deductions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Payment Information -->
                    <div class="col-12">
                        <h6 class="text-primary mt-3 mb-2">Payment Information</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                            <option value="">Select Payment Method</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method }}" {{ old('payment_method') == $method ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $method)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name" 
                               class="form-control @error('bank_name') is-invalid @enderror" 
                               value="{{ old('bank_name') }}" maxlength="100">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" name="account_number" id="account_number" 
                               class="form-control @error('account_number') is-invalid @enderror" 
                               value="{{ old('account_number') }}" maxlength="50">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="ifsc_code" class="form-label">IFSC Code</label>
                        <input type="text" name="ifsc_code" id="ifsc_code" 
                               class="form-control @error('ifsc_code') is-invalid @enderror" 
                               value="{{ old('ifsc_code') }}" maxlength="20">
                        @error('ifsc_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" 
                                  class="form-control @error('remarks') is-invalid @enderror" 
                                  maxlength="500">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Summary -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Salary Summary</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Total Allowances:</strong> <span id="totalAllowances">₹0.00</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Total Deductions:</strong> <span id="totalDeductions">₹0.00</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Net Salary:</strong> <span id="netSalary">₹0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.hr.payroll.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Payroll</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Calculate totals when salary fields change
    $('input[name*="allowance"], input[name*="pay"], input[name*="bonus"], input[name*="incentives"], input[name*="arrears"]').on('input', calculateTotals);
    $('input[name*="deduction"], input[name*="fund"]').on('input', calculateTotals);
    $('#basic_salary').on('input', calculateTotals);

    function calculateTotals() {
        var basicSalary = parseFloat($('#basic_salary').val()) || 0;
        
        // Calculate total allowances
        var totalAllowances = basicSalary;
        $('input[name*="allowance"], input[name*="pay"], input[name*="bonus"], input[name*="incentives"], input[name*="arrears"]').each(function() {
            totalAllowances += parseFloat($(this).val()) || 0;
        });
        
        // Calculate total deductions
        var totalDeductions = 0;
        $('input[name*="deduction"], input[name*="fund"]').each(function() {
            totalDeductions += parseFloat($(this).val()) || 0;
        });
        
        // Calculate net salary
        var netSalary = totalAllowances - totalDeductions;
        
        // Update display
        $('#totalAllowances').text('₹' + totalAllowances.toFixed(2));
        $('#totalDeductions').text('₹' + totalDeductions.toFixed(2));
        $('#netSalary').text('₹' + netSalary.toFixed(2));
    }

    // Initial calculation
    calculateTotals();
});
</script>
@endsection

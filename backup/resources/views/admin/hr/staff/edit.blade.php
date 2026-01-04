@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Edit Staff Member</h6>
        <a href="{{ route('admin.hr.staff.show', $staff->id) }}" class="btn btn-sm btn-secondary">
            <i class="bx bx-left-arrow-alt"></i> Back to Details
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header">
            <h6 class="mb-0">Edit Staff Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hr.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <!-- Basic Information -->
                    <div class="col-12">
                        <h6 class="text-primary">Basic Information</h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                               name="employee_id" value="{{ old('employee_id', $staff->employee_id) }}" required>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                               name="photo" accept="image/*">
                        @if($staff->photo)
                            <small class="form-text text-muted">Current: {{ basename($staff->photo) }}</small>
                        @endif
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                               name="first_name" value="{{ old('first_name', $staff->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                               name="last_name" value="{{ old('last_name', $staff->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', $staff->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone', $staff->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                               name="date_of_birth" value="{{ old('date_of_birth', $staff->date_of_birth?->format('Y-m-d')) }}">
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $staff->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $staff->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $staff->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Address Information -->
                    <div class="col-12">
                        <h6 class="text-primary mt-4">Address Information</h6>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  name="address" rows="2">{{ old('address', $staff->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                               name="city" value="{{ old('city', $staff->city) }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input type="text" class="form-control @error('state') is-invalid @enderror" 
                               name="state" value="{{ old('state', $staff->state) }}">
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Postal Code</label>
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                               name="postal_code" value="{{ old('postal_code', $staff->postal_code) }}">
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Employment Information -->
                    <div class="col-12">
                        <h6 class="text-primary mt-4">Employment Information</h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-select @error('department') is-invalid @enderror" name="department" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ old('department', $staff->department) == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                        <select class="form-select @error('designation') is-invalid @enderror" name="designation" required>
                            <option value="">Select Designation</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig }}" {{ old('designation', $staff->designation) == $desig ? 'selected' : '' }}>
                                    {{ $desig }}
                                </option>
                            @endforeach
                        </select>
                        @error('designation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                               name="hire_date" value="{{ old('hire_date', $staff->hire_date->format('Y-m-d')) }}" required>
                        @error('hire_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Contract End Date</label>
                        <input type="date" class="form-control @error('contract_end_date') is-invalid @enderror" 
                               name="contract_end_date" value="{{ old('contract_end_date', $staff->contract_end_date?->format('Y-m-d')) }}">
                        @error('contract_end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Salary <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('salary') is-invalid @enderror" 
                               name="salary" value="{{ old('salary', $staff->salary) }}" step="0.01" min="0" required>
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Employment Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('employment_type') is-invalid @enderror" name="employment_type" required>
                            <option value="">Select Employment Type</option>
                            @foreach($employmentTypes as $key => $type)
                                <option value="{{ $key }}" {{ old('employment_type', $staff->employment_type) == $key ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('employment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Emergency Contact -->
                    <div class="col-12">
                        <h6 class="text-primary mt-4">Emergency Contact</h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Emergency Contact Name</label>
                        <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                               name="emergency_contact_name" value="{{ old('emergency_contact_name', $staff->emergency_contact_name) }}">
                        @error('emergency_contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Emergency Contact Phone</label>
                        <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                               name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $staff->emergency_contact_phone) }}">
                        @error('emergency_contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Bank Information -->
                    <div class="col-12">
                        <h6 class="text-primary mt-4">Bank Information</h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Bank Name</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                               name="bank_name" value="{{ old('bank_name', $staff->bank_name) }}">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Bank Account Number</label>
                        <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                               name="bank_account_number" value="{{ old('bank_account_number', $staff->bank_account_number) }}">
                        @error('bank_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">IFSC Code</label>
                        <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" 
                               name="ifsc_code" value="{{ old('ifsc_code', $staff->ifsc_code) }}">
                        @error('ifsc_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Government IDs -->
                    <div class="col-12">
                        <h6 class="text-primary mt-4">Government IDs</h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">PAN Number</label>
                        <input type="text" class="form-control @error('pan_number') is-invalid @enderror" 
                               name="pan_number" value="{{ old('pan_number', $staff->pan_number) }}">
                        @error('pan_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Aadhar Number</label>
                        <input type="text" class="form-control @error('aadhar_number') is-invalid @enderror" 
                               name="aadhar_number" value="{{ old('aadhar_number', $staff->aadhar_number) }}">
                        @error('aadhar_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div class="col-12">
                        <h6 class="text-primary mt-4">Status</h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" 
                                   {{ old('status', $staff->status) ? 'checked' : '' }} id="statusCheck">
                            <label class="form-check-label" for="statusCheck">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Update Staff Member
                        </button>
                        <a href="{{ route('admin.hr.staff.show', $staff->id) }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

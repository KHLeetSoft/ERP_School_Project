@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Staff Details</h6>
        <div>
            <a href="{{ route('admin.hr.staff.edit', $staff->id) }}" class="btn btn-sm btn-primary me-2">
                <i class="bx bx-edit"></i> Edit
            </a>
            <a href="{{ route('admin.hr.staff.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-left-arrow-alt"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row g-3">
        <!-- Basic Information -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Basic Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Employee ID</label>
                            <p class="mb-0">{{ $staff->employee_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <p class="mb-0">{{ $staff->full_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <p class="mb-0">{{ $staff->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <p class="mb-0">{{ $staff->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <p class="mb-0">{{ $staff->date_of_birth ? $staff->date_of_birth->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gender</label>
                            <p class="mb-0">{{ $staff->gender_label ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Age</label>
                            <p class="mb-0">{{ $staff->age }} years</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <p class="mb-0">{!! $staff->status_badge !!}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Address Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <p class="mb-0">{{ $staff->address ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City</label>
                            <p class="mb-0">{{ $staff->city ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">State</label>
                            <p class="mb-0">{{ $staff->state ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Postal Code</label>
                            <p class="mb-0">{{ $staff->postal_code ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Employment Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department</label>
                            <p class="mb-0">{{ $staff->department }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Designation</label>
                            <p class="mb-0">{{ $staff->designation }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Joining Date</label>
                            <p class="mb-0">{{ $staff->joining_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Contract End Date</label>
                            <p class="mb-0">{{ $staff->contract_end_date ? $staff->contract_end_date->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Employment Type</label>
                            <p class="mb-0">{{ $staff->employment_type_label }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Experience</label>
                            <p class="mb-0">{{ $staff->experience }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Salary</label>
                            <p class="mb-0">{{ $staff->formatted_salary }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Profile Photo -->
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Profile Photo</h6>
                </div>
                <div class="card-body text-center">
                    @if($staff->profile_photo)
                        <img src="{{ asset('storage/' . $staff->profile_photo) }}" 
                             alt="Profile Photo" class="img-fluid rounded" style="max-width: 200px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="width: 200px; height: 200px;">
                            <i class="bx bx-user text-muted" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Emergency Contact</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contact Name</label>
                        <p class="mb-0">{{ $staff->emergency_contact_name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Contact Phone</label>
                        <p class="mb-0">{{ $staff->emergency_contact_phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Bank Information -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Bank Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bank Name</label>
                        <p class="mb-0">{{ $staff->bank_name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Account Number</label>
                        <p class="mb-0">{{ $staff->bank_account_number ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">IFSC Code</label>
                        <p class="mb-0">{{ $staff->ifsc_code ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Government IDs -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Government IDs</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">PAN Number</label>
                        <p class="mb-0">{{ $staff->pan_number ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Aadhar Number</label>
                        <p class="mb-0">{{ $staff->aadhar_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('superadmin.app')

@section('title', 'Edit School QR Code')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="qr-icon me-3">
                            <i class="bx bx-edit fs-1 text-warning"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">Edit School QR Code</h1>
                            <p class="text-muted mb-0">Update QR code details and settings</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-end">
                        <a href="{{ route('superadmin.payment.school-qr-codes.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-2"></i>Back to QR Codes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-edit me-2"></i>QR Code Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.payment.school-qr-codes.update', $schoolQrCode->id) }}" method="POST" id="qrCodeForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- School Selection -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-building me-2"></i>School Selection
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="school_id" class="form-label fw-bold">Select School <span class="text-danger">*</span></label>
                                        <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id" required>
                                            <option value="">Choose a school...</option>
                                            @foreach($schools as $school)
                                                <option value="{{ $school->id }}" {{ old('school_id', $schoolQrCode->school_id) == $school->id ? 'selected' : '' }}>
                                                    {{ $school->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('school_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- QR Code Information -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-qr-scan me-2"></i>QR Code Information
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="title" class="form-label fw-bold">QR Code Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $schoolQrCode->title) }}" 
                                               placeholder="e.g., School Fee Payment" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">A unique title for this QR code</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="description" class="form-label fw-bold">Description</label>
                                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                               id="description" name="description" value="{{ old('description', $schoolQrCode->description) }}" 
                                               placeholder="Optional description">
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- UPI Details -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-credit-card me-2"></i>UPI Payment Details
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="upi_id" class="form-label fw-bold">UPI ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('upi_id') is-invalid @enderror" 
                                               id="upi_id" name="upi_id" value="{{ old('upi_id', $schoolQrCode->upi_id) }}" 
                                               placeholder="e.g., school@paytm" required>
                                        @error('upi_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Enter the school's UPI ID</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="merchant_name" class="form-label fw-bold">Merchant Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('merchant_name') is-invalid @enderror" 
                                               id="merchant_name" name="merchant_name" value="{{ old('merchant_name', $schoolQrCode->merchant_name) }}" 
                                               placeholder="e.g., School Name" required>
                                        @error('merchant_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Name that will appear in UPI app</small>
                                    </div>
                                </div>

                                <!-- Amount Settings -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-rupee me-2"></i>Amount Settings
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label fw-bold">Fixed Amount (₹)</label>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" name="amount" value="{{ old('amount', $schoolQrCode->amount) }}" 
                                               placeholder="0.00" step="0.01" min="0">
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Leave empty for variable amount</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   {{ old('is_active', $schoolQrCode->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_active">
                                                Active QR Code
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Enable this QR code for use</small>
                                    </div>
                                </div>

                                <!-- Current QR Code Preview -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-show me-2"></i>Current QR Code
                                        </h6>
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                @if($schoolQrCode->qr_code_image)
                                                    <div class="mb-3">
                                                        <img src="{{ asset('storage/' . $schoolQrCode->qr_code_image) }}" 
                                                             alt="Current QR Code" class="img-thumbnail" style="max-width: 200px;">
                                                    </div>
                                                @else
                                                    <div class="qr-placeholder">
                                                        <i class="bx bx-qr-scan fs-1 text-muted"></i>
                                                        <p class="text-muted mt-2">No QR Code Image</p>
                                                    </div>
                                                @endif
                                                <div class="text-muted">
                                                    <small>Current UPI String: <span id="currentUpiString">{{ $schoolQrCode->qr_code_data }}</span></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- New Preview Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-refresh me-2"></i>Updated Preview
                                        </h6>
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <div id="qrPreview" class="mb-3">
                                                    <div class="qr-placeholder">
                                                        <i class="bx bx-qr-scan fs-1 text-muted"></i>
                                                        <p class="text-muted mt-2">New QR Code will be generated after form submission</p>
                                                    </div>
                                                </div>
                                                <div id="upiPreview" class="text-muted">
                                                    <small>New UPI String: <span id="upiString">upi://pay?pa=example@paytm&pn=School Name&cu=INR</span></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('superadmin.payment.school-qr-codes.index') }}" class="btn btn-secondary">
                                        <i class="bx bx-x me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="bx bx-save me-2"></i>Update QR Code
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- QR Code Info -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-info-circle me-2"></i>QR Code Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>School:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $schoolQrCode->school->name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-6">
                                    @if($schoolQrCode->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Usage Count:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $schoolQrCode->usage_count }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Created:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $schoolQrCode->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Last Updated:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $schoolQrCode->updated_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-cog me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('superadmin.payment.school-qr-codes.show', $schoolQrCode->id) }}" class="btn btn-outline-info">
                                    <i class="bx bx-show me-2"></i>View Details
                                </a>
                                <a href="{{ route('superadmin.payment.school-qr-codes.download', $schoolQrCode->id) }}" class="btn btn-outline-success">
                                    <i class="bx bx-download me-2"></i>Download QR Code
                                </a>
                                <button class="btn btn-outline-warning" onclick="toggleStatus({{ $schoolQrCode->id }})">
                                    <i class="bx bx-{{ $schoolQrCode->is_active ? 'pause' : 'play' }} me-2"></i>
                                    {{ $schoolQrCode->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Warning -->
                    <div class="alert alert-warning mt-3">
                        <h6 class="fw-bold">Important:</h6>
                        <p class="mb-0">Changing UPI details will regenerate the QR code image. The old image will be deleted.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.qr-icon {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
}

.form-label {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e1e5e9;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.btn {
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.qr-placeholder {
    padding: 2rem;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    background-color: #f8f9fa;
}

.alert {
    border-radius: 8px;
    border: none;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
}
</style>

<script>
$(document).ready(function() {
    // Update UPI preview when form fields change
    function updateUPIPreview() {
        const upiId = $('#upi_id').val() || 'example@paytm';
        const merchantName = $('#merchant_name').val() || 'School Name';
        const amount = $('#amount').val();
        
        let upiString = `upi://pay?pa=${upiId}&pn=${merchantName}`;
        if (amount && amount > 0) {
            upiString += `&am=${amount}`;
        }
        upiString += '&cu=INR';
        
        $('#upiString').text(upiString);
    }

    // Bind events to form fields
    $('#upi_id, #merchant_name, #amount').on('input', updateUPIPreview);

    // Form validation
    $('#qrCodeForm').on('submit', function(e) {
        const schoolId = $('#school_id').val();
        const title = $('#title').val();
        const upiId = $('#upi_id').val();
        const merchantName = $('#merchant_name').val();

        if (!schoolId || !title || !upiId || !merchantName) {
            e.preventDefault();
            showAlert('Please fill in all required fields!', 'error');
            return false;
        }

        // Show loading state
        $(this).find('button[type="submit"]').html('<i class="bx bx-loader-alt bx-spin me-2"></i>Updating...').prop('disabled', true);
    });

    // Initialize preview
    updateUPIPreview();
});

function toggleStatus(qrCodeId) {
    $.ajax({
        url: "{{ route('superadmin.payment.school-qr-codes.toggle-status', '') }}/" + qrCodeId,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function() {
            showAlert('Something went wrong!', 'error');
        }
    });
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'error-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection

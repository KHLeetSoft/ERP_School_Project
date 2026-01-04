@extends('superadmin.app')

@section('title', 'Create School QR Code')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="qr-icon me-3">
                            <i class="bx bx-qr-scan fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">Create School QR Code</h1>
                            <p class="text-muted mb-0">Generate a new payment QR code for a school</p>
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
                                <i class="bx bx-plus-circle me-2"></i>QR Code Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.payment.school-qr-codes.store') }}" method="POST" id="qrCodeForm">
                                @csrf
                                
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
                                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
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
                                               id="title" name="title" value="{{ old('title') }}" 
                                               placeholder="e.g., School Fee Payment" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">A unique title for this QR code</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="description" class="form-label fw-bold">Description</label>
                                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                               id="description" name="description" value="{{ old('description') }}" 
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
                                               id="upi_id" name="upi_id" value="{{ old('upi_id') }}" 
                                               placeholder="e.g., school@paytm" required>
                                        @error('upi_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Enter the school's UPI ID</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="merchant_name" class="form-label fw-bold">Merchant Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('merchant_name') is-invalid @enderror" 
                                               id="merchant_name" name="merchant_name" value="{{ old('merchant_name') }}" 
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
                                               id="amount" name="amount" value="{{ old('amount') }}" 
                                               placeholder="0.00" step="0.01" min="0">
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Leave empty for variable amount</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_active">
                                                Active QR Code
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Enable this QR code for use</small>
                                    </div>
                                </div>

                                <!-- Preview Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-show me-2"></i>Preview
                                        </h6>
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <div id="qrPreview" class="mb-3">
                                                    <div class="qr-placeholder">
                                                        <i class="bx bx-qr-scan fs-1 text-muted"></i>
                                                        <p class="text-muted mt-2">QR Code will be generated after form submission</p>
                                                    </div>
                                                </div>
                                                <div id="upiPreview" class="text-muted">
                                                    <small>UPI String: <span id="upiString">upi://pay?pa=example@paytm&pn=School Name&cu=INR</span></small>
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
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bx bx-plus me-2"></i>Generate QR Code
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-help-circle me-2"></i>Help & Guidelines
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6 class="fw-bold">QR Code Guidelines:</h6>
                                <ul class="mb-0">
                                    <li>Each school can have multiple QR codes</li>
                                    <li>QR code titles must be unique per school</li>
                                    <li>UPI ID should be valid and active</li>
                                    <li>Fixed amount is optional - leave empty for variable</li>
                                </ul>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6 class="fw-bold">Important Notes:</h6>
                                <ul class="mb-0">
                                    <li>QR code image will be generated automatically</li>
                                    <li>QR codes can be downloaded after creation</li>
                                    <li>Status can be toggled anytime</li>
                                    <li>Usage statistics will be tracked</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-bar-chart me-2"></i>Quick Stats
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary mb-0" id="totalSchools">{{ $schools->count() }}</h4>
                                    <small class="text-muted">Total Schools</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-0" id="activeQrCodes">0</h4>
                                    <small class="text-muted">Active QR Codes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.qr-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
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
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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

.alert ul {
    margin-bottom: 0;
}

.alert li {
    margin-bottom: 0.25rem;
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
        $(this).find('button[type="submit"]').html('<i class="bx bx-loader-alt bx-spin me-2"></i>Generating...').prop('disabled', true);
    });

    // Load initial stats
    loadStats();
});

function loadStats() {
    $.ajax({
        url: "{{ route('superadmin.payment.school-qr-codes.index') }}",
        data: { stats_only: true },
        success: function(data) {
            $('#activeQrCodes').text(data.active || 0);
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

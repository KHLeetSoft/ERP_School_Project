@extends('admin.layout.app')

@section('title', 'Edit School QR Code')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit School QR Code</h4>
            <p class="text-muted mb-0">Update your school's payment QR code settings</p>
        </div>
        <div>
            <a href="{{ route('admin.payment.school-qr-codes.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">School QR Code Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment.school-qr-codes.update') }}" method="POST" id="school-qr-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- School Information -->
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>School:</strong> {{ $school->name }}<br>
                            <strong>Note:</strong> You can edit the QR code details but cannot change the school.
                        </div>

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $schoolQrCode->title) }}" 
                                       placeholder="Enter QR code title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount (₹)</label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount', $schoolQrCode->amount) }}" 
                                       step="0.01" min="0" placeholder="Leave empty for variable amount">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty for variable amount payments</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Enter QR code description">{{ old('description', $schoolQrCode->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- UPI Payment Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="upi_id" class="form-label">UPI ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('upi_id') is-invalid @enderror" 
                                       id="upi_id" name="upi_id" value="{{ old('upi_id', $schoolQrCode->upi_id) }}" 
                                       placeholder="e.g., school@paytm">
                                @error('upi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter your school's UPI ID for payments</small>
                            </div>
                            <div class="col-md-6">
                                <label for="merchant_name" class="form-label">Merchant Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merchant_name') is-invalid @enderror" 
                                       id="merchant_name" name="merchant_name" value="{{ old('merchant_name', $schoolQrCode->merchant_name) }}" 
                                       placeholder="e.g., School Name">
                                @error('merchant_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">This will appear on payment apps</small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', $schoolQrCode->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (QR code will be available for use)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.payment.school-qr-codes.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Update School QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Panel -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Current QR Code</h5>
                </div>
                <div class="card-body text-center">
                    @if($schoolQrCode->qr_code_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $schoolQrCode->qr_code_image) }}" alt="School QR Code" class="img-fluid" style="max-width: 200px;">
                    </div>
                    @else
                    <div class="mb-3">
                        <div class="bg-light rounded p-4">
                            <i class="bx bx-qr-scan fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">No QR code image available</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="text-start">
                        <h6 class="mb-2">QR Code Information</h6>
                        <p class="text-muted mb-1"><strong>School:</strong> {{ $school->name }}</p>
                        <p class="text-muted mb-1"><strong>Title:</strong> {{ $schoolQrCode->title }}</p>
                        <p class="text-muted mb-1"><strong>UPI ID:</strong> {{ $schoolQrCode->upi_id }}</p>
                        <p class="text-muted mb-1"><strong>Amount:</strong> {{ $schoolQrCode->amount ? '₹' . number_format($schoolQrCode->amount, 2) : 'Variable' }}</p>
                        <p class="text-muted mb-1"><strong>Usage:</strong> {{ $schoolQrCode->usage_count }} times</p>
                        <p class="text-muted mb-1"><strong>Status:</strong> 
                            @if($schoolQrCode->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.payment.school-qr-codes.download') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-download me-1"></i> Download QR Code
                        </a>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Usage Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary mb-1">{{ $schoolQrCode->usage_count }}</h4>
                            <p class="text-muted mb-0">Total Scans</p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">
                                @if($schoolQrCode->created_at->diffInDays(now()) > 0)
                                    {{ $schoolQrCode->created_at->diffInDays(now()) }}d
                                @else
                                    {{ $schoolQrCode->created_at->diffInHours(now()) }}h
                                @endif
                            </h4>
                            <p class="text-muted mb-0">Age</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form input change handlers
    $('#title, #amount, #description, #upi_id, #merchant_name, #is_active').on('input change', function() {
        updatePreview();
    });

    // Update preview
    function updatePreview() {
        var title = $('#title').val() || 'Untitled';
        var amount = $('#amount').val();
        var upiId = $('#upi_id').val();
        var merchantName = $('#merchant_name').val();
        var isActive = $('#is_active').is(':checked');

        // Update preview info
        $('#preview-title').text(title);
        $('#preview-upi').text(upiId || '-');
        $('#preview-amount').text(amount ? '₹' + parseFloat(amount).toFixed(2) : 'Variable');
        $('#preview-status').html(isActive ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>');
    }

    // Form validation
    $('#school-qr-form').on('submit', function(e) {
        if (!$('#upi_id').val()) {
            e.preventDefault();
            toastr.error('UPI ID is required for school QR code');
            $('#upi_id').focus();
            return false;
        }
        if (!$('#merchant_name').val()) {
            e.preventDefault();
            toastr.error('Merchant name is required for school QR code');
            $('#merchant_name').focus();
            return false;
        }
    });

    // Initialize preview
    updatePreview();
});
</script>
@endpush

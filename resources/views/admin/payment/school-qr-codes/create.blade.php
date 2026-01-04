@extends('admin.layout.app')

@section('title', 'Generate School QR Code')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Generate School QR Code</h4>
            <p class="text-muted mb-0">Create a one-time school payment QR code</p>
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
                    <form action="{{ route('admin.payment.school-qr-codes.store') }}" method="POST" id="school-qr-form">
                        @csrf
                        
                        <!-- School Information -->
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>School:</strong> {{ $school->name }}<br>
                            <strong>Important:</strong> You can only generate one QR code per school. This action cannot be undone.
                        </div>

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $school->name . ' Payment QR') }}" 
                                       placeholder="Enter QR code title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount (₹)</label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" 
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
                                          placeholder="Enter QR code description">{{ old('description', 'School payment QR code for ' . $school->name) }}</textarea>
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
                                       id="upi_id" name="upi_id" value="{{ old('upi_id') }}" 
                                       placeholder="e.g., school@paytm">
                                @error('upi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter your school's UPI ID for payments</small>
                            </div>
                            <div class="col-md-6">
                                <label for="merchant_name" class="form-label">Merchant Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merchant_name') is-invalid @enderror" 
                                       id="merchant_name" name="merchant_name" value="{{ old('merchant_name', $school->name) }}" 
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
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (QR code will be available for use immediately)
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
                                <i class="bx bx-plus me-1"></i> Generate School QR Code
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
                    <h5 class="mb-0">Preview</h5>
                </div>
                <div class="card-body text-center">
                    <div id="qr-preview" class="mb-3">
                        <div class="bg-light rounded p-4">
                            <i class="bx bx-qr-scan fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">QR Code preview will appear here</p>
                        </div>
                    </div>
                    <div id="qr-info" class="text-start">
                        <h6 class="mb-2">QR Code Information</h6>
                        <p class="text-muted mb-1"><strong>School:</strong> <span id="preview-school">{{ $school->name }}</span></p>
                        <p class="text-muted mb-1"><strong>Title:</strong> <span id="preview-title">-</span></p>
                        <p class="text-muted mb-1"><strong>UPI ID:</strong> <span id="preview-upi">-</span></p>
                        <p class="text-muted mb-1"><strong>Amount:</strong> <span id="preview-amount">-</span></p>
                        <p class="text-muted mb-0"><strong>Status:</strong> <span id="preview-status">-</span></p>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-warning border-0 py-3">
                    <h5 class="mb-0 text-white">
                        <i class="bx bx-info-circle me-1"></i> Important Notice
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bx bx-check text-warning me-2"></i>
                            <strong>One-time only:</strong> You can only generate one QR code per school
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-warning me-2"></i>
                            <strong>Cannot be deleted:</strong> Once created, you can only edit or deactivate
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-warning me-2"></i>
                            <strong>Accountant access:</strong> Your accountant will be able to use this QR code
                        </li>
                        <li class="mb-0">
                            <i class="bx bx-check text-warning me-2"></i>
                            <strong>Usage tracking:</strong> All scans will be tracked and recorded
                        </li>
                    </ul>
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

        // Generate QR code preview for UPI
        if (upiId && merchantName) {
            var qrData = generateUPIString(upiId, amount, merchantName);
            generateQRPreview(qrData);
        } else {
            $('#qr-preview').html('<div class="bg-light rounded p-4"><i class="bx bx-qr-scan fs-1 text-muted"></i><p class="text-muted mt-2 mb-0">QR Code preview will appear here</p></div>');
        }
    }

    // Generate UPI string
    function generateUPIString(upiId, amount, merchantName) {
        var upiString = 'upi://pay?pa=' + encodeURIComponent(upiId);
        upiString += '&pn=' + encodeURIComponent(merchantName);
        if (amount) {
            upiString += '&am=' + amount;
        }
        upiString += '&cu=INR';
        return upiString;
    }

    // Generate QR code preview
    function generateQRPreview(data) {
        // Simple QR code preview using a placeholder
        $('#qr-preview').html('<div class="bg-white rounded p-2 border"><div class="qr-placeholder" style="width: 150px; height: 150px; background: #f8f9fa; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center; margin: 0 auto;"><i class="bx bx-qr-scan fs-2 text-muted"></i></div><small class="text-muted mt-2 d-block">QR Code will be generated after creation</small></div>');
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

        // Confirm before generating
        if (!confirm('Are you sure you want to generate the school QR code? This action cannot be undone.')) {
            e.preventDefault();
            return false;
        }
    });

    // Initialize preview
    updatePreview();
});
</script>
@endpush

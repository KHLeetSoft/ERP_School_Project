@extends('superadmin.app')

@section('title', 'School QR Code Details')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="qr-icon me-3">
                            <i class="bx bx-qr-scan fs-1 text-info"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">QR Code Details</h1>
                            <p class="text-muted mb-0">{{ $schoolQrCode->title }} - {{ $school->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-end">
                        <a href="{{ route('superadmin.payment.school-qr-codes.index') }}" class="btn btn-secondary me-2">
                            <i class="bx bx-arrow-back me-2"></i>Back to QR Codes
                        </a>
                        <a href="{{ route('superadmin.payment.school-qr-codes.edit', $schoolQrCode->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-2"></i>Edit QR Code
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
                <!-- QR Code Display -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-qr-scan me-2"></i>QR Code
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            @if($schoolQrCode->qr_code_image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $schoolQrCode->qr_code_image) }}" 
                                         alt="QR Code" class="img-fluid" style="max-width: 300px;">
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('superadmin.payment.school-qr-codes.download', $schoolQrCode->id) }}" 
                                       class="btn btn-success">
                                        <i class="bx bx-download me-2"></i>Download QR Code
                                    </a>
                                </div>
                            @else
                                <div class="qr-placeholder">
                                    <i class="bx bx-qr-scan fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No QR Code Image Available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-cog me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('superadmin.payment.school-qr-codes.edit', $schoolQrCode->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="bx bx-edit me-2"></i>Edit QR Code
                                </a>
                                <button class="btn btn-outline-warning" onclick="toggleStatus({{ $schoolQrCode->id }})">
                                    <i class="bx bx-{{ $schoolQrCode->is_active ? 'pause' : 'play' }} me-2"></i>
                                    {{ $schoolQrCode->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteQrCode({{ $schoolQrCode->id }})">
                                    <i class="bx bx-trash me-2"></i>Delete QR Code
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Information -->
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-info-circle me-2"></i>Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">School Name</label>
                                    <p class="mb-0">{{ $school->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">QR Code Title</label>
                                    <p class="mb-0">{{ $schoolQrCode->title }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Description</label>
                                    <p class="mb-0">{{ $schoolQrCode->description ?: 'No description provided' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Status</label>
                                    <p class="mb-0">
                                        @if($schoolQrCode->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UPI Details -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-credit-card me-2"></i>UPI Payment Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">UPI ID</label>
                                    <p class="mb-0 font-monospace">{{ $schoolQrCode->upi_id }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Merchant Name</label>
                                    <p class="mb-0">{{ $schoolQrCode->merchant_name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Amount Type</label>
                                    <p class="mb-0">
                                        @if($schoolQrCode->amount)
                                            <span class="badge bg-info">Fixed Amount</span>
                                        @else
                                            <span class="badge bg-secondary">Variable Amount</span>
                                        @endif
                                    </p>
                                </div>
                                @if($schoolQrCode->amount)
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Fixed Amount</label>
                                    <p class="mb-0 fs-5 text-success">₹{{ number_format($schoolQrCode->amount, 2) }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- UPI String -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-link me-2"></i>UPI String
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control font-monospace" 
                                       value="{{ $schoolQrCode->qr_code_data }}" readonly id="upiString">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyUPIString()">
                                    <i class="bx bx-copy me-1"></i>Copy
                                </button>
                            </div>
                            <small class="text-muted">This is the UPI string encoded in the QR code</small>
                        </div>
                    </div>

                    <!-- Usage Statistics -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-bar-chart me-2"></i>Usage Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <h3 class="text-primary mb-0">{{ $schoolQrCode->usage_count }}</h3>
                                        <p class="text-muted mb-0">Total Scans</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <h3 class="text-success mb-0">{{ $schoolQrCode->created_at->diffInDays(now()) }}</h3>
                                        <p class="text-muted mb-0">Days Active</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <h3 class="text-info mb-0">{{ $schoolQrCode->created_at->format('M d') }}</h3>
                                        <p class="text-muted mb-0">Created Date</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <h3 class="text-warning mb-0">{{ $schoolQrCode->updated_at->format('M d') }}</h3>
                                        <p class="text-muted mb-0">Last Updated</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- School Information -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-building me-2"></i>School Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">School Name</label>
                                    <p class="mb-0">{{ $school->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Email</label>
                                    <p class="mb-0">{{ $school->email ?: 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Phone</label>
                                    <p class="mb-0">{{ $school->phone ?: 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted">Address</label>
                                    <p class="mb-0">{{ $school->address ?: 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bx bx-trash me-2"></i>Delete QR Code
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h5>Are you sure you want to delete this QR code?</h5>
                <p class="text-muted">This action cannot be undone. The QR code image will also be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bx bx-trash me-1"></i>Delete QR Code
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.qr-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(23, 162, 184, 0.3);
}

.qr-placeholder {
    padding: 3rem;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    background-color: #f8f9fa;
}

.stat-item {
    padding: 1rem;
    border-radius: 8px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.stat-item:hover {
    background-color: #e9ecef;
    transform: translateY(-2px);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
}

.font-monospace {
    font-family: 'Courier New', monospace;
}

.input-group .btn {
    border-radius: 0 8px 8px 0;
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 12px 12px 0 0 !important;
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
</style>

<script>
function copyUPIString() {
    const upiString = document.getElementById('upiString');
    upiString.select();
    upiString.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        showAlert('UPI string copied to clipboard!', 'success');
    } catch (err) {
        showAlert('Failed to copy UPI string', 'error');
    }
}

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

function deleteQrCode(qrCodeId) {
    $('#deleteModal').modal('show');
    
    $('#confirmDelete').off('click').on('click', function() {
        $.ajax({
            url: "{{ route('superadmin.payment.school-qr-codes.destroy', '') }}/" + qrCodeId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                showAlert(response.message, 'success');
                setTimeout(() => {
                    window.location.href = "{{ route('superadmin.payment.school-qr-codes.index') }}";
                }, 1500);
            },
            error: function() {
                showAlert('Something went wrong!', 'error');
            }
        });
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

@extends('accountant.layout.app')

@section('title', 'QR Code Details')
@section('page-title', 'QR Code Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $qrCode->title }}</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('accountant.qr-codes.edit', $qrCode) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('accountant.qr-codes.download', $qrCode) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- QR Code Image -->
                    <div class="text-center mb-4">
                        @if($qrCode->qr_image_path)
                            <img src="{{ $qrCode->qr_image_url }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                        @else
                            <div class="qr-placeholder bg-light p-5 rounded">
                                <i class="fas fa-qrcode fa-3x text-muted"></i>
                                <p class="text-muted mt-2">QR Code not generated yet</p>
                                <button class="btn btn-primary" onclick="generateQR()">
                                    <i class="fas fa-sync me-1"></i>Generate QR Code
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- QR Code Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold">QR Code Type</label>
                                <div>
                                    <span class="badge bg-{{ $qrCode->type === 'student' ? 'primary' : ($qrCode->type === 'payment' ? 'success' : 'info') }}">
                                        {{ ucfirst($qrCode->type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <div>
                                    @if($qrCode->is_active)
                                        @if($qrCode->isExpired())
                                            <span class="badge bg-warning">Expired</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold">QR Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $qrCode->code }}" readonly>
                                    <button class="btn btn-outline-secondary" onclick="copyToClipboard('{{ $qrCode->code }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold">Scan Count</label>
                                <div class="h5 text-primary">{{ $qrCode->scan_count }}</div>
                            </div>
                        </div>
                    </div>

                    @if($qrCode->description)
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <p class="text-muted">{{ $qrCode->description }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold">Created</label>
                                <div>{{ $qrCode->created_at->format('M d, Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold">Expires</label>
                                <div>
                                    @if($qrCode->expires_at)
                                        {{ $qrCode->expires_at->format('M d, Y H:i') }}
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Data -->
                    @if($qrCode->data)
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">QR Code Data</label>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0">{{ json_encode($qrCode->data, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif

                    <!-- Access URL -->
                    <div class="info-item mb-3">
                        <label class="form-label fw-bold">Access URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ route('accountant.qr.access', $qrCode->code) }}" readonly>
                            <button class="btn btn-outline-secondary" onclick="copyToClipboard('{{ route('accountant.qr.access', $qrCode->code) }}')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted">Share this URL or scan the QR code to access</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('accountant.qr.access', $qrCode->code) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Preview Access Page
                        </a>
                        <button class="btn btn-outline-primary" onclick="printQR()">
                            <i class="fas fa-print me-2"></i>Print QR Code
                        </button>
                        <button class="btn btn-outline-success" onclick="shareQR()">
                            <i class="fas fa-share me-2"></i>Share QR Code
                        </button>
                    </div>
                </div>
            </div>

            <!-- QR Code Statistics -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-primary">{{ $qrCode->scan_count }}</h4>
                                <small class="text-muted">Total Scans</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-success">{{ $qrCode->created_at->diffInDays(now()) }}</h4>
                                <small class="text-muted">Days Active</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Types Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">QR Code Types</h6>
                </div>
                <div class="card-body">
                    <div class="qr-type-info">
                        <div class="mb-2">
                            <span class="badge bg-primary me-2">Student</span>
                            <small>Links to student profile</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-success me-2">Payment</span>
                            <small>Quick payment processing</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-warning me-2">Fee</span>
                            <small>Fee information and payment</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-info me-2">Link</span>
                            <small>Custom URL redirection</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-secondary me-2">General</span>
                            <small>Custom data storage</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-dark me-2">Document</span>
                            <small>Document information</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showAlert('success', 'Copied to clipboard!');
    }, function(err) {
        showAlert('error', 'Failed to copy to clipboard');
    });
}

function generateQR() {
    if (confirm('Generate QR code image for this QR code?')) {
        // This would typically make an AJAX call to generate the QR code
        showAlert('info', 'QR code generation feature coming soon...');
    }
}

function printQR() {
    window.print();
}

function shareQR() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $qrCode->title }}',
            text: '{{ $qrCode->description }}',
            url: '{{ route('accountant.qr.access', $qrCode->code) }}'
        });
    } else {
        copyToClipboard('{{ route('accountant.qr.access', $qrCode->code) }}');
        showAlert('info', 'Access URL copied to clipboard!');
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.querySelector('.content-wrapper').insertAdjacentHTML('afterbegin', alertHtml);
    
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection

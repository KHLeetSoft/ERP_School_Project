@extends('accountant.layout.app')

@section('title', 'Edit QR Code')
@section('page-title', 'Edit QR Code')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit QR Code: {{ $qrCode->title }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('accountant.qr-codes.update', $qrCode) }}" method="POST" id="editQRForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           name="title" value="{{ old('title', $qrCode->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">QR Code Type</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($qrCode->type) }}" readonly>
                                    <small class="text-muted">QR code type cannot be changed after creation</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="3" placeholder="Enter description...">{{ old('description', $qrCode->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status and Expiration -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" 
                                               id="is_active" {{ old('is_active', $qrCode->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active QR Code
                                        </label>
                                    </div>
                                    <small class="text-muted">Inactive QR codes cannot be accessed</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                           name="expires_at" value="{{ old('expires_at', $qrCode->expires_at ? $qrCode->expires_at->format('Y-m-d\TH:i') : '') }}">
                                    <small class="text-muted">Leave empty for no expiration</small>
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current QR Code Info -->
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Current QR Code Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-2">
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
                                        <div class="info-item mb-2">
                                            <label class="form-label fw-bold">Scan Count</label>
                                            <div class="h5 text-primary">{{ $qrCode->scan_count }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-item mb-2">
                                    <label class="form-label fw-bold">Access URL</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ route('accountant.qr.access', $qrCode->code) }}" readonly>
                                        <button class="btn btn-outline-secondary" onclick="copyToClipboard('{{ route('accountant.qr.access', $qrCode->code) }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                @if($qrCode->qr_image_path)
                                    <div class="text-center mt-3">
                                        <img src="{{ $qrCode->qr_image_url }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- QR Code Data (Read-only) -->
                        @if($qrCode->data)
                            <div class="card bg-light mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">QR Code Data</h6>
                                </div>
                                <div class="card-body">
                                    <div class="bg-white p-3 rounded">
                                        <pre class="mb-0">{{ json_encode($qrCode->data, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                    <small class="text-muted">QR code data cannot be modified after creation</small>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update QR Code
                            </button>
                            <a href="{{ route('accountant.qr-codes.show', $qrCode) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-eye me-2"></i>View QR Code
                            </a>
                            <a href="{{ route('accountant.qr-codes') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- QR Code Preview -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">QR Code Preview</h6>
                </div>
                <div class="card-body text-center">
                    @if($qrCode->qr_image_path)
                        <img src="{{ $qrCode->qr_image_url }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                    @else
                        <div class="qr-placeholder bg-light p-4 rounded mb-3">
                            <i class="fas fa-qrcode fa-2x text-muted"></i>
                            <p class="text-muted mt-2 mb-0">QR Code not generated</p>
                        </div>
                    @endif
                    
                    <div class="text-start">
                        <h6>QR Code Information:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Type:</strong> {{ ucfirst($qrCode->type) }}</li>
                            <li><strong>Title:</strong> <span id="previewTitle">{{ $qrCode->title }}</span></li>
                            <li><strong>Status:</strong> <span id="previewStatus">{{ $qrCode->is_active ? 'Active' : 'Inactive' }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('accountant.qr.access', $qrCode->code) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Preview Access Page
                        </a>
                        <a href="{{ route('accountant.qr-codes.download', $qrCode) }}" class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Download QR Code
                        </a>
                        <button class="btn btn-outline-warning" onclick="printQR()">
                            <i class="fas fa-print me-2"></i>Print QR Code
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
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

function printQR() {
    window.print();
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

// Update preview on form changes
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.querySelector('input[name="title"]');
    const statusCheckbox = document.querySelector('input[name="is_active"]');
    
    titleInput.addEventListener('input', function() {
        document.getElementById('previewTitle').textContent = this.value;
    });
    
    statusCheckbox.addEventListener('change', function() {
        document.getElementById('previewStatus').textContent = this.checked ? 'Active' : 'Inactive';
    });
});
</script>
@endsection

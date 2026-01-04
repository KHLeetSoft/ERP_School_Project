@extends('admin.layout.app')

@section('title', 'School QR Code Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">School QR Code Details</h4>
            <p class="text-muted mb-0">Complete information about your school's QR code</p>
        </div>
        <div>
            <a href="{{ route('admin.payment.school-qr-codes.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
            <a href="{{ route('admin.payment.school-qr-codes.edit') }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i> Edit QR Code
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- QR Code Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">QR Code Information</h5>
                        <div>
                            @if($schoolQrCode->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Title</label>
                                <p class="mb-0">{{ $schoolQrCode->title }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">School</label>
                                <p class="mb-0">{{ $school->name }}</p>
                            </div>
                        </div>
                    </div>

                    @if($schoolQrCode->description)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <p class="mb-0">{{ $schoolQrCode->description }}</p>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">UPI ID</label>
                                <p class="mb-0">
                                    <code>{{ $schoolQrCode->upi_id }}</code>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Merchant Name</label>
                                <p class="mb-0">{{ $schoolQrCode->merchant_name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Amount</label>
                                <p class="mb-0">
                                    @if($schoolQrCode->amount)
                                        <strong class="text-success">₹{{ number_format($schoolQrCode->amount, 2) }}</strong>
                                        <small class="text-muted">(Fixed Amount)</small>
                                    @else
                                        <span class="text-muted">Variable Amount</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Usage Count</label>
                                <p class="mb-0">
                                    <strong class="text-primary">{{ $schoolQrCode->usage_count }}</strong>
                                    <small class="text-muted">times scanned</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Created By</label>
                                <p class="mb-0">{{ $schoolQrCode->createdBy->name ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Created At</label>
                                <p class="mb-0">{{ $schoolQrCode->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($schoolQrCode->updatedBy)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Last Updated By</label>
                                <p class="mb-0">{{ $schoolQrCode->updatedBy->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Last Updated</label>
                                <p class="mb-0">{{ $schoolQrCode->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- QR Code Data -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">QR Code Data</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">QR Code String</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $schoolQrCode->qr_code_data }}" readonly id="qr-data">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('qr-data')">
                                <i class="bx bx-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    @if($schoolQrCode->additional_data)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Additional Data</label>
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($schoolQrCode->additional_data, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- QR Code Preview -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">QR Code Preview</h5>
                </div>
                <div class="card-body text-center">
                    @if($schoolQrCode->qr_code_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $schoolQrCode->qr_code_image) }}" alt="School QR Code" class="img-fluid" style="max-width: 250px;">
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.payment.school-qr-codes.download') }}" class="btn btn-primary">
                            <i class="bx bx-download me-1"></i> Download QR Code
                        </a>
                        <button class="btn btn-outline-secondary" onclick="printQRCode()">
                            <i class="bx bx-printer me-1"></i> Print QR Code
                        </button>
                    </div>
                    @else
                    <div class="bg-light rounded p-4">
                        <i class="bx bx-qr-scan fs-1 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">No QR code image available</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.payment.school-qr-codes.edit') }}" class="btn btn-outline-primary">
                            <i class="bx bx-edit me-1"></i> Edit QR Code
                        </a>
                        
                        <button class="btn btn-outline-{{ $schoolQrCode->is_active ? 'warning' : 'success' }}" 
                                onclick="toggleStatus()">
                            <i class="bx bx-{{ $schoolQrCode->is_active ? 'pause' : 'play' }} me-1"></i>
                            {{ $schoolQrCode->is_active ? 'Deactivate' : 'Activate' }} QR Code
                        </button>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="card border-0 shadow-sm">
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
function copyToClipboard(elementId) {
    var element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    toastr.success('QR code data copied to clipboard!');
}

function printQRCode() {
    var qrImage = document.querySelector('img[alt="School QR Code"]');
    if (qrImage) {
        var printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>School QR Code - {{ $school->name }}</title>
                    <style>
                        body { text-align: center; font-family: Arial, sans-serif; }
                        .qr-container { margin: 50px auto; }
                        .qr-title { font-size: 24px; margin-bottom: 20px; }
                        .qr-image { max-width: 300px; }
                    </style>
                </head>
                <body>
                    <div class="qr-container">
                        <h1 class="qr-title">{{ $school->name }}</h1>
                        <img src="${qrImage.src}" alt="School QR Code" class="qr-image">
                        <p>{{ $schoolQrCode->description ?? 'School Payment QR Code' }}</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

function toggleStatus() {
    $.ajax({
        url: "{{ route('admin.payment.school-qr-codes.toggle-status') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Something went wrong!');
        }
    });
}
</script>
@endpush

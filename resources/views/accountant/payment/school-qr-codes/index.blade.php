@extends('accountant.layout.app')

@section('title', 'School QR Codes')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">School QR Codes</h4>
            <p class="text-muted mb-0">Access your school's payment QR codes for fee collection</p>
        </div>
    </div>

    @if($schoolQrCodes->count() > 0)
    <!-- School QR Codes Available -->
    <div class="row">
        @foreach($schoolQrCodes as $schoolQrCode)
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $schoolQrCode->title }}</h5>
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
                    @if($schoolQrCode->description)
                    <p class="text-muted mb-3">{{ $schoolQrCode->description }}</p>
                    @endif

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">UPI ID</small>
                            <p class="mb-0"><code>{{ $schoolQrCode->upi_id }}</code></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Merchant</small>
                            <p class="mb-0">{{ $schoolQrCode->merchant_name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Amount</small>
                            <p class="mb-0">
                                @if($schoolQrCode->amount)
                                    <strong class="text-success">₹{{ number_format($schoolQrCode->amount, 2) }}</strong>
                                    <br><small class="text-muted">Fixed Amount</small>
                                @else
                                    <span class="text-muted">Variable Amount</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Usage</small>
                            <p class="mb-0">
                                <strong class="text-primary">{{ $schoolQrCode->usage_count }}</strong>
                                <br><small class="text-muted">times scanned</small>
                            </p>
                        </div>
                    </div>

                    <!-- QR Code Preview -->
                    <div class="text-center mb-3">
                        @if($schoolQrCode->qr_code_image)
                        <img src="{{ asset('storage/' . $schoolQrCode->qr_code_image) }}" 
                             alt="QR Code" 
                             class="img-fluid" 
                             style="max-width: 150px;">
                        @else
                        <div class="bg-light rounded p-3">
                            <i class="bx bx-qr-scan fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">No QR code image</p>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('accountant.payment.school-qr-codes.download', $schoolQrCode->id) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="bx bx-download me-1"></i> Download QR Code
                        </a>
                        <a href="{{ route('accountant.payment.school-qr-codes.show', $schoolQrCode->id) }}" 
                           class="btn btn-outline-info btn-sm">
                            <i class="bx bx-show me-1"></i> View Details
                        </a>
                        <button class="btn btn-outline-success btn-sm" 
                                onclick="processScan({{ $schoolQrCode->id }})">
                            <i class="bx bx-scan me-1"></i> Mark as Scanned
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">QR Code Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary mb-1">{{ $schoolQrCodes->count() }}</h4>
                            <p class="text-muted mb-0">Total QR Codes</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success mb-1">{{ $schoolQrCodes->where('is_active', true)->count() }}</h4>
                            <p class="text-muted mb-0">Active QR Codes</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info mb-1">{{ $schoolQrCodes->sum('usage_count') }}</h4>
                            <p class="text-muted mb-0">Total Scans</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning mb-1">{{ $schoolQrCodes->where('amount', '>', 0)->count() }}</h4>
                            <p class="text-muted mb-0">Fixed Amount QR Codes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No QR Codes Available -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">No QR Codes Available</h5>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bx bx-qr-scan fs-1 text-muted"></i>
                    </div>
                    <h4 class="mb-3">No School QR Codes Found</h4>
                    <p class="text-muted mb-4">
                        Your school admin hasn't generated any QR codes yet. 
                        Please contact your school administrator to generate QR codes for payment collection.
                    </p>
                    <a href="{{ route('accountant.dashboard') }}" class="btn btn-primary">
                        <i class="bx bx-home me-2"></i> Go to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info border-0 py-3">
                    <h5 class="mb-0 text-white">
                        <i class="bx bx-info-circle me-1"></i> About QR Codes
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bx bx-check text-success me-2"></i>
                            Generated by school admin
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success me-2"></i>
                            UPI payment integration
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success me-2"></i>
                            Download and print options
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success me-2"></i>
                            Usage tracking and analytics
                        </li>
                        <li class="mb-0">
                            <i class="bx bx-check text-success me-2"></i>
                            Multiple QR codes support
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function processScan(qrCodeId) {
    $.ajax({
        url: "{{ route('accountant.payment.school-qr-codes.process-scan', '') }}/" + qrCodeId,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                // Reload page to update usage counts
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Something went wrong!');
        }
    });
}

function printQRCode(qrCodeId, title) {
    var qrImage = document.querySelector(`img[alt="QR Code"][data-qr-id="${qrCodeId}"]`);
    if (qrImage) {
        var printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>School QR Code - ${title}</title>
                    <style>
                        body { text-align: center; font-family: Arial, sans-serif; }
                        .qr-container { margin: 50px auto; }
                        .qr-title { font-size: 24px; margin-bottom: 20px; }
                        .qr-image { max-width: 300px; }
                    </style>
                </head>
                <body>
                    <div class="qr-container">
                        <h1 class="qr-title">${title}</h1>
                        <img src="${qrImage.src}" alt="QR Code" class="qr-image">
                        <p>{{ $school->name }}</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}
</script>
@endpush
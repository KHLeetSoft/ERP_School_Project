@extends('admin.layout.app')

@section('title', 'QR Code Limit Request')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payment.school-qr-codes.index') }}">QR Codes</a></li>
                        <li class="breadcrumb-item active">Limit Request</li>
                    </ol>
                </div>
                <h4 class="page-title">QR Code Limit Request</h4>
            </div>
        </div>
    </div>

    <!-- Current Status Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Current QR Code Status</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary" id="current-limit">{{ $school->qr_code_limit }}</h3>
                                <p class="text-muted mb-0">Current Limit</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success" id="generated-count">{{ $school->qr_codes_generated }}</h3>
                                <p class="text-muted mb-0">Generated</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info" id="remaining-count">{{ $school->getRemainingQrCodes() }}</h3>
                                <p class="text-muted mb-0">Remaining</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                @if($school->needsPaymentForQrCodes())
                                    <span class="badge bg-warning fs-6">Payment Required</span>
                                @elseif($school->qr_limit_paid)
                                    <span class="badge bg-success fs-6">Paid</span>
                                @else
                                    <span class="badge bg-info fs-6">Free</span>
                                @endif
                                <p class="text-muted mb-0 mt-1">Status</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-qrcode font-size-48 text-primary mb-3"></i>
                    <h5>Need More QR Codes?</h5>
                    <p class="text-muted">Request an increase in your QR code generation limit.</p>
                    <a href="{{ route('admin.payment.qr-limit-requests.history') }}" class="btn btn-outline-primary">
                        View Request History
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Form or Current Request Status -->
    <div class="row">
        <div class="col-12">
            @if($currentRequest)
                <!-- Current Request Status -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Current Request Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="alert-heading">Request Submitted</h5>
                                    <p class="mb-0">
                                        You have a pending request to increase your QR code limit from 
                                        <strong>{{ $currentRequest->current_limit }}</strong> to 
                                        <strong>{{ $currentRequest->requested_limit }}</strong>.
                                    </p>
                                    <small class="text-muted">
                                        Submitted on {{ $currentRequest->created_at->format('d M Y H:i A') }}
                                    </small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-warning fs-6">Pending Review</span>
                                    <br>
                                    <button class="btn btn-outline-danger btn-sm mt-2" onclick="cancelRequest({{ $currentRequest->id }})">
                                        Cancel Request
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Request Form -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Request QR Code Limit Increase</h4>
                    </div>
                    <div class="card-body">
                        <form id="limitRequestForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="current_limit_display" class="form-label">Current Limit</label>
                                        <input type="text" class="form-control" id="current_limit_display" 
                                               value="{{ $school->qr_code_limit }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="requested_limit" class="form-label">Requested Limit <span class="text-danger">*</span></label>
                                        <select class="form-select" id="requested_limit" name="requested_limit" required>
                                            <option value="">Select new limit</option>
                                            @for($i = $school->qr_code_limit + 1; $i <= 100; $i++)
                                                <option value="{{ $i }}">{{ $i }} QR Codes</option>
                                            @endfor
                                        </select>
                                        <div class="form-text">Maximum 100 QR codes per school</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason for Increase <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="reason" name="reason" rows="4" 
                                          placeholder="Please explain why you need to increase your QR code limit..." required></textarea>
                                <div class="form-text">Provide a detailed reason for your request</div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="mdi mdi-information me-2"></i>
                                <strong>Important:</strong> After generating 3 QR codes, you will need to make a payment to continue generating more QR codes.
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-send me-1"></i> Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Submit request form
    $('#limitRequestForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('admin.payment.qr-limit-requests.store') }}",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else if (xhr.responseJSON.error) {
                    toastr.error(xhr.responseJSON.error);
                }
            }
        });
    });
});

function cancelRequest(requestId) {
    if (confirm('Are you sure you want to cancel this request?')) {
        $.ajax({
            url: "{{ route('admin.payment.qr-limit-requests.cancel', ':id') }}".replace(':id', requestId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON.error) {
                    toastr.error(xhr.responseJSON.error);
                }
            }
        });
    }
}
</script>
@endsection

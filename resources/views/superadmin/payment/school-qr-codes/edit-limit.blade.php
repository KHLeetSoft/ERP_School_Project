@extends('superadmin.app')

@section('title', 'Edit QR Code Limit - ' . $school->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.payment.school-qr-codes.limits') }}">QR Code Limits</a></li>
                        <li class="breadcrumb-item active">Edit Limit</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit QR Code Limit</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">{{ $school->name }}</h4>
                    <p class="text-muted mb-0">Manage QR code generation limit for this school</p>
                </div>
                <div class="card-body">
                    <form id="updateLimitForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">School Name</label>
                                    <input type="text" class="form-control" value="{{ $school->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Admin</label>
                                    <input type="text" class="form-control" value="{{ $school->admin ? $school->admin->name : 'No Admin' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Current Limit</label>
                                    <input type="text" class="form-control" value="{{ $school->qr_code_limit }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">QR Codes Generated</label>
                                    <input type="text" class="form-control" value="{{ $school->qr_codes_generated }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Remaining</label>
                                    <input type="text" class="form-control" value="{{ $school->getRemainingQrCodes() }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qr_code_limit" class="form-label">New Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="qr_code_limit" name="qr_code_limit" 
                                           value="{{ $school->qr_code_limit }}" min="1" max="100" required>
                                    <div class="form-text">Maximum 100 QR codes per school</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Payment Status</label>
                                    <div class="input-group">
                                        @if($school->needsPaymentForQrCodes())
                                            <span class="badge bg-warning">Payment Required</span>
                                        @elseif($school->qr_limit_paid)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-info">Free</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Change</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" 
                                      placeholder="Optional reason for changing the limit"></textarea>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('superadmin.payment.school-qr-codes.limits') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Limit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- School Info Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">School Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="img-thumbnail" style="max-width: 100px;">
                        @else
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                                    <i class="mdi mdi-school"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <h5 class="text-center">{{ $school->name }}</h5>
                    <p class="text-muted text-center">{{ $school->email }}</p>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $school->qr_codes_generated }}</h4>
                            <p class="text-muted mb-0">Generated</p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $school->getRemainingQrCodes() }}</h4>
                            <p class="text-muted mb-0">Remaining</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Codes List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Generated QR Codes</h5>
                </div>
                <div class="card-body">
                    @if($school->qrCodes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($school->qrCodes as $qrCode)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $qrCode->title }}</h6>
                                        <small class="text-muted">{{ $qrCode->created_at->format('d M Y') }}</small>
                                    </div>
                                    <div>
                                        @if($qrCode->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="mdi mdi-qrcode font-size-24 mb-2"></i>
                            <p>No QR codes generated yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#updateLimitForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('superadmin.payment.school-qr-codes.update-limit', $school) }}",
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = "{{ route('superadmin.payment.school-qr-codes.limits') }}";
                    }, 1500);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                }
            }
        });
    });
});
</script>
@endpush

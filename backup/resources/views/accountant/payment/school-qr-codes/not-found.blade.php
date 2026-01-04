@extends('accountant.layout.app')

@section('title', 'School QR Code Not Found')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">School QR Code Not Available</h4>
            <p class="text-muted mb-0">Your school's QR code has not been generated yet</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">No School QR Code Available</h5>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bx bx-qr-scan fs-1 text-muted"></i>
                    </div>
                    <h4 class="mb-3">School QR Code Not Generated</h4>
                    <p class="text-muted mb-4">
                        Your school administrator has not generated a QR code yet. 
                        Please contact your administrator to generate the school QR code.
                    </p>
                    
                    <div class="alert alert-info text-start mb-4">
                        <h6 class="alert-heading">
                            <i class="bx bx-info-circle me-2"></i> What you can do:
                        </h6>
                        <ul class="mb-0">
                            <li>Contact your school administrator</li>
                            <li>Request them to generate the school QR code</li>
                            <li>Once generated, you'll be able to access it here</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning text-start">
                        <h6 class="alert-heading">
                            <i class="bx bx-info-circle me-2"></i> About School QR Code:
                        </h6>
                        <ul class="mb-0">
                            <li>Only administrators can generate school QR codes</li>
                            <li>One QR code per school (one-time generation)</li>
                            <li>Used for UPI payment collection</li>
                            <li>Accountants can view and use the QR code</li>
                        </ul>
                    </div>

                    <a href="{{ route('accountant.payment.school-qr-codes.index') }}" class="btn btn-primary">
                        <i class="bx bx-refresh me-1"></i> Refresh Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

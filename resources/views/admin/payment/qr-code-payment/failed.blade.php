@extends('admin.layout.app')

@section('title', 'Payment Failed')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payment.qr-code-payment.index') }}">QR Code Payment</a></li>
                        <li class="breadcrumb-item active">Failed</li>
                    </ol>
                </div>
                <h4 class="page-title">Payment Failed</h4>
            </div>
        </div>
    </div>

    <!-- Failed Message -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title rounded-circle bg-soft-danger text-danger font-size-24">
                            <i class="mdi mdi-close"></i>
                        </div>
                    </div>
                    
                    <h3 class="text-danger mb-3">Payment Failed</h3>
                    <p class="text-muted mb-4">
                        Unfortunately, your payment could not be processed. Please try again or contact support.
                    </p>

                    <!-- Payment Details -->
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Payment Details</h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-2"><strong>Payment ID:</strong></p>
                                            <p class="mb-2"><strong>Amount:</strong></p>
                                            <p class="mb-2"><strong>QR Codes:</strong></p>
                                            <p class="mb-2"><strong>Method:</strong></p>
                                            <p class="mb-0"><strong>Status:</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-2 text-muted">{{ $payment->payment_id }}</p>
                                            <p class="mb-2 text-muted">₹{{ number_format($payment->amount, 2) }}</p>
                                            <p class="mb-2 text-muted">{{ $payment->qr_codes_purchased }}</p>
                                            <p class="mb-2 text-muted">{{ ucfirst($payment->payment_method) }}</p>
                                            <p class="mb-0 text-muted">
                                                <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    @if($payment->failure_reason)
                                        <div class="mt-3">
                                            <strong>Failure Reason:</strong>
                                            <p class="text-muted">{{ $payment->failure_reason }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <a href="{{ route('admin.payment.qr-code-payment.index') }}" class="btn btn-primary me-2">
                            <i class="mdi mdi-credit-card me-1"></i> Try Again
                        </a>
                        <a href="{{ route('admin.payment.qr-code-payment.history') }}" class="btn btn-outline-primary me-2">
                            <i class="mdi mdi-history me-1"></i> Payment History
                        </a>
                        <a href="{{ route('admin.payment.school-qr-codes.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-qrcode me-1"></i> Back to QR Codes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

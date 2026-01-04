@extends('admin.layout.app')

@section('title', 'Payment Successful')

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
                        <li class="breadcrumb-item active">Success</li>
                    </ol>
                </div>
                <h4 class="page-title">Payment Successful</h4>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title rounded-circle bg-soft-success text-success font-size-24">
                            <i class="mdi mdi-check"></i>
                        </div>
                    </div>
                    
                    <h3 class="text-success mb-3">Payment Completed Successfully!</h3>
                    <p class="text-muted mb-4">
                        Your payment has been processed and your QR code limit has been increased.
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
                                            <p class="mb-2"><strong>QR Codes Purchased:</strong></p>
                                            <p class="mb-2"><strong>Payment Method:</strong></p>
                                            <p class="mb-0"><strong>Transaction Date:</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-2 text-muted">{{ $payment->payment_id }}</p>
                                            <p class="mb-2 text-muted">₹{{ number_format($payment->amount, 2) }}</p>
                                            <p class="mb-2 text-muted">{{ $payment->qr_codes_purchased }}</p>
                                            <p class="mb-2 text-muted">{{ ucfirst($payment->payment_method) }}</p>
                                            <p class="mb-0 text-muted">{{ $payment->paid_at->format('d M Y H:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Updated Status -->
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-8">
                            <div class="alert alert-success">
                                <h5 class="alert-heading">QR Code Limit Updated!</h5>
                                <p class="mb-0">
                                    Your QR code limit has been increased by <strong>{{ $payment->qr_codes_purchased }}</strong> codes.
                                    You can now generate up to <strong>{{ $payment->school->qr_code_limit }}</strong> QR codes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <a href="{{ route('admin.payment.school-qr-codes.index') }}" class="btn btn-primary me-2">
                            <i class="mdi mdi-qrcode me-1"></i> Generate QR Codes
                        </a>
                        <a href="{{ route('admin.payment.qr-code-payment.history') }}" class="btn btn-outline-primary me-2">
                            <i class="mdi mdi-history me-1"></i> Payment History
                        </a>
                        <a href="{{ route('admin.payment.qr-code-payment.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-credit-card me-1"></i> Make Another Payment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

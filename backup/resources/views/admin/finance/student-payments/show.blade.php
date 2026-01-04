@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Payment #{{ $payment->id }}</h6>
                    <a href="{{ route('admin.finance.student-payments.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div><strong>Student:</strong> {{ optional($payment->student)->full_name }}</div>
                            <div><strong>Date:</strong> {{ $payment->payment_date->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div><strong>Amount:</strong> {{ number_format($payment->amount, 2) }}</div>
                            <div><strong>Method:</strong> {{ ucfirst($payment->method) }}</div>
                            <div><strong>Status:</strong> {{ ucfirst($payment->status) }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-2">Reference</div>
                        <div>{{ $payment->reference ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="fw-bold mb-2">Notes</div>
                        <div>{{ $payment->notes }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



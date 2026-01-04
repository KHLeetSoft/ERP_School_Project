@extends('student.layout.app')

@section('title', 'Fee Details')
@section('page-title', 'Fee Details')

@section('content')
<div class="row">
    <!-- Fee Information -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">Fee Record #{{ $fee->id }}</h4>
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar me-1"></i>
                            Fee Date: {{ \Carbon\Carbon::parse($fee->fee_date)->format('M d, Y') }}
                        </p>
                        <p class="text-muted mb-1">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Class: {{ $fee->schoolClass->name ?? 'N/A' }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-rupee-sign me-1"></i>
                            Amount: ₹{{ number_format($fee->amount, 2) }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="fee-status">
                            @if($fee->payment_mode && $fee->transaction_id)
                                <span class="badge bg-success fs-6">Paid</span>
                            @elseif($fee->fee_date && \Carbon\Carbon::now()->gt($fee->fee_date))
                                <span class="badge bg-danger fs-6">Overdue</span>
                            @else
                                <span class="badge bg-warning fs-6">Pending</span>
                            @endif
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('student.fees.invoice', $fee->id) }}" class="btn btn-outline-success">
                                <i class="fas fa-file-invoice me-1"></i>Generate Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Details -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Fee Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fee ID</label>
                        <p class="form-control-plaintext">#{{ $fee->id }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fee Date</label>
                        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($fee->fee_date)->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Class</label>
                        <p class="form-control-plaintext">{{ $fee->schoolClass->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Amount</label>
                        <p class="form-control-plaintext">
                            <strong class="text-success fs-5">₹{{ number_format($fee->amount, 2) }}</strong>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Mode</label>
                        <p class="form-control-plaintext">
                            @if($fee->payment_mode)
                                <span class="badge bg-info">{{ ucfirst($fee->payment_mode) }}</span>
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Transaction ID</label>
                        <p class="form-control-plaintext">
                            @if($fee->transaction_id)
                                <code>{{ $fee->transaction_id }}</code>
                            @else
                                <span class="text-muted">Not available</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($fee->remarks)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Remarks</label>
                            <div class="alert alert-info">
                                <i class="fas fa-comment me-2"></i>{{ $fee->remarks }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Status</h5>
            </div>
            <div class="card-body">
                @if($fee->payment_mode && $fee->transaction_id)
                    <div class="payment-status paid">
                        <div class="status-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="status-content">
                            <h6 class="mb-1">Payment Completed</h6>
                            <p class="text-muted mb-0">This fee has been successfully paid.</p>
                        </div>
                    </div>
                    
                    <div class="payment-details mt-3">
                        <div class="detail-item">
                            <span class="detail-label">Payment Method:</span>
                            <span class="detail-value">{{ ucfirst($fee->payment_mode) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Transaction ID:</span>
                            <span class="detail-value"><code>{{ $fee->transaction_id }}</code></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Amount Paid:</span>
                            <span class="detail-value text-success">₹{{ number_format($fee->amount, 2) }}</span>
                        </div>
                    </div>
                @elseif($fee->fee_date && \Carbon\Carbon::now()->gt($fee->fee_date))
                    <div class="payment-status overdue">
                        <div class="status-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="status-content">
                            <h6 class="mb-1">Payment Overdue</h6>
                            <p class="text-muted mb-0">This fee is past its due date.</p>
                        </div>
                    </div>
                    
                    <div class="overdue-info mt-3">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Overdue Notice:</strong> Please contact the school office to make payment arrangements.
                        </div>
                    </div>
                @else
                    <div class="payment-status pending">
                        <div class="status-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="status-content">
                            <h6 class="mb-1">Payment Pending</h6>
                            <p class="text-muted mb-0">This fee is awaiting payment.</p>
                        </div>
                    </div>
                    
                    <div class="pending-info mt-3">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Payment Required:</strong> Please make payment before the due date to avoid late fees.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment History -->
    @if($payments->count() > 0)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Payment History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <strong class="text-success">₹{{ number_format($payment->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $this->getPaymentMethodColor($payment->payment_method) }}">
                                                {{ ucfirst($payment->payment_method) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment->transaction_id)
                                                <code>{{ $payment->transaction_id }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($payment->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($payment->status === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->notes)
                                                {{ $payment->notes }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Navigation -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.fees.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Fees
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.fees.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Payment History
                        </a>
                        <a href="{{ route('student.fees.invoice', $fee->id) }}" class="btn btn-outline-success">
                            <i class="fas fa-file-invoice me-2"></i>Generate Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .payment-status {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .payment-status.paid {
        background: #d4edda;
        border-left: 4px solid #28a745;
    }

    .payment-status.overdue {
        background: #f8d7da;
        border-left: 4px solid #dc3545;
    }

    .payment-status.pending {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
    }

    .status-icon {
        font-size: 2rem;
        margin-right: 1rem;
    }

    .payment-status.paid .status-icon {
        color: #28a745;
    }

    .payment-status.overdue .status-icon {
        color: #dc3545;
    }

    .payment-status.pending .status-icon {
        color: #ffc107;
    }

    .status-content h6 {
        margin: 0;
        font-weight: bold;
    }

    .payment-details {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 1rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #6c757d;
    }

    .detail-value {
        font-weight: 500;
    }
</style>
@endsection

@php
    function getPaymentMethodColor($method) {
        switch(strtolower($method)) {
            case 'cash':
                return 'success';
            case 'card':
            case 'credit card':
            case 'debit card':
                return 'primary';
            case 'bank transfer':
            case 'transfer':
                return 'info';
            case 'cheque':
            case 'check':
                return 'warning';
            case 'online':
                return 'secondary';
            default:
                return 'light';
        }
    }
@endphp

@extends('student.layout.app')

@section('title', 'Fee Invoice')
@section('page-title', 'Fee Invoice')

@section('content')
<div class="row">
    <!-- Invoice Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="mb-3">Fee Invoice</h3>
                <h5 class="text-muted mb-2">Invoice #{{ $fee->id }}</h5>
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar me-1"></i>
                    Generated on {{ \Carbon\Carbon::now()->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Student Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Student Name:</td>
                                <td>{{ $studentUser->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Class:</td>
                                <td>{{ $fee->schoolClass->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Roll No:</td>
                                <td>{{ $student->roll_no ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Admission No:</td>
                                <td>{{ $student->admission_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Academic Year:</td>
                                <td>{{ date('Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Invoice Date:</td>
                                <td>{{ \Carbon\Carbon::parse($fee->fee_date)->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Details -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Fee Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Fee Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>School Fee</strong>
                                    <br><small class="text-muted">Class: {{ $fee->schoolClass->name ?? 'N/A' }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($fee->fee_date)->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <strong>₹{{ number_format($fee->amount, 2) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">Total Amount:</th>
                                <th class="text-end">
                                    <strong class="text-success fs-5">₹{{ number_format($fee->amount, 2) }}</strong>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    @if($fee->payment_mode && $fee->transaction_id)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="payment-info">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>Payment Completed
                                </h6>
                                <div class="payment-details">
                                    <div class="detail-row">
                                        <span class="detail-label">Payment Method:</span>
                                        <span class="detail-value">{{ ucfirst($fee->payment_mode) }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Transaction ID:</span>
                                        <span class="detail-value"><code>{{ $fee->transaction_id }}</code></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Amount Paid:</span>
                                        <span class="detail-value text-success">₹{{ number_format($fee->amount, 2) }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Payment Date:</span>
                                        <span class="detail-value">{{ \Carbon\Carbon::parse($fee->fee_date)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-status">
                                <div class="status-badge paid">
                                    <i class="fas fa-check-circle"></i>
                                    <span>PAID</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Payment Required</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="payment-info">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-clock me-2"></i>Payment Pending
                                </h6>
                                <p class="text-muted">
                                    This fee is awaiting payment. Please contact the school office to make payment arrangements.
                                </p>
                                <div class="payment-details">
                                    <div class="detail-row">
                                        <span class="detail-label">Amount Due:</span>
                                        <span class="detail-value text-danger">₹{{ number_format($fee->amount, 2) }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Due Date:</span>
                                        <span class="detail-value">{{ \Carbon\Carbon::parse($fee->fee_date)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="payment-status">
                                <div class="status-badge pending">
                                    <i class="fas fa-clock"></i>
                                    <span>PENDING</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <strong>₹{{ number_format($payment->amount, 2) }}</strong>
                                        </td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Invoice Footer -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-md-4">
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-label">Student Signature</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-label">Parent/Guardian Signature</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-label">School Authority</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.fees.show', $fee->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Fee Details
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="printInvoice()">
                            <i class="fas fa-print me-2"></i>Print Invoice
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="downloadInvoice()">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .payment-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .payment-details {
        margin-top: 1rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #6c757d;
    }

    .detail-value {
        font-weight: 500;
    }

    .payment-status {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .status-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
        border-radius: 50%;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .status-badge.paid {
        background: #28a745;
    }

    .status-badge.pending {
        background: #ffc107;
    }

    .status-badge i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .signature-section {
        margin: 1rem 0;
    }

    .signature-line {
        width: 100%;
        height: 2px;
        background-color: #000;
        margin-bottom: 0.5rem;
    }

    .signature-label {
        margin: 0;
        font-weight: bold;
        color: #6c757d;
    }

    @media print {
        .btn, .card-header, .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .page-break {
            page-break-before: always;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Print Invoice
    function printInvoice() {
        window.print();
    }

    // Download Invoice (placeholder)
    function downloadInvoice() {
        alert('PDF download functionality would be implemented here.');
    }
</script>
@endsection

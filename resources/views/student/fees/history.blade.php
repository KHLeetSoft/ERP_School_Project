@extends('student.layout.app')

@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('content')
<div class="row">
    <!-- Payment Statistics -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Total Payments</h6>
                            <h4 class="mb-0">{{ $stats['total_payments'] }}</h4>
                            <small class="text-muted">All transactions</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Completed</h6>
                            <h4 class="mb-0">{{ $stats['completed_payments'] }}</h4>
                            <small class="text-muted">Successful payments</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Pending</h6>
                            <h4 class="mb-0">{{ $stats['pending_payments'] }}</h4>
                            <small class="text-muted">Awaiting processing</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Total Amount</h6>
                            <h4 class="mb-0">₹{{ number_format($stats['total_amount'], 2) }}</h4>
                            <small class="text-muted">All payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('student.fees.history') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ $status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="year" class="form-label">Year</label>
                        <select name="year" id="year" class="form-select">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('student.fees.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Fees
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Payment History</h5>
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Fee</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>
                                            <div>
                                                {{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>Fee #{{ $payment->fee_id }}</strong>
                                                @if($payment->fee && $payment->fee->schoolClass)
                                                    <br><small class="text-muted">{{ $payment->fee->schoolClass->name }}</small>
                                                @endif
                                            </div>
                                        </td>
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
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Completed
                                                </span>
                                            @elseif($payment->status === 'pending')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                            @elseif($payment->status === 'failed')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Failed
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-question me-1"></i>{{ ucfirst($payment->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('student.fees.show', $payment->fee_id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('student.fees.invoice', $payment->fee_id) }}" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No Payment History</h4>
                        <p class="text-muted">No payment records found for the selected criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Summary Chart -->
    @if($payments->count() > 0)
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Payment Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Payment Methods</h5>
                </div>
                <div class="card-body">
                    @php
                        $paymentMethods = $payments->groupBy('payment_method')->map(function ($methodPayments) {
                            return [
                                'method' => $methodPayments->first()->payment_method,
                                'count' => $methodPayments->count(),
                                'amount' => $methodPayments->sum('amount'),
                            ];
                        })->sortByDesc('amount');
                    @endphp
                    
                    @foreach($paymentMethods as $method)
                        <div class="payment-method-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">{{ ucfirst($method['method']) }}</span>
                                <span class="badge bg-{{ $this->getPaymentMethodColor($method['method']) }}">
                                    {{ $method['count'] }} payment(s)
                                </span>
                            </div>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar bg-{{ $this->getPaymentMethodColor($method['method']) }}" 
                                     style="width: {{ ($method['amount'] / $stats['total_amount']) * 100 }}%"></div>
                            </div>
                            <small class="text-muted">₹{{ number_format($method['amount'], 2) }}</small>
                        </div>
                    @endforeach
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
                        <a href="{{ route('student.fees.structure') }}" class="btn btn-outline-info">
                            <i class="fas fa-list me-2"></i>Fee Structure
                        </a>
                        <button type="button" class="btn btn-outline-success" onclick="printHistory()">
                            <i class="fas fa-print me-2"></i>Print History
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
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .payment-method-item {
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 4px solid #007bff;
    }

    .progress {
        height: 6px;
        border-radius: 3px;
    }

    @media print {
        .btn, .card-header, .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Payment Trend Chart
    @if($payments->count() > 0)
        const ctx = document.getElementById('paymentChart').getContext('2d');
        const paymentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($payments->pluck('created_at')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })) !!},
                datasets: [{
                    label: 'Payment Amount',
                    data: {!! json_encode($payments->pluck('amount')) !!},
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    @endif

    // Print History
    function printHistory() {
        window.print();
    }
</script>
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

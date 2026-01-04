@extends('student.layout.app')

@section('title', 'My Fees')
@section('page-title', 'Fees')

@section('content')
<div class="row">
    <!-- Fee Statistics -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Total Fees</h6>
                            <h4 class="mb-0">{{ $stats['total_fees'] }}</h4>
                            <small class="text-muted">All fees</small>
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
                            <h6 class="mb-0">Paid</h6>
                            <h4 class="mb-0">{{ $stats['paid_fees'] }}</h4>
                            <small class="text-muted">Completed payments</small>
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
                            <h4 class="mb-0">{{ $stats['pending_fees'] }}</h4>
                            <small class="text-muted">Awaiting payment</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Overdue</h6>
                            <h4 class="mb-0">{{ $stats['overdue_fees'] }}</h4>
                            <small class="text-muted">Past due date</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Amount Summary -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="amount-card">
                    <div class="amount-header">
                        <h6 class="mb-0">Total Amount</h6>
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="amount-value">
                        <h3 class="mb-0">₹{{ number_format($stats['total_amount'], 2) }}</h3>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="amount-card paid">
                    <div class="amount-header">
                        <h6 class="mb-0">Paid Amount</h6>
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="amount-value">
                        <h3 class="mb-0">₹{{ number_format($stats['paid_amount'], 2) }}</h3>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="amount-card pending">
                    <div class="amount-header">
                        <h6 class="mb-0">Pending Amount</h6>
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="amount-value">
                        <h3 class="mb-0">₹{{ number_format($stats['pending_amount'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Year Filter -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('student.fees.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="form-select">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $academicYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('student.fees.structure') }}" class="btn btn-outline-info">
                            <i class="fas fa-list me-1"></i>Fee Structure
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Fees List -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Fee Records</h5>
            </div>
            <div class="card-body">
                @if($fees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fee Date</th>
                                    <th>Class</th>
                                    <th>Amount</th>
                                    <th>Payment Mode</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fees as $fee)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($fee->fee_date)->format('M d, Y') }}</td>
                                        <td>{{ $fee->schoolClass->name ?? 'N/A' }}</td>
                                        <td>
                                            <strong>₹{{ number_format($fee->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($fee->payment_mode)
                                                <span class="badge bg-info">{{ ucfirst($fee->payment_mode) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fee->transaction_id)
                                                <code>{{ $fee->transaction_id }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fee->payment_mode && $fee->transaction_id)
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($fee->fee_date && \Carbon\Carbon::now()->gt($fee->fee_date))
                                                <span class="badge bg-danger">Overdue</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('student.fees.show', $fee->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('student.fees.invoice', $fee->id) }}" class="btn btn-sm btn-outline-success">
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
                        <i class="fas fa-receipt text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No Fees Found</h4>
                        <p class="text-muted">No fee records found for the selected academic year.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Fee Structure Preview -->
    @if($feeStructure->count() > 0)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Fee Structure Preview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($feeStructure as $fee)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="fee-structure-card">
                                    <div class="fee-header">
                                        <h6 class="fee-type">{{ $fee->fee_type }}</h6>
                                        <span class="fee-frequency">{{ ucfirst($fee->frequency) }}</span>
                                    </div>
                                    <div class="fee-amount">
                                        <h4 class="mb-0">₹{{ number_format($fee->amount, 2) }}</h4>
                                    </div>
                                    @if($fee->due_date)
                                        <div class="fee-due-date">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Due: {{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}
                                            </small>
                                        </div>
                                    @endif
                                    @if($fee->description)
                                        <div class="fee-description">
                                            <small class="text-muted">{{ $fee->description }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.fees.structure') }}" class="btn btn-outline-info">
                            <i class="fas fa-list me-2"></i>Fee Structure
                        </a>
                        <a href="{{ route('student.fees.history') }}" class="btn btn-outline-success">
                            <i class="fas fa-history me-2"></i>Payment History
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

    .amount-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #007bff;
    }

    .amount-card.paid {
        border-left-color: #28a745;
    }

    .amount-card.pending {
        border-left-color: #ffc107;
    }

    .amount-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .amount-header i {
        font-size: 1.5rem;
        color: #6c757d;
    }

    .amount-value h3 {
        color: #333;
        font-weight: bold;
    }

    .fee-structure-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        border-left: 4px solid #007bff;
        height: 100%;
    }

    .fee-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .fee-type {
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    .fee-frequency {
        background: #007bff;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .fee-amount {
        margin-bottom: 1rem;
    }

    .fee-amount h4 {
        color: #28a745;
        font-weight: bold;
    }

    .fee-due-date {
        margin-bottom: 0.5rem;
    }

    .fee-description {
        font-style: italic;
    }
</style>
@endsection

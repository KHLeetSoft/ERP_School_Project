@extends('accountant.layout.app')

@section('title', 'Student Fees - ' . $student->first_name . ' ' . $student->last_name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Fees</h1>
        <a href="{{ route('accountant.students') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>

    <!-- Student Information -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="font-weight-bold">{{ $student->first_name }} {{ $student->last_name }}</h5>
                            <p class="text-muted mb-1">
                                <strong>Admission No:</strong> {{ $student->admission_no ?? 'N/A' }} | 
                                <strong>Student ID:</strong> {{ $student->student_id ?? 'N/A' }}
                            </p>
                            <p class="text-muted mb-1">
                                <strong>Class:</strong> {{ $student->schoolClass->name ?? 'N/A' }} | 
                                <strong>Section:</strong> {{ $student->section->name ?? 'N/A' }}
                            </p>
                            <p class="text-muted mb-0">
                                <strong>Phone:</strong> {{ $student->phone ?? 'N/A' }} | 
                                <strong>Email:</strong> {{ $student->email ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge badge-{{ $student->status ? 'success' : 'danger' }} badge-lg">
                                {{ $student->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fees and Payments Tabs -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="feesTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="fees-tab" data-toggle="tab" href="#fees" role="tab" aria-controls="fees" aria-selected="true">
                                <i class="fas fa-list"></i> Fees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab" aria-controls="payments" aria-selected="false">
                                <i class="fas fa-credit-card"></i> Payments
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="feesTabsContent">
                        <!-- Fees Tab -->
                        <div class="tab-pane fade show active" id="fees" role="tabpanel" aria-labelledby="fees-tab">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Fee ID</th>
                                            <th>Amount</th>
                                            <th>Fee Date</th>
                                            <th>Payment Mode</th>
                                            <th>Transaction ID</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($fees as $fee)
                                        <tr>
                                            <td>#{{ $fee->id }}</td>
                                            <td>₹{{ number_format($fee->amount, 2) }}</td>
                                            <td>{{ $fee->fee_date ? $fee->fee_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ ucfirst($fee->payment_mode ?? 'N/A') }}</td>
                                            <td>{{ $fee->transaction_id ?? 'N/A' }}</td>
                                            <td>
                                                @if($fee->transaction_id)
                                                    <span class="badge badge-success">Paid</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $fee->remarks ?? 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No fees found for this student.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payments Tab -->
                        <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Payment ID</th>
                                            <th>Amount</th>
                                            <th>Payment Method</th>
                                            <th>Transaction ID</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                        <tr>
                                            <td>#{{ $payment->id }}</td>
                                            <td>₹{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payment->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->created_at ? $payment->created_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $payment->notes ?? 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No payments found for this student.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Fees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₹{{ number_format($fees->sum('amount'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Paid Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₹{{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₹{{ number_format($fees->whereNull('transaction_id')->sum('amount'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('accountant.layouts.app')

@section('title', 'Payment Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Payment Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-truncate font-size-14 mb-2">Today's Collection</p>
                            <h4 class="mb-2">₹{{ number_format($todayPayments, 2) }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-primary rounded">
                                <i class="mdi mdi-currency-inr font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-truncate font-size-14 mb-2">Monthly Collection</p>
                            <h4 class="mb-2">₹{{ number_format($monthlyPayments, 2) }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-success rounded">
                                <i class="mdi mdi-chart-line font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-truncate font-size-14 mb-2">Pending Payments</p>
                            <h4 class="mb-2">{{ $pendingPayments }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-warning rounded">
                                <i class="mdi mdi-clock-outline font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-truncate font-size-14 mb-2">Total Students</p>
                            <h4 class="mb-2">{{ $totalStudents }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-info rounded">
                                <i class="mdi mdi-account-group font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('accountant.payment.payments.create') }}" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="mdi mdi-plus"></i><br>
                                Record Payment
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('accountant.payment.qr-scanner') }}" class="btn btn-success btn-lg w-100 mb-3">
                                <i class="mdi mdi-qrcode-scan"></i><br>
                                QR Scanner
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('accountant.payment.online-payment') }}" class="btn btn-info btn-lg w-100 mb-3">
                                <i class="mdi mdi-credit-card"></i><br>
                                Online Payment
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('accountant.payment.payments.index') }}" class="btn btn-warning btn-lg w-100 mb-3">
                                <i class="mdi mdi-history"></i><br>
                                Payment History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Recent Payments</h4>
                    <a href="{{ route('accountant.payment.payments.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $payment->student->first_name }} {{ $payment->student->last_name }}</h6>
                                                    <small class="text-muted">{{ $payment->student->admission_no }}</small>
                                                </div>
                                            </td>
                                            <td><strong>₹{{ number_format($payment->amount, 2) }}</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $payment->method == 'cash' ? 'secondary' : ($payment->method == 'online' ? 'success' : 'primary') }}">
                                                    {{ ucfirst($payment->method) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-information-outline font-size-48 text-muted"></i>
                            <p class="text-muted mt-2">No recent payments found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Available Payment Methods -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Active Payment Gateways</h4>
                </div>
                <div class="card-body">
                    @if($activeGateways->count() > 0)
                        @foreach($activeGateways as $gateway)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-light rounded">
                                        <i class="mdi mdi-credit-card font-size-18"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $gateway->display_name }}</h6>
                                    <small class="text-muted">{{ ucfirst($gateway->gateway_name) }} - {{ $gateway->is_test_mode ? 'Test Mode' : 'Live Mode' }}</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge badge-success">Active</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No active payment gateways configured</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Available QR Codes</h4>
                </div>
                <div class="card-body">
                    @if($activeQrCodes->count() > 0)
                        @foreach($activeQrCodes as $qrCode)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-light rounded">
                                        <i class="mdi mdi-qrcode font-size-18"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $qrCode->title }}</h6>
                                    <small class="text-muted">{{ ucfirst($qrCode->qr_type) }} - Used: {{ $qrCode->usage_count }}/{{ $qrCode->max_usage ?: '∞' }}</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge badge-success">Available</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No QR codes available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('superadmin.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bx bx-bar-chart me-2"></i>Payment Reports & Analytics
                    </h1>
                    <p class="text-muted mb-0">Comprehensive payment analytics and reporting dashboard</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success" onclick="exportReports('excel')">
                        <i class="bx bx-download me-2"></i>Export Excel
                    </button>
                    <button class="btn btn-outline-primary" onclick="exportReports('pdf')">
                        <i class="bx bx-file me-2"></i>Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form method="GET" id="dateFilterForm">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="date_from" class="form-label fw-bold">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                       value="{{ $dateRange[0]->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label fw-bold">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                       value="{{ $dateRange[1]->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter me-2"></i>Apply Filter
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-secondary" onclick="resetDateFilter()">
                                    <i class="bx bx-refresh me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-credit-card display-4 mb-2"></i>
                    <h3 class="mb-0">{{ $stats['total_transactions'] }}</h3>
                    <small>Total Transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-check-circle display-4 mb-2"></i>
                    <h3 class="mb-0">{{ $stats['successful_transactions'] }}</h3>
                    <small>Successful</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-x-circle display-4 mb-2"></i>
                    <h3 class="mb-0">{{ $stats['failed_transactions'] }}</h3>
                    <small>Failed</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-time display-4 mb-2"></i>
                    <h3 class="mb-0">{{ $stats['pending_transactions'] }}</h3>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-rupee display-4 mb-2"></i>
                    <h3 class="mb-0">₹{{ number_format($stats['total_revenue']) }}</h3>
                    <small>Total Revenue</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bx bx-trending-up display-4 mb-2"></i>
                    <h3 class="mb-0">{{ $stats['success_rate'] }}%</h3>
                    <small>Success Rate</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bx bx-line-chart me-2"></i>Monthly Revenue Trend
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bx bx-pie-chart me-2"></i>Payment Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Tables -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bx bx-credit-card me-2"></i>Top Performing Gateways
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Gateway</th>
                                    <th>Transactions</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topGateways as $gateway)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                    <i class="bx bx-credit-card text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $gateway->name }}</strong><br>
                                                <small class="text-muted">{{ $gateway->provider }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $gateway->transactions_count }}</span>
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($gateway->transactions_sum_amount) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bx bx-package me-2"></i>Top Performing Plans
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Transactions</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topPlans as $plan)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                    <i class="bx bx-package text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $plan->name }}</strong><br>
                                                <small class="text-muted">₹{{ number_format($plan->price) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $plan->transactions_count }}</span>
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($plan->transactions_sum_amount) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Performance -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bx bx-building me-2"></i>School Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Transactions</th>
                                    <th>Revenue</th>
                                    <th>Success Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schoolPerformance as $school)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bx bx-building text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $school->name }}</strong><br>
                                                <small class="text-muted">{{ $school->address ?? 'No address' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $school->transactions_count }}</span>
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($school->transactions_sum_amount) }}</strong>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $school->transactions_count > 0 ? ($school->transactions_count / $school->transactions_count) * 100 : 0 }}%">
                                                {{ $school->transactions_count > 0 ? round(($school->transactions_count / $school->transactions_count) * 100) : 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Revenue (₹)',
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Initialize Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusDistribution->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($statusDistribution->pluck('count')) !!},
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

function exportReports(format) {
    const url = new URL('{{ route("superadmin.payment.reports.export") }}', window.location.origin);
    url.searchParams.append('format', format);
    url.searchParams.append('date_from', document.getElementById('date_from').value);
    url.searchParams.append('date_to', document.getElementById('date_to').value);
    
    window.open(url.toString(), '_blank');
}

function resetDateFilter() {
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('dateFilterForm').submit();
}
</script>
@endsection

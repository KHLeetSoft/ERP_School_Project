@extends('admin.layout.app')

@section('title', 'Fees Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fees Dashboard</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.fees.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> View All Fees
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalFees }}</h3>
                                    <p>Total Fees</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $paidFees }}</h3>
                                    <p>Paid Fees</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $pendingFees }}</h3>
                                    <p>Pending Fees</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $overdueFees }}</h3>
                                    <p>Overdue Fees</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="row mt-4">
                        <div class="col-lg-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-rupee-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Amount</span>
                                    <span class="info-box-number">₹{{ number_format($totalAmount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Paid Amount</span>
                                    <span class="info-box-number">₹{{ number_format($paidAmount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pending Amount</span>
                                    <span class="info-box-number">₹{{ number_format($pendingAmount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Revenue Chart -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Monthly Revenue (Last 12 Months)</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Month/Year</th>
                                                    <th>Total Revenue</th>
                                                    <th>Paid Fees</th>
                                                    <th>Pending Fees</th>
                                                    <th>Collection Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($monthlyRevenue as $revenue)
                                                <tr>
                                                    <td>{{ date('F', mktime(0, 0, 0, $revenue->month, 1)) }} {{ $revenue->year }}</td>
                                                    <td>₹{{ number_format($revenue->total, 2) }}</td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            {{ $revenue->paid_fees ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">
                                                            {{ $revenue->pending_fees ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $collectionRate = $revenue->total > 0 ? round((($revenue->paid_fees ?? 0) / $revenue->total) * 100, 1) : 0;
                                                        @endphp
                                                        <span class="badge badge-{{ $collectionRate > 80 ? 'success' : ($collectionRate > 50 ? 'warning' : 'danger') }}">
                                                            {{ $collectionRate }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No revenue data available</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Quick Actions</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.fees.create') }}" class="btn btn-primary btn-block">
                                                <i class="fas fa-plus"></i> New Fee
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.fees.index') }}" class="btn btn-info btn-block">
                                                <i class="fas fa-list"></i> View All Fees
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.fees.export') }}" class="btn btn-success btn-block">
                                                <i class="fas fa-download"></i> Export Data
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning btn-block" onclick="generateReport()">
                                                <i class="fas fa-chart-bar"></i> Generate Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateReport() {
    // Implementation for report generation
    alert('Report generation functionality will be implemented soon');
}
</script>
@endsection

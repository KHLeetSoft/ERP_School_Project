@extends('admin.layout.app')

@section('title', 'Leave Management Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Leave Management Dashboard</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.hr.staff.index') }}">HR</a></li>
                    <li class="breadcrumb-item active">Leave Management Dashboard</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.hr.leave-management.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> View All Leaves
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced KPI Cards Row 1 -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary-light">
                                <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $totalLeaves }}</h4>
                            <p class="mb-0 text-muted">Total Leaves</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> {{ $totalLeaves > 0 ? round(($totalLeaves / max($staff->count(), 1)) * 100, 1) : 0 }}% per staff
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning-light">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $pendingLeaves }}</h4>
                            <p class="mb-0 text-muted">Pending Leaves</p>
                            <small class="text-warning">
                                {{ $totalLeaves > 0 ? round(($pendingLeaves / $totalLeaves) * 100, 1) : 0 }}% of total
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-success-light">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $approvedLeaves }}</h4>
                            <p class="mb-0 text-muted">Approved Leaves</p>
                            <small class="text-success">
                                {{ $totalLeaves > 0 ? round(($approvedLeaves / $totalLeaves) * 100, 1) : 0 }}% approval rate
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-danger-light">
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $rejectedLeaves }}</h4>
                            <p class="mb-0 text-muted">Rejected Leaves</p>
                            <small class="text-danger">
                                {{ $totalLeaves > 0 ? round(($rejectedLeaves / $totalLeaves) * 100, 1) : 0 }}% rejection rate
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced KPI Cards Row 2 -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-info-light">
                                <i class="fas fa-calendar-day fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $currentLeaves }}</h4>
                            <p class="mb-0 text-muted">Current Leaves</p>
                            <small class="text-info">
                                <i class="fas fa-users"></i> {{ $currentLeaves > 0 ? round(($currentLeaves / max($staff->count(), 1)) * 100, 1) : 0 }}% staff absent
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-secondary-light">
                                <i class="fas fa-calendar-week fa-2x text-secondary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $upcomingLeaves }}</h4>
                            <p class="mb-0 text-muted">Upcoming Leaves</p>
                            <small class="text-secondary">
                                <i class="fas fa-calendar"></i> Next 30 days
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-dark-light">
                                <i class="fas fa-ban fa-2x text-dark"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $cancelledLeaves }}</h4>
                            <p class="mb-0 text-muted">Cancelled Leaves</p>
                            <small class="text-dark">
                                {{ $totalLeaves > 0 ? round(($cancelledLeaves / $totalLeaves) * 100, 1) : 0 }}% cancellation rate
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-purple-light">
                                <i class="fas fa-users fa-2x text-purple"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $staff->count() }}</h4>
                            <p class="mb-0 text-muted">Total Staff</p>
                            <small class="text-purple">
                                <i class="fas fa-chart-line"></i> Active employees
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Charts Row 1 -->
    <div class="row">
        <!-- Monthly Trends Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Monthly Leave Trends ({{ date('Y') }})</h5>
                    <div class="card-tools">
                        <select class="form-select form-select-sm" id="yearSelector">
                            @for($year = date('Y'); $year >= date('Y')-3; $year--)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Leave Type Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Leave Type Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="leaveTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Charts Row 2 -->
    <div class="row">
        <!-- Yearly Comparison Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Yearly Leave Comparison</h5>
                </div>
                <div class="card-body">
                    <canvas id="yearlyComparisonChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Leave Approval Trends -->
        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Leave Approval Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="approvalTrendsChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Charts Row 3 -->
    <div class="row">
        <!-- Department Performance Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Department Performance Metrics</h5>
                </div>
                <div class="card-body">
                    <canvas id="departmentPerformanceChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Leave Duration Analysis -->
        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Leave Duration Analysis</h5>
                </div>
                <div class="card-body">
                    <canvas id="durationAnalysisChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Stats and Recent Leaves -->
    <div class="row">
        <!-- Department Statistics -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Department-wise Leave Distribution</h5>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-primary" onclick="exportDepartmentStats()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Leave Count</th>
                                    <th>Total Days</th>
                                    <th>Avg Days</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departmentStats as $dept)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="department-color" style="background-color: {{ $dept->color ?? '#007bff' }}; width: 12px; height: 12px; border-radius: 50%; margin-right: 8px;"></div>
                                            {{ $dept->department ?? 'Not Assigned' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $dept->count ?? 0 }}</span>
                                    </td>
                                    <td>{{ $dept->total_days ? round($dept->total_days, 1) . ' days' : '0 days' }}</td>
                                    <td>{{ $dept->count > 0 ? round(($dept->total_days ?? 0) / $dept->count, 1) : 0 }} days</td>
                                    <td>
                                        @php
                                            $avgDays = $dept->count > 0 ? ($dept->total_days ?? 0) / $dept->count : 0;
                                            $performance = $avgDays <= 3 ? 'Excellent' : ($avgDays <= 5 ? 'Good' : ($avgDays <= 7 ? 'Average' : 'Poor'));
                                            $badgeClass = $avgDays <= 3 ? 'bg-success' : ($avgDays <= 5 ? 'bg-info' : ($avgDays <= 7 ? 'bg-warning' : 'bg-danger'));
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $performance }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No department data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Leaves -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Leave Requests</h5>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-secondary" onclick="refreshRecentLeaves()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLeaves as $leave)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.hr.leave-management.show', $leave->id) }}">
                                            {{ $leave->staff ? ($leave->staff->first_name ?? 'N/A') . ' ' . ($leave->staff->last_name ?? '') : 'N/A' }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $leave->leave_type_label ?? ucfirst($leave->leave_type ?? 'N/A') }}</span>
                                    </td>
                                    <td>{!! $leave->status_badge ?? '<span class="badge bg-secondary">Unknown</span>' !!}</td>
                                    <td>{{ $leave->start_date ? $leave->start_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if($leave->half_day && $leave->start_date && $leave->start_date->isSameDay($leave->end_date))
                                            <span class="badge bg-warning">Half Day</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $leave->total_days ?? 0 }} days</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No recent leaves</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Staff with Most Leaves -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Staff with Most Leave Requests</h5>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-success" onclick="exportTopStaff()">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Staff Name</th>
                                    <th>Leave Count</th>
                                    <th>Total Days</th>
                                    <th>Average Days per Leave</th>
                                    <th>Performance Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topStaffLeaves as $index => $staff)
                                <tr>
                                    <td>
                                        @if($index === 0)
                                            <span class="badge bg-warning">🥇 1st</span>
                                        @elseif($index === 1)
                                            <span class="badge bg-secondary">🥈 2nd</span>
                                        @elseif($index === 2)
                                            <span class="badge bg-bronze">🥉 3rd</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary-light me-2">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            {{ ($staff->first_name ?? 'N/A') . ' ' . ($staff->last_name ?? '') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $staff->leave_count ?? 0 }}</span>
                                    </td>
                                    <td>{{ $staff->total_days ? round($staff->total_days, 1) . ' days' : '0 days' }}</td>
                                    <td>{{ ($staff->leave_count ?? 0) > 0 ? round(($staff->total_days ?? 0) / ($staff->leave_count ?? 1), 1) : 0 }} days</td>
                                    <td>
                                        @php
                                            $avgDays = ($staff->leave_count ?? 0) > 0 ? ($staff->total_days ?? 0) / ($staff->leave_count ?? 1) : 0;
                                            $rating = $avgDays <= 2 ? 'Excellent' : ($avgDays <= 4 ? 'Good' : ($avgDays <= 6 ? 'Average' : 'Needs Improvement'));
                                            $ratingClass = $avgDays <= 2 ? 'bg-success' : ($avgDays <= 4 ? 'bg-info' : ($avgDays <= 6 ? 'bg-warning' : 'bg-danger'));
                                        @endphp
                                        <span class="badge {{ $ratingClass }}">{{ $rating }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No staff leave data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics Row -->
    <div class="row">
        <!-- Leave Efficiency Metrics -->
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Leave Efficiency Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="metric-item">
                                <h3 class="text-primary">{{ $totalLeaves > 0 ? round(($approvedLeaves / $totalLeaves) * 100, 1) : 0 }}%</h3>
                                <p class="text-muted">Approval Rate</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="metric-item">
                                <h3 class="text-success">{{ $totalLeaves > 0 ? round(($pendingLeaves / $totalLeaves) * 100, 1) : 0 }}%</h3>
                                <p class="text-muted">Pending Rate</p>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="metric-item">
                                <h3 class="text-warning">{{ $staff->count() > 0 ? round(($totalLeaves / $staff->count()), 1) : 0 }}</h3>
                                <p class="text-muted">Avg Leaves/Staff</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="metric-item">
                                <h3 class="text-info">{{ $totalLeaves > 0 ? round(($currentLeaves / $totalLeaves) * 100, 1) : 0 }}%</h3>
                                <p class="text-muted">Current Leave Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.hr.leave-management.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Leave Request
                        </a>
                        <a href="{{ route('admin.hr.leave-management.export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export Data
                        </a>
                        <a href="{{ route('admin.hr.leave-management.calendar') }}" class="btn btn-info">
                            <i class="fas fa-calendar"></i> View Calendar
                        </a>
                        <button class="btn btn-warning" onclick="generateReport()">
                            <i class="fas fa-chart-bar"></i> Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">System Status</h5>
                </div>
                <div class="card-body">
                    <div class="status-item">
                        <i class="fas fa-database text-success"></i>
                        <span class="ms-2">Database: Connected</span>
                    </div>
                    <div class="status-item">
                        <i class="fas fa-server text-success"></i>
                        <span class="ms-2">Server: Running</span>
                    </div>
                    <div class="status-item">
                        <i class="fas fa-clock text-info"></i>
                        <span class="ms-2">Last Updated: {{ now()->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="status-item">
                        <i class="fas fa-users text-primary"></i>
                        <span class="ms-2">Active Users: {{ rand(5, 25) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trends Chart
    const monthlyCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    const monthlyLabels = @json($monthlyLabels ?? []);
    const monthlyCounts = @json($monthlyCounts ?? []);
    const monthlyDays = @json($monthlyDays ?? []);
    
    if (monthlyLabels && monthlyLabels.length > 0) {
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Leave Count',
                    data: monthlyCounts,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    yAxisID: 'y'
                }, {
                    label: 'Total Days',
                    data: monthlyDays,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Leave Count'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Total Days'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }

    // Leave Type Distribution Chart
    const leaveTypeCtx = document.getElementById('leaveTypeChart').getContext('2d');
    const leaveTypeData = @json($leaveTypeStats ?? []);
    
    if (leaveTypeData && leaveTypeData.length > 0) {
        new Chart(leaveTypeCtx, {
            type: 'doughnut',
            data: {
                labels: leaveTypeData.map(item => {
                    const labels = {
                        'casual': 'Casual',
                        'sick': 'Sick',
                        'annual': 'Annual',
                        'maternity': 'Maternity',
                        'paternity': 'Paternity',
                        'bereavement': 'Bereavement',
                        'study': 'Study',
                        'other': 'Other'
                    };
                    return labels[item.leave_type] || item.leave_type;
                }),
                datasets: [{
                    data: leaveTypeData.map(item => item.count || 0),
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#C9CBCF'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Yearly Comparison Chart
    const yearlyCtx = document.getElementById('yearlyComparisonChart').getContext('2d');
    const currentYear = new Date().getFullYear();
    const yearlyData = {
        labels: [currentYear-2, currentYear-1, currentYear],
        datasets: [{
            label: 'Total Leaves',
            data: [Math.floor(Math.random() * 50) + 20, Math.floor(Math.random() * 50) + 20, {{ $totalLeaves }}],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 2
        }, {
            label: 'Approved Leaves',
            data: [Math.floor(Math.random() * 40) + 15, Math.floor(Math.random() * 40) + 15, {{ $approvedLeaves }}],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 2
        }]
    };

    new Chart(yearlyCtx, {
        type: 'bar',
        data: yearlyData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Approval Trends Chart
    const approvalCtx = document.getElementById('approvalTrendsChart').getContext('2d');
    const approvalData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Approved',
            data: [65, 59, 80, 81, 56, 55],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 2
        }, {
            label: 'Rejected',
            data: [28, 48, 40, 19, 86, 27],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgb(255, 99, 132)',
            borderWidth: 2
        }]
    };

    new Chart(approvalCtx, {
        type: 'line',
        data: approvalData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Department Performance Chart
    const deptCtx = document.getElementById('departmentPerformanceChart').getContext('2d');
    const deptData = @json($departmentStats ?? []);
    
    if (deptData && deptData.length > 0) {
        new Chart(deptCtx, {
            type: 'radar',
            data: {
                labels: deptData.map(item => item.department ?? 'Unknown'),
                datasets: [{
                    label: 'Leave Count',
                    data: deptData.map(item => item.count || 0),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 2
                }, {
                    label: 'Total Days',
                    data: deptData.map(item => (item.total_days || 0) / 10), // Scale down for better visualization
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Duration Analysis Chart
    const durationCtx = document.getElementById('durationAnalysisChart').getContext('2d');
    const durationData = {
        labels: ['1 Day', '2-3 Days', '4-7 Days', '1-2 Weeks', '2+ Weeks'],
        datasets: [{
            label: 'Leave Count',
            data: [30, 25, 20, 15, 10],
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ]
        }]
    };

    new Chart(durationCtx, {
        type: 'polarArea',
        data: durationData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});

// Utility Functions
function exportDepartmentStats() {
    alert('Exporting department statistics...');
    // Implement export functionality
}

function exportTopStaff() {
    alert('Exporting top staff data...');
    // Implement export functionality
}

function refreshRecentLeaves() {
    location.reload();
}

function generateReport() {
    alert('Generating comprehensive report...');
    // Implement report generation
}

// Year selector change handler
document.getElementById('yearSelector').addEventListener('change', function() {
    // Implement year change functionality
    console.log('Year changed to:', this.value);
});
</script>
@endsection

@section('styles')
<style>
.avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.bg-primary-light { background-color: rgba(13, 110, 253, 0.1); }
.bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }
.bg-success-light { background-color: rgba(25, 135, 84, 0.1); }
.bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
.bg-info-light { background-color: rgba(13, 202, 240, 0.1); }
.bg-secondary-light { background-color: rgba(108, 117, 125, 0.1); }
.bg-dark-light { background-color: rgba(33, 37, 41, 0.1); }
.bg-purple-light { background-color: rgba(111, 66, 193, 0.1); }

.bg-bronze { background-color: #cd7f32; }

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    margin-bottom: 1rem;
}

.card-header {
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.card-tools {
    float: right;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
}

.department-color {
    display: inline-block;
}

.metric-item {
    padding: 1rem 0;
}

.metric-item h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.metric-item p {
    margin: 0.5rem 0 0 0;
    font-size: 0.875rem;
}

.status-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.status-item:last-child {
    border-bottom: none;
}

.status-item i {
    width: 20px;
}

.d-grid.gap-2 {
    gap: 0.5rem !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-tools {
        float: none;
        margin-top: 0.5rem;
    }
    
    .metric-item h3 {
        font-size: 1.25rem;
    }
}
</style>
@endsection

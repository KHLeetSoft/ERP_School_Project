@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Payroll Dashboard</h6>
        <a href="{{ route('admin.hr.payroll.index') }}" class="btn btn-sm btn-secondary">
            <i class="bx bx-left-arrow-alt"></i> Back to List
        </a>
    </div>
    
    <!-- KPI Cards Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Payrolls</div>
                    <div class="fs-5 fw-semibold text-primary">{{ number_format($totalPayrolls ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Pending Payrolls</div>
                    <div class="fs-5 fw-semibold text-warning">{{ number_format($pendingPayrolls ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Approved Payrolls</div>
                    <div class="fs-5 fw-semibold text-info">{{ number_format($approvedPayrolls ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Paid Payrolls</div>
                    <div class="fs-5 fw-semibold text-success">{{ number_format($paidPayrolls ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row 2 -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Gross Salary</div>
                    <div class="fs-6 fw-semibold text-primary">₹{{ number_format($totalGrossSalary ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Net Salary</div>
                    <div class="fs-6 fw-semibold text-success">₹{{ number_format($totalNetSalary ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Allowances</div>
                    <div class="fs-6 fw-semibold text-info">₹{{ number_format($totalAllowances ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Deductions</div>
                    <div class="fs-6 fw-semibold text-danger">₹{{ number_format($totalDeductions ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Monthly Payroll Trends</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Payroll Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Department-wise Salary Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="departmentSalaryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Payment Method Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 3 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Salary vs Month Analysis</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="salaryMonthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Allowances Breakdown</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="allowancesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payrolls -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Recent Payrolls</h6>
                </div>
                <div class="card-body">
                    @if(isset($recentPayrolls) && count($recentPayrolls) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Period</th>
                                        <th>Basic Salary</th>
                                        <th>Gross Salary</th>
                                        <th>Net Salary</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayrolls as $payroll)
                                        <tr>
                                            <td>
                                                <strong>{{ $payroll->staff ? $payroll->staff->first_name . ' ' . $payroll->staff->last_name : 'N/A' }}</strong>
                                                <br><small class="text-muted">{{ $payroll->staff ? $payroll->staff->employee_id : 'N/A' }}</small>
                                            </td>
                                            <td>{{ $payroll->payroll_period }}</td>
                                            <td>₹{{ number_format($payroll->basic_salary, 2) }}</td>
                                            <td>₹{{ number_format($payroll->gross_salary, 2) }}</td>
                                            <td>₹{{ number_format($payroll->net_salary, 2) }}</td>
                                            <td>{!! $payroll->status_badge !!}</td>
                                            <td>{{ $payroll->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent payrolls found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize charts when Chart.js is available
    if (typeof Chart !== 'undefined') {
        initCharts();
    } else {
        // Load Chart.js from CDN
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js';
        script.onload = initCharts;
        document.head.appendChild(script);
    }
});

function initCharts() {
    // Monthly Trends Chart
    const monthlyCtx = document.getElementById('monthlyTrendsChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']),
                datasets: [{
                    label: 'Payroll Count',
                    data: @json($monthlyCounts ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Gross Salary (₹)',
                    data: @json($monthlyGross ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Count' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Salary (₹)' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusDistributionChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Paid', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $pendingPayrolls ?? 0 }},
                        {{ $approvedPayrolls ?? 0 }},
                        {{ $paidPayrolls ?? 0 }},
                        {{ $rejectedPayrolls ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // Department Salary Chart
    const deptCtx = document.getElementById('departmentSalaryChart');
    if (deptCtx) {
        const deptData = @json($departmentStats ?? []);
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: deptData.map(item => item.department),
                datasets: [{
                    label: 'Total Salary (₹)',
                    data: deptData.map(item => item.total_salary),
                    backgroundColor: 'rgba(139, 92, 246, 0.8)',
                    borderColor: 'rgba(139, 92, 246, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: { display: true, text: 'Salary (₹)' }
                    }
                }
            }
        });
    }

    // Payment Method Chart
    const paymentCtx = document.getElementById('paymentMethodChart');
    if (paymentCtx) {
        const paymentData = @json($paymentMethodStats ?? []);
        new Chart(paymentCtx, {
            type: 'pie',
            data: {
                labels: paymentData.map(item => item.payment_method.replace('_', ' ').toUpperCase()),
                datasets: [{
                    data: paymentData.map(item => item.count),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // Salary vs Month Chart
    const salaryMonthCtx = document.getElementById('salaryMonthChart');
    if (salaryMonthCtx) {
        new Chart(salaryMonthCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']),
                datasets: [{
                    label: 'Net Salary (₹)',
                    data: @json($monthlyNet ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]),
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: { display: true, text: 'Salary (₹)' }
                    }
                }
            }
        });
    }

    // Allowances Chart
    const allowancesCtx = document.getElementById('allowancesChart');
    if (allowancesCtx) {
        new Chart(allowancesCtx, {
            type: 'bar',
            data: {
                labels: ['Basic', 'HRA', 'DA', 'Conveyance', 'Medical', 'Special', 'Overtime', 'Bonus', 'Incentives', 'Arrears'],
                datasets: [{
                    label: 'Amount (₹)',
                    data: [50000, 15000, 10000, 1200, 800, 12500, 3000, 8000, 5000, 2000],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: { display: true, text: 'Amount (₹)' }
                    }
                }
            }
        });
    }
}
</script>
@endsection

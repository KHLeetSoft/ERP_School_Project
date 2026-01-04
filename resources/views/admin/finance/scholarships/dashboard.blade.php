@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Scholarships Dashboard</h6>
        <a href="{{ route('admin.finance.scholarships.index') }}" class="btn btn-sm btn-secondary"><i class="bx bx-left-arrow-alt"></i> Back</a>
    </div>
    
    <!-- KPI Cards Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Scholarships</div>
                    <div class="fs-5 fw-semibold text-primary">{{ number_format($totalScholarships ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Amount</div>
                    <div class="fs-5 fw-semibold text-success">₹{{ number_format($totalAmount ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Avg Amount</div>
                    <div class="fs-5 fw-semibold text-info">₹{{ number_format($avgAmount ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Approval Rate</div>
                    <div class="fs-5 fw-semibold text-warning">{{ number_format($approvalRate ?? 0, 1) }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Payment Rate</div>
                    <div class="fs-5 fw-semibold text-success">{{ number_format($paymentRate ?? 0, 1) }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Pending Amount</div>
                    <div class="fs-6 fw-semibold text-secondary">₹{{ number_format(is_array($pending) ? array_sum($pending) : 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Distribution Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Pending</div>
                    <div class="fs-5 fw-semibold text-secondary">{{ $statusCounts['pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Approved</div>
                    <div class="fs-5 fw-semibold text-primary">{{ $statusCounts['approved'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Rejected</div>
                    <div class="fs-5 fw-semibold text-danger">{{ $statusCounts['rejected'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Paid</div>
                    <div class="fs-5 fw-semibold text-success">{{ $statusCounts['paid'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Monthly Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="monthlyStatus"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="statusDistribution"></canvas>
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
                    <h6 class="mb-0">Amount Range Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="amountRanges"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Top Categories by Amount</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="topCategories"></canvas>
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
                    <h6 class="mb-0">Monthly Trends</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="monthlyTrends"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Cumulative Approved vs Paid</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="cumulativeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 4 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Monthly Count vs Amount</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="countVsAmount"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Performance Metrics</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="performanceMetrics"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 5 - Additional Charts -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Monthly Trend Analysis</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="trendAnalysis"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Status Flow Analysis</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="statusFlow"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 6 - More Advanced Charts -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Amount Distribution Bubble</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="bubbleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Monthly Comparison</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="monthlyComparison"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 7 - Statistical Charts -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Approval Rate Trend</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="approvalRateTrend"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Amount Efficiency</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="amountEfficiency"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 8 - Final Charts -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Seasonal Analysis</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="seasonalAnalysis"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Growth Metrics</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="growthMetrics"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Statistics Section -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Advanced Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="text-muted small">Highest Scholarship</div>
                                <div class="fs-6 fw-semibold text-success">₹{{ number_format(is_array($approved) && !empty($approved) ? max($approved) : 0) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="text-muted small">Lowest Scholarship</div>
                                <div class="fs-6 fw-semibold text-info">₹{{ number_format(is_array($approved) && !empty($approved) ? min(array_filter($approved)) : 0) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="text-muted small">Processing Time</div>
                                <div class="fs-6 fw-semibold text-warning">{{ is_array($pending) && is_array($approved) ? round(array_sum($pending) / max(array_sum($approved), 1), 1) : 0 }} days</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="text-muted small">Success Rate</div>
                                <div class="fs-6 fw-semibold text-primary">{{ round((($statusCounts['approved'] ?? 0) + ($statusCounts['paid'] ?? 0)) / max(($statusCounts['pending'] ?? 0) + ($statusCounts['rejected'] ?? 0), 1) * 100, 1) }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
    function init(){
        // Data from controller
        const labels = @json($labels ?? []);
        const approved = @json($approved ?? []);
        const paid = @json($paid ?? []);
        const pending = @json($pending ?? []);
        const rejected = @json($rejected ?? []);
        const statusCounts = @json($statusCounts ?? []);
        const amountRanges = @json($amountRanges ?? []);
        const topCategories = @json($topCategories ?? []);
        const monthlyTotal = @json($monthlyTotal ?? []);
        const monthlyCount = @json($monthlyCount ?? []);
        const cumulativeApproved = @json($cumulativeApproved ?? []);
        const cumulativePaid = @json($cumulativePaid ?? []);
        
        // Chart 1: Monthly Status Distribution (Stacked Bar)
        const monthlyStatusCtx = document.getElementById('monthlyStatus');
        if (monthlyStatusCtx) {
            new Chart(monthlyStatusCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pending',
                            data: pending,
                            backgroundColor: 'rgba(156, 163, 175, 0.8)',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Approved',
                            data: approved,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Rejected',
                            data: rejected,
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Paid',
                            data: paid,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            stack: 'Stack 0'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Monthly Scholarship Status Distribution' }
                    },
                    scales: {
                        x: { stacked: true },
                        y: { 
                            stacked: true,
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 2: Status Distribution (Doughnut)
        const statusDistributionCtx = document.getElementById('statusDistribution');
        if (statusDistributionCtx) {
            new Chart(statusDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected', 'Paid'],
                    datasets: [{
                        data: [
                            statusCounts.pending || 0,
                            statusCounts.approved || 0,
                            statusCounts.rejected || 0,
                            statusCounts.paid || 0
                        ],
                        backgroundColor: [
                            '#9ca3af', '#3b82f6', '#ef4444', '#10b981'
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
        
        // Chart 3: Amount Ranges (Polar Area)
        const amountRangesCtx = document.getElementById('amountRanges');
        if (amountRangesCtx) {
            new Chart(amountRangesCtx, {
                type: 'polarArea',
                data: {
                    labels: Object.keys(amountRanges),
                    datasets: [{
                        data: Object.values(amountRanges),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
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
        
        // Chart 4: Top Categories (Horizontal Bar)
        const topCategoriesCtx = document.getElementById('topCategories');
        if (topCategoriesCtx) {
            new Chart(topCategoriesCtx, {
                type: 'bar',
                data: {
                    labels: topCategories.map(cat => cat.category),
                    datasets: [{
                        label: 'Total Amount (₹)',
                        data: topCategories.map(cat => cat.total_amount),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 5: Monthly Trends (Line)
        const monthlyTrendsCtx = document.getElementById('monthlyTrends');
        if (monthlyTrendsCtx) {
            new Chart(monthlyTrendsCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Amount',
                            data: monthlyTotal,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Count',
                            data: monthlyCount,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        }
        
        // Chart 6: Cumulative Chart (Line)
        const cumulativeCtx = document.getElementById('cumulativeChart');
        if (cumulativeCtx) {
            new Chart(cumulativeCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Cumulative Approved',
                            data: cumulativeApproved,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true
                        },
                        {
                            label: 'Cumulative Paid',
                            data: cumulativePaid,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 7: Count vs Amount (Scatter)
        const countVsAmountCtx = document.getElementById('countVsAmount');
        if (countVsAmountCtx) {
            new Chart(countVsAmountCtx, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Monthly Data',
                        data: labels.map((label, index) => ({
                            x: monthlyCount[index] || 0,
                            y: monthlyTotal[index] || 0
                        })),
                        backgroundColor: 'rgba(59, 130, 246, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Number of Scholarships' },
                            beginAtZero: true
                        },
                        y: {
                            title: { display: true, text: 'Total Amount (₹)' },
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 8: Performance Metrics (Radar)
        const performanceMetricsCtx = document.getElementById('performanceMetrics');
        if (performanceMetricsCtx) {
            new Chart(performanceMetricsCtx, {
                type: 'radar',
                data: {
                    labels: ['Approval Rate', 'Payment Rate', 'Total Scholarships', 'Avg Amount', 'Total Amount'],
                    datasets: [{
                        label: 'Performance Metrics',
                        data: [
                            {{ $approvalRate ?? 0 }},
                            {{ $paymentRate ?? 0 }},
                            {{ $totalScholarships ?? 0 }},
                            {{ ($avgAmount ?? 0) / 1000 }},
                            {{ ($totalAmount ?? 0) / 10000 }}
                        ],
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgb(59, 130, 246)',
                        pointBackgroundColor: 'rgb(59, 130, 246)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        r: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Chart 9: Trend Analysis (Area Chart)
        const trendAnalysisCtx = document.getElementById('trendAnalysis');
        if (trendAnalysisCtx) {
            new Chart(trendAnalysisCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Approved Trend',
                            data: approved,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.3)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Paid Trend',
                            data: paid,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.3)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 10: Status Flow Analysis (Sankey-like visualization using stacked area)
        const statusFlowCtx = document.getElementById('statusFlow');
        if (statusFlowCtx) {
            new Chart(statusFlowCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pending → Approved',
                            data: pending.map((p, i) => p * 0.7), // Simulate flow
                            borderColor: 'rgb(156, 163, 175)',
                            backgroundColor: 'rgba(156, 163, 175, 0.2)',
                            fill: true
                        },
                        {
                            label: 'Approved → Paid',
                            data: approved.map((a, i) => a * 0.8), // Simulate flow
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 11: Bubble Chart for Amount Distribution
        const bubbleChartCtx = document.getElementById('bubbleChart');
        if (bubbleChartCtx) {
            const bubbleData = labels.map((label, index) => ({
                x: monthlyCount[index] || 0,
                y: monthlyTotal[index] || 0,
                r: Math.sqrt((approved[index] || 0) / 1000) // Bubble size based on approved amount
            }));
            
            new Chart(bubbleChartCtx, {
                type: 'bubble',
                data: {
                    datasets: [{
                        label: 'Scholarship Distribution',
                        data: bubbleData,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(59, 130, 246, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Number of Scholarships' },
                            beginAtZero: true
                        },
                        y: {
                            title: { display: true, text: 'Total Amount (₹)' },
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 12: Monthly Comparison (Mixed Chart)
        const monthlyComparisonCtx = document.getElementById('monthlyComparison');
        if (monthlyComparisonCtx) {
            new Chart(monthlyComparisonCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Amount',
                            data: monthlyTotal,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Count Trend',
                            data: monthlyCount,
                            type: 'line',
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        }
        
        // Chart 13: Approval Rate Trend
        const approvalRateTrendCtx = document.getElementById('approvalRateTrend');
        if (approvalRateTrendCtx) {
            const approvalRates = labels.map((label, index) => {
                const total = (approved[index] || 0) + (rejected[index] || 0);
                return total > 0 ? ((approved[index] || 0) / total) * 100 : 0;
            });
            
            new Chart(approvalRateTrendCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Approval Rate %',
                        data: approvalRates,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 14: Amount Efficiency (Horizontal Bar)
        const amountEfficiencyCtx = document.getElementById('amountEfficiency');
        if (amountEfficiencyCtx) {
            const efficiencyData = labels.map((label, index) => {
                const total = monthlyTotal[index] || 0;
                const count = monthlyCount[index] || 1;
                return total / count; // Average amount per scholarship
            });
            
            new Chart(amountEfficiencyCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average Amount per Scholarship',
                        data: efficiencyData,
                        backgroundColor: 'rgba(245, 158, 11, 0.8)'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 15: Seasonal Analysis (Polar Area)
        const seasonalAnalysisCtx = document.getElementById('seasonalAnalysis');
        if (seasonalAnalysisCtx) {
            // Group by quarters
            const quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
            const quarterlyData = [0, 0, 0, 0];
            
            labels.forEach((label, index) => {
                const month = new Date(label).getMonth();
                const quarter = Math.floor(month / 3);
                quarterlyData[quarter] += monthlyTotal[index] || 0;
            });
            
            new Chart(seasonalAnalysisCtx, {
                type: 'polarArea',
                data: {
                    labels: quarters,
                    datasets: [{
                        data: quarterlyData,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
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
        
        // Chart 16: Growth Metrics (Line with moving average)
        const growthMetricsCtx = document.getElementById('growthMetrics');
        if (growthMetricsCtx) {
            // Calculate month-over-month growth
            const growthRates = [];
            for (let i = 1; i < monthlyTotal.length; i++) {
                const current = monthlyTotal[i] || 0;
                const previous = monthlyTotal[i-1] || 1;
                const growth = ((current - previous) / previous) * 100;
                growthRates.push(growth);
            }
            
            // Moving average
            const movingAverage = [];
            for (let i = 2; i < growthRates.length; i++) {
                const avg = (growthRates[i] + growthRates[i-1] + growthRates[i-2]) / 3;
                movingAverage.push(avg);
            }
            
            new Chart(growthMetricsCtx, {
                type: 'line',
                data: {
                    labels: labels.slice(1),
                    datasets: [
                        {
                            label: 'Month-over-Month Growth %',
                            data: growthRates,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: false
                        },
                        {
                            label: '3-Month Moving Average %',
                            data: movingAverage,
                            borderColor: 'rgb(139, 92, 246)',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            fill: false,
                            borderDash: [5, 5]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(1) + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    } // Close init function
    
    if (window.Chart) {
        init();
    } else {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js';
        script.onload = init;
        document.head.appendChild(script);
    }
})(); // Close IIFE
</script>
@endsection



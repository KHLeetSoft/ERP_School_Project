@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Fee Structure Dashboard</h6>
        <a href="{{ route('admin.finance.fee-structure.index') }}" class="btn btn-sm btn-secondary">
            <i class="bx bx-left-arrow-alt"></i> Back to List
        </a>
    </div>
    
    <!-- KPI Cards Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Fee Structures</div>
                    <div class="fs-5 fw-semibold text-primary">{{ number_format($totalFeeStructures ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Active Structures</div>
                    <div class="fs-5 fw-semibold text-success">{{ number_format($activeFeeStructures ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Amount</div>
                    <div class="fs-5 fw-semibold text-info">₹{{ number_format($totalAmount ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Average Amount</div>
                    <div class="fs-6 fw-semibold text-warning">₹{{ number_format($avgAmount ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- KPI Cards Row 2 -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">With Discount</div>
                    <div class="fs-6 fw-semibold text-success">{{ number_format($discountStats['with_discount'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Discount</div>
                    <div class="fs-6 fw-semibold text-info">₹{{ number_format($discountStats['total_discount'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">With Late Fee</div>
                    <div class="fs-6 fw-semibold text-warning">{{ number_format($lateFeeStats['with_late_fee'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Late Fee</div>
                    <div class="fs-6 fw-semibold text-danger">₹{{ number_format($lateFeeStats['total_late_fee'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Fee Types Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="feeTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Frequency Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="frequencyChart"></canvas>
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
                    <h6 class="mb-0">Class-wise Fee Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="classDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Monthly Trend ({{ $currentYear ?? 'Current Year' }})</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="monthlyTrendChart"></canvas>
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
                    <h6 class="mb-0">Discount vs No Discount</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="discountChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Late Fee Analysis</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="lateFeeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 4 - Additional Analytics -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Fee Amount Range Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="amountRangeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Academic Year Comparison</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="academicYearChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 5 - Advanced Analytics -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Fee Structure Growth Trend</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="growthTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Discount Percentage Analysis</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="discountPercentageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 6 - Performance Metrics -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Class Performance Ranking</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="classRankingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Fee Type Efficiency</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="efficiencyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional KPI Cards Row 3 -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Highest Fee Amount</div>
                    <div class="fs-6 fw-semibold text-danger">₹{{ number_format($highestAmount ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Lowest Fee Amount</div>
                    <div class="fs-6 fw-semibold text-success">₹{{ number_format($lowestAmount ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Most Popular Fee Type</div>
                    <div class="fs-6 fw-semibold text-primary">{{ $mostPopularFeeType ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Classes</div>
                    <div class="fs-6 fw-semibold text-info">{{ $totalClasses ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Summary Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Fee Structure Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-muted small">Monthly</div>
                            <div class="fs-6 fw-semibold text-primary">{{ number_format($monthlyCount ?? 0) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Quarterly</div>
                            <div class="fs-6 fw-semibold text-success">{{ number_format($quarterlyCount ?? 0) }}</div>
                        </div>
                    </div>
                    <div class="row text-center mt-2">
                        <div class="col-6">
                            <div class="text-muted small">Half Yearly</div>
                            <div class="fs-6 fw-semibold text-warning">{{ number_format($halfYearlyCount ?? 0) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Yearly</div>
                            <div class="fs-6 fw-semibold text-info">{{ number_format($yearlyCount ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Financial Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-muted small">Total Revenue</div>
                            <div class="fs-6 fw-semibold text-success">₹{{ number_format($totalRevenue ?? 0) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Avg per Class</div>
                            <div class="fs-6 fw-semibold text-info">₹{{ number_format($avgPerClass ?? 0) }}</div>
                        </div>
                    </div>
                    <div class="row text-center mt-2">
                        <div class="col-6">
                            <div class="text-muted small">Max Discount</div>
                            <div class="fs-6 fw-semibold text-warning">₹{{ number_format($maxDiscount ?? 0) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Total Late Fees</div>
                            <div class="fs-6 fw-semibold text-danger">₹{{ number_format($totalLateFees ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Performance Metrics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-muted small">Active Rate</div>
                            <div class="fs-6 fw-semibold text-success">{{ number_format($activeRate ?? 0, 1) }}%</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Discount Rate</div>
                            <div class="fs-6 fw-semibold text-info">{{ number_format($discountRate ?? 0, 1) }}%</div>
                        </div>
                    </div>
                    <div class="row text-center mt-2">
                        <div class="col-6">
                            <div class="text-muted small">Late Fee Rate</div>
                            <div class="fs-6 fw-semibold text-warning">{{ number_format($lateFeeRate ?? 0, 1) }}%</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Growth Rate</div>
                            <div class="fs-6 fw-semibold text-primary">{{ number_format($growthRate ?? 0, 1) }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Academic Year Filter -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Academic Year Analysis</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Select Academic Year</label>
                            <select class="form-select" id="academicYearFilter">
                                @foreach($academicYears ?? [] as $year)
                                    <option value="{{ $year }}" {{ $year == ($currentYear ?? '') ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex align-items-end h-100">
                                <button type="button" class="btn btn-primary" onclick="updateYearAnalysis()">
                                    <i class="bx bx-refresh"></i> Update Analysis
                                </button>
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
        // Data from controller - temporarily using empty data to isolate syntax error
        const feeTypes = [];
        const frequencies = [];
        const classDistribution = [];
        const monthlyTrend = [];
        const discountStats = {};
        const lateFeeStats = {};
        const amountRanges = {};
        const academicYearComparison = {};
        const classPerformance = [];
        const feeTypeEfficiency = [];
        
        // Chart 1: Fee Types Distribution (Horizontal Bar)
        const feeTypesCtx = document.getElementById('feeTypesChart');
        if (feeTypesCtx) {
            new Chart(feeTypesCtx, {
                type: 'bar',
                data: {
                    labels: feeTypes.length > 0 ? feeTypes.map(item => item.fee_type) : ['No Data'],
                    datasets: [{
                        label: 'Total Amount (₹)',
                        data: feeTypes.length > 0 ? feeTypes.map(item => item.total_amount) : [0],
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
        
        // Chart 2: Frequency Distribution (Doughnut)
        const frequencyCtx = document.getElementById('frequencyChart');
        if (frequencyCtx) {
            new Chart(frequencyCtx, {
                type: 'doughnut',
                data: {
                    labels: frequencies.length > 0 ? frequencies.map(item => item.frequency) : ['No Data'],
                    datasets: [{
                        data: frequencies.length > 0 ? frequencies.map(item => item.count) : [1],
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
        
        // Chart 3: Class Distribution (Bar)
        const classDistributionCtx = document.getElementById('classDistributionChart');
        if (classDistributionCtx) {
            new Chart(classDistributionCtx, {
                type: 'bar',
                data: {
                    labels: classDistribution.length > 0 ? classDistribution.map(item => item.class?.name || 'Unknown') : ['No Data'],
                    datasets: [{
                        label: 'Total Amount (₹)',
                        data: classDistribution.length > 0 ? classDistribution.map(item => item.total_amount) : [0],
                        backgroundColor: 'rgba(16, 185, 129, 0.8)'
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
        
        // Chart 4: Monthly Trend (Line)
        const monthlyTrendCtx = document.getElementById('monthlyTrendChart');
        if (monthlyTrendCtx) {
            new Chart(monthlyTrendCtx, {
                type: 'line',
                data: {
                    labels: monthlyTrend.length > 0 ? monthlyTrend.map(item => item.month) : ['No Data'],
                    datasets: [
                        {
                            label: 'Count',
                            data: monthlyTrend.length > 0 ? monthlyTrend.map(item => item.count) : [0],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Amount (₹)',
                            data: monthlyTrend.length > 0 ? monthlyTrend.map(item => item.amount) : [0],
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
                            position: 'left'
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
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
        
        // Chart 5: Discount Analysis (Pie)
        const discountCtx = document.getElementById('discountChart');
        if (discountCtx) {
            new Chart(discountCtx, {
                type: 'pie',
                data: {
                    labels: ['With Discount', 'Without Discount'],
                    datasets: [{
                        data: [
                            discountStats.with_discount || 0,
                            discountStats.without_discount || 0
                        ],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(156, 163, 175, 0.8)'
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
        
        // Chart 6: Late Fee Analysis (Bar)
        const lateFeeCtx = document.getElementById('lateFeeChart');
        if (lateFeeCtx) {
            new Chart(lateFeeCtx, {
                type: 'bar',
                data: {
                    labels: ['With Late Fee', 'Total Late Fee Amount'],
                    datasets: [{
                        label: 'Count/Amount',
                        data: [
                            lateFeeStats.with_late_fee || 0,
                            lateFeeStats.total_late_fee || 0
                        ],
                        backgroundColor: [
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
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Chart 7: Fee Amount Range Distribution (Histogram)
        const amountRangeCtx = document.getElementById('amountRangeChart');
        if (amountRangeCtx) {
            const ranges = Object.keys(amountRanges).length > 0 ? Object.keys(amountRanges) : ['No Data'];
            const rangeData = Object.keys(amountRanges).length > 0 ? Object.values(amountRanges) : [0];
            
            new Chart(amountRangeCtx, {
                type: 'bar',
                data: {
                    labels: ranges,
                    datasets: [{
                        label: 'Number of Fee Structures',
                        data: rangeData,
                        backgroundColor: 'rgba(139, 92, 246, 0.8)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 1
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
                            title: { display: true, text: 'Count' }
                        },
                        x: {
                            title: { display: true, text: 'Amount Range (₹)' }
                        }
                    }
                }
            });
        }
        
        // Chart 8: Academic Year Comparison (Stacked Bar)
        const academicYearCtx = document.getElementById('academicYearChart');
        if (academicYearCtx) {
            const years = Object.keys(academicYearComparison).length > 0 ? Object.keys(academicYearComparison) : ['No Data'];
            const activeData = Object.keys(academicYearComparison).length > 0 ? years.map(year => academicYearComparison[year].active) : [0];
            const inactiveData = Object.keys(academicYearComparison).length > 0 ? years.map(year => academicYearComparison[year].inactive) : [0];
            
            new Chart(academicYearCtx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: [
                        {
                            label: 'Active',
                            data: activeData,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Inactive',
                            data: inactiveData,
                            backgroundColor: 'rgba(156, 163, 175, 0.8)',
                            stack: 'Stack 0'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        x: { stacked: true },
                        y: { 
                            stacked: true,
                            beginAtZero: true,
                            title: { display: true, text: 'Count' }
                        }
                    }
                }
            });
        }
        
        // Chart 9: Fee Structure Growth Trend (Area Chart)
        const growthTrendCtx = document.getElementById('growthTrendChart');
        if (growthTrendCtx) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const growthData = [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65];
            
            new Chart(growthTrendCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Growth Rate (%)',
                        data: growthData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                            title: { display: true, text: 'Growth Rate (%)' }
                        }
                    }
                }
            });
        }
        
        // Chart 10: Discount Percentage Analysis (Radar Chart)
        const discountPercentageCtx = document.getElementById('discountPercentageChart');
        if (discountPercentageCtx) {
            const categories = ['Tuition Fee', 'Transport Fee', 'Library Fee', 'Laboratory Fee', 'Sports Fee', 'Computer Fee'];
            const discountPercentages = [10, 5, 0, 0, 0, 0];
            
            new Chart(discountPercentageCtx, {
                type: 'radar',
                data: {
                    labels: categories,
                    datasets: [{
                        label: 'Discount Percentage (%)',
                        data: discountPercentages,
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(16, 185, 129, 1)'
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
                            beginAtZero: true,
                            max: 15,
                            ticks: { stepSize: 5 }
                        }
                    }
                }
            });
        }
        
        // Chart 11: Class Performance Ranking (Horizontal Bar)
        const classRankingCtx = document.getElementById('classRankingChart');
        if (classRankingCtx) {
            const classes = classPerformance.length > 0 ? classPerformance.map(item => item.class?.name || 'Unknown') : ['No Data'];
            const performanceScores = classPerformance.length > 0 ? classPerformance.map(item => Math.round((item.avg_amount / 1000) * 10)) : [0]; // Convert to percentage-like score
            
            new Chart(classRankingCtx, {
                type: 'bar',
                data: {
                    labels: classes,
                    datasets: [{
                        label: 'Performance Score',
                        data: performanceScores,
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ]
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
                            title: { display: true, text: 'Performance Score' }
                        }
                    }
                }
            });
        }
        
        // Chart 12: Fee Type Efficiency (Doughnut with Custom Elements)
        const efficiencyCtx = document.getElementById('efficiencyChart');
        if (efficiencyCtx) {
            const feeTypes = feeTypeEfficiency.length > 0 ? feeTypeEfficiency.map(item => item.fee_type) : ['No Data'];
            const efficiencyData = feeTypeEfficiency.length > 0 ? feeTypeEfficiency.map(item => Math.round((item.avg_amount / 1000) * 10)) : [0]; // Convert to efficiency score
            
            new Chart(efficiencyCtx, {
                type: 'doughnut',
                data: {
                    labels: feeTypes,
                    datasets: [{
                        data: efficiencyData,
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }
    }
    
    if (window.Chart) {
        init();
    } else {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js';
        script.onload = init;
        document.head.appendChild(script);
    }
    
    // Function to update year analysis
    window.updateYearAnalysis = function() {
        const year = document.getElementById('academicYearFilter').value;
        // You can implement AJAX call here to update charts based on selected year
        alert('Year analysis update functionality can be implemented here for year: ' + year);
    };
})();
</script>
@endsection

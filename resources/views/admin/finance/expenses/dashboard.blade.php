@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Expenses Dashboard</h6>
        <a href="{{ route('admin.finance.expenses.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
    
    <!-- KPI Cards Row -->
    <div class="row g-3 mb-3">
        <div class="col-sm-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="text-white-50 small">Total This Month</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalThisMonth, 2) }}</div>
                    <div class="small">₹</div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <div class="text-white-50 small">Total Last Month</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalLastMonth, 2) }}</div>
                    <div class="small">₹</div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-sm border-0 {{ $growthPct > 0 ? 'bg-danger' : 'bg-info' }} text-white">
                <div class="card-body">
                    <div class="text-white-50 small">Growth vs Last Month</div>
                    <div class="fs-4 fw-bold">{{ is_null($growthPct) ? '-' : ($growthPct.'%') }}</div>
                    <div class="small">{{ $growthPct > 0 ? '↗ Increase' : '↘ Decrease' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-sm border-0 bg-warning text-white">
                <div class="card-body">
                    <div class="text-white-50 small">Total Categories</div>
                    <div class="fs-4 fw-bold">{{ count($categoryTotals) }}</div>
                    <div class="small">Active</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">📈 Monthly Expenses Trend (6 months)</h6>
                    <span class="badge bg-primary">Line Chart</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="expLine"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">🍕 Expenses by Category</h6>
                    <span class="badge bg-success">Pie Chart</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="catPie"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">📊 Top Vendors by Amount</h6>
                    <span class="badge bg-info">Bar Chart</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="vendorsBar"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">💳 Payment Methods Distribution</h6>
                    <span class="badge bg-warning">Doughnut Chart</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="methodPie"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 3 -->
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">📋 Expense Status Distribution</h6>
                    <span class="badge bg-danger">Doughnut Chart</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="statusPie"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">📅 Daily Expenses (Last 30 days)</h6>
                    <span class="badge bg-secondary">Line Chart</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="dailyExpenses"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 4 -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">🏗️ Category Monthly Breakdown (Stacked)</h6>
                    <span class="badge bg-dark">Stacked Bar Chart</span>
                </div>
                <div class="card-body" style="height: 350px;">
                    <canvas id="catStacked"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 5 -->
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">💰 Amount Range Distribution</h6>
                    <span class="badge bg-primary">Bar Chart</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="amountRangeChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">📊 Category vs Vendor Analysis</h6>
                    <span class="badge bg-success">Pie Chart</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="categoryVendorChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards Row -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bx bx-calendar-check fs-1 text-primary"></i>
                    <h6 class="mt-2">Total Expenses</h6>
                    <div class="fs-5 fw-bold text-primary">{{ number_format(array_sum($totals), 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bx bx-building fs-1 text-success"></i>
                    <h6 class="mt-2">Active Vendors</h6>
                    <div class="fs-5 fw-bold text-success">{{ count($vendorLabels) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bx bx-category fs-1 text-warning"></i>
                    <h6 class="mt-2">Categories</h6>
                    <div class="fs-5 fw-bold text-warning">{{ count($categoryTotals) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <i class="bx bx-trending-up fs-1 text-info"></i>
                    <h6 class="mt-2">Avg Monthly</h6>
                    <div class="fs-5 fw-bold text-info">{{ number_format(array_sum($totals) / count($totals), 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function(){
    const labels = {!! json_encode($labels) !!};
    const totals = {!! json_encode($totals) !!};
    const categoryTotals = {!! json_encode($categoryTotals) !!};
    const categoryMonthlySeries = {!! json_encode($categoryMonthlySeries) !!};
    const vendorLabels = {!! json_encode($vendorLabels) !!};
    const vendorValues = {!! json_encode($vendorValues) !!};
    const methodCounts = {!! json_encode($methodCounts) !!};
    const statusCounts = {!! json_encode($statusCounts) !!};

    const colors = ['#16a34a','#3b82f6','#f59e0b','#ef4444','#9333ea','#0ea5e9','#22c55e','#64748b','#f97316','#84cc16','#06b6d4','#8b5cf6'];
    const gradientColors = ['rgba(22, 163, 74, 0.8)','rgba(59, 130, 246, 0.8)','rgba(245, 158, 11, 0.8)','rgba(239, 68, 68, 0.8)','rgba(147, 51, 234, 0.8)','rgba(14, 165, 233, 0.8)'];

    // 1. Monthly Expenses Line Chart
    const line = document.getElementById('expLine');
    if (line) {
        new Chart(line, { 
            type: 'line', 
            data: { 
                labels, 
                datasets: [{ 
                    label: 'Monthly Expenses', 
                    data: totals, 
                    borderColor: '#ef4444', 
                    backgroundColor: 'rgba(239,68,68,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    },
                    x: {
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    }
                } 
            } 
        });
    }

    // 2. Category Pie Chart
    const catPie = document.getElementById('catPie');
    if (catPie) {
        const cLabels = Object.keys(categoryTotals);
        const cValues = Object.values(categoryTotals);
        new Chart(catPie, { 
            type: 'pie', 
            data: { 
                labels: cLabels, 
                datasets: [{ 
                    data: cValues, 
                    backgroundColor: colors.slice(0, cLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: ₹${context.parsed.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                } 
            } 
        });
    }

    // 3. Top Vendors Bar Chart
    const vend = document.getElementById('vendorsBar');
    if (vend) {
        new Chart(vend, { 
            type: 'bar', 
            data: { 
                labels: vendorLabels, 
                datasets: [{ 
                    label: 'Amount', 
                    data: vendorValues, 
                    backgroundColor: gradientColors.slice(0, vendorLabels.length),
                    borderColor: colors.slice(0, vendorLabels.length),
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                }, 
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                } 
            } 
        });
    }

    // 4. Payment Methods Doughnut Chart
    const mPie = document.getElementById('methodPie');
    if (mPie) {
        const mLabels = Object.keys(methodCounts);
        const mValues = Object.values(methodCounts);
        new Chart(mPie, { 
            type: 'doughnut', 
            data: { 
                labels: mLabels, 
                datasets: [{ 
                    data: mValues, 
                    backgroundColor: colors.slice(0, mLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            } 
        });
    }

    // 5. Status Doughnut Chart
    const sPie = document.getElementById('statusPie');
    if (sPie) {
        const sLabels = Object.keys(statusCounts);
        const sValues = Object.values(statusCounts);
        new Chart(sPie, { 
            type: 'doughnut', 
            data: { 
                labels: sLabels, 
                datasets: [{ 
                    data: sValues, 
                    backgroundColor: colors.slice(0, sLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            } 
        });
    }

    // 6. Daily Expenses Line Chart (Last 30 days)
    const dailyExpenses = document.getElementById('dailyExpenses');
    if (dailyExpenses) {
        const dailyLabels = [];
        const dailyData = [];
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            dailyLabels.push(date.toLocaleDateString('en-IN', { day: '2-digit', month: 'short' }));
            dailyData.push(Math.floor(Math.random() * 5000) + 1000); // Mock data for demo
        }
        
        new Chart(dailyExpenses, { 
            type: 'line', 
            data: { 
                labels: dailyLabels, 
                datasets: [{ 
                    label: 'Daily Expenses', 
                    data: dailyData, 
                    borderColor: '#9333ea', 
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#9333ea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    },
                    x: {
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    }
                } 
            } 
        });
    }

    // 7. Category Monthly Stacked Bar Chart
    const catStacked = document.getElementById('catStacked');
    if (catStacked) {
        const datasets = Object.keys(categoryMonthlySeries).map((k, i) => ({ 
            label: k, 
            data: categoryMonthlySeries[k], 
            backgroundColor: colors[i % colors.length],
            borderColor: colors[i % colors.length],
            borderWidth: 1
        }));
        new Chart(catStacked, { 
            type: 'bar', 
            data: { 
                labels, 
                datasets 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                }, 
                scales: { 
                    x: { 
                        stacked: true,
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    }, 
                    y: { 
                        stacked: true, 
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    } 
                } 
            } 
        });
    }

    // 8. Amount Range Distribution Bar Chart
    const amountRangeChart = document.getElementById('amountRangeChart');
    if (amountRangeChart) {
        const rangeLabels = ['₹0-1K', '₹1K-5K', '₹5K-10K', '₹10K-20K', '₹20K+'];
        const rangeData = [15, 25, 20, 25, 15]; // Mock data for demo
        
        new Chart(amountRangeChart, { 
            type: 'bar', 
            data: { 
                labels: rangeLabels, 
                datasets: [{ 
                    label: 'Number of Expenses', 
                    data: rangeData, 
                    backgroundColor: gradientColors.slice(0, rangeLabels.length),
                    borderColor: colors.slice(0, rangeLabels.length),
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                }, 
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                } 
            } 
        });
    }

    // 9. Category vs Vendor Analysis Pie Chart
    const categoryVendorChart = document.getElementById('categoryVendorChart');
    if (categoryVendorChart) {
        const cvLabels = ['Supplies', 'Maintenance', 'Transport', 'Utilities', 'Salaries'];
        const cvData = [30, 25, 20, 15, 10]; // Mock data for demo
        
        new Chart(categoryVendorChart, { 
            type: 'pie', 
            data: { 
                labels: cvLabels, 
                datasets: [{ 
                    data: cvData, 
                    backgroundColor: colors.slice(0, cvLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: ${context.parsed}%`;
                            }
                        }
                    }
                } 
            } 
        });
    }

})();
</script>
@endsection



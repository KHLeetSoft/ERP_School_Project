@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Staff Dashboard</h6>
        <a href="{{ route('admin.hr.staff.index') }}" class="btn btn-sm btn-secondary">
            <i class="bx bx-left-arrow-alt"></i> Back to List
        </a>
    </div>
    
    <!-- KPI Cards Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Staff</div>
                    <div class="fs-5 fw-semibold text-primary">{{ number_format($totalStaff ?? 68) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Active Staff</div>
                    <div class="fs-5 fw-semibold text-success">{{ number_format($activeStaff ?? 58) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Salary</div>
                    <div class="fs-6 fw-semibold text-info">₹{{ number_format($totalSalary ?? 2850000) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Average Salary</div>
                    <div class="fs-6 fw-semibold text-warning">₹{{ number_format($avgSalary ?? 41912) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row 2 -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">New Hires (This Month)</div>
                    <div class="fs-5 fw-semibold text-info">{{ number_format($newHiresThisMonth ?? 5) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Contract Expiring Soon</div>
                    <div class="fs-5 fw-semibold text-warning">{{ number_format($contractExpiringSoon ?? 8) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Departments</div>
                    <div class="fs-5 fw-semibold text-purple">{{ number_format($totalDepartments ?? 8) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="text-muted small">Avg Experience</div>
                    <div class="fs-5 fw-semibold text-success">{{ number_format($avgExperience ?? 4.2) }} years</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row 1 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Department Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Employment Type Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="employmentTypeChart"></canvas>
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
                    <h6 class="mb-0">Salary Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="salaryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Experience Analysis</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="experienceChart"></canvas>
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
                    <h6 class="mb-0">Gender Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Age Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="ageChart"></canvas>
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
                    <h6 class="mb-0">Designation Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="designationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Monthly Hiring Trend</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="hiringTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 5 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Salary vs Experience</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="salaryVsExperienceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Department Performance</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="departmentPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 6 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Contract Status</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="contractStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Geographic Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="geographicChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 7 -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Salary Range Analysis</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="salaryRangeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Staff Growth Trend</h6>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:300px;">
                        <canvas id="growthTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Summary Statistics -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Staff Analytics Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-muted small">Gender Ratio</div>
                            <div class="fw-semibold">Male: 47% | Female: 53%</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-muted small">Most Popular Department</div>
                                <div class="fw-semibold">Academic (18 staff)</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-muted small">Highest Salary Range</div>
                                <div class="fw-semibold">₹25K-35K (22 staff)</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-muted small">Most Common Experience</div>
                                <div class="fw-semibold">1-3 years (18 staff)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Hires & Alerts -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Recent Hires</h6>
                </div>
                <div class="card-body">
                    @if(isset($recentHires) && count($recentHires) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentHires as $staff)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $staff->full_name }}</div>
                                        <small class="text-muted">{{ $staff->designation }} - {{ $staff->department }}</small>
                                    </div>
                                    <span class="badge bg-success">{{ $staff->hire_date->format('d M Y') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No recent hires</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0">Contract Expiry Alerts</h6>
                </div>
                <div class="card-body">
                    @if(isset($contractExpiryAlerts) && count($contractExpiryAlerts) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($contractExpiryAlerts as $staff)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $staff->full_name }}</div>
                                        <small class="text-muted">{{ $staff->designation }}</small>
                                    </div>
                                    <span class="badge bg-warning">{{ $staff->contract_end_date->format('d M Y') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No contract expiry alerts</p>
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
    // Department Chart
    const deptCtx = document.getElementById('departmentChart');
    if (deptCtx) {
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: ['Administration', 'Academic', 'Finance', 'IT', 'HR', 'Marketing', 'Operations', 'Teaching'],
                datasets: [{
                    label: 'Staff Count',
                    data: [8, 12, 6, 10, 7, 5, 9, 11],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Employment Type Chart
    const empTypeCtx = document.getElementById('employmentTypeChart');
    if (empTypeCtx) {
        new Chart(empTypeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Full Time', 'Part Time', 'Contract', 'Intern'],
                datasets: [{
                    data: [35, 12, 18, 5],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Salary Distribution Chart (bar instead of histogram)
    const salaryCtx = document.getElementById('salaryChart');
    if (salaryCtx) {
        new Chart(salaryCtx, {
            type: 'bar',
            data: {
                labels: ['15K-25K', '25K-35K', '35K-45K', '45K-55K', '55K-65K', '65K-75K', '75K+'],
                datasets: [{
                    label: 'Staff Count',
                    data: [8, 15, 12, 10, 6, 4, 2],
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Experience Analysis Chart
    const expCtx = document.getElementById('experienceChart');
    if (expCtx) {
        new Chart(expCtx, {
            type: 'line',
            data: {
                labels: ['0-1', '1-3', '3-5', '5-8', '8-10', '10+'],
                datasets: [{
                    label: 'Staff Count',
                    data: [12, 18, 15, 10, 8, 7],
                    borderColor: 'rgba(245, 158, 11, 1)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Gender Distribution Chart
    const genderCtx = document.getElementById('genderChart');
    if (genderCtx) {
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    data: [32, 38, 2],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(16, 185, 129, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Age Distribution Chart
    const ageCtx = document.getElementById('ageChart');
    if (ageCtx) {
        new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: ['20-25', '26-30', '31-35', '36-40', '41-45', '46-50', '50+'],
                datasets: [{
                    label: 'Staff Count',
                    data: [5, 12, 18, 15, 10, 8, 4],
                    backgroundColor: 'rgba(139, 92, 246, 0.8)',
                    borderColor: 'rgba(139, 92, 246, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Designation Distribution Chart (horizontalBar -> bar with indexAxis)
    const desigCtx = document.getElementById('designationChart');
    if (desigCtx) {
        new Chart(desigCtx, {
            type: 'bar',
            data: {
                labels: ['Teacher', 'Admin', 'Principal', 'Vice Principal', 'HOD', 'Accountant', 'IT Support'],
                datasets: [{
                    label: 'Staff Count',
                    data: [20, 8, 3, 5, 7, 4, 6],
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                indexAxis: 'y',
                scales: { x: { beginAtZero: true } }
            }
        });
    }

    // Monthly Hiring Trend Chart
    const hiringCtx = document.getElementById('hiringTrendChart');
    if (hiringCtx) {
        new Chart(hiringCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'New Hires',
                    data: [3, 5, 7, 4, 6, 8, 5, 7, 4, 6, 8, 5],
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Salary vs Experience Chart
    const salaryExpCtx = document.getElementById('salaryVsExperienceChart');
    if (salaryExpCtx) {
        new Chart(salaryExpCtx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Staff',
                    data: [
                        {x: 1, y: 25000}, {x: 2, y: 28000}, {x: 3, y: 32000},
                        {x: 4, y: 35000}, {x: 5, y: 38000}, {x: 6, y: 42000},
                        {x: 7, y: 45000}, {x: 8, y: 48000}, {x: 9, y: 52000},
                        {x: 10, y: 55000}
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { title: { display: true, text: 'Experience (Years)' } },
                    y: { title: { display: true, text: 'Salary (₹)' } }
                }
            }
        });
    }

    // Department Performance Chart
    const deptPerfCtx = document.getElementById('departmentPerformanceChart');
    if (deptPerfCtx) {
        new Chart(deptPerfCtx, {
            type: 'radar',
            data: {
                labels: ['Efficiency', 'Productivity', 'Innovation', 'Collaboration', 'Growth'],
                datasets: [{
                    label: 'Administration',
                    data: [85, 80, 75, 90, 85],
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)'
                }, {
                    label: 'Academic',
                    data: [90, 85, 80, 85, 90],
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { r: { beginAtZero: true, max: 100 } }
            }
        });
    }

    // Contract Status Chart
    const contractCtx = document.getElementById('contractStatusChart');
    if (contractCtx) {
        new Chart(contractCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Expiring Soon', 'Expired', 'Renewed'],
                datasets: [{
                    data: [45, 8, 3, 12],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(59, 130, 246, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Geographic Distribution Chart
    const geoCtx = document.getElementById('geographicChart');
    if (geoCtx) {
        new Chart(geoCtx, {
            type: 'bar',
            data: {
                labels: ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad', 'Pune'],
                datasets: [{
                    label: 'Staff Count',
                    data: [12, 15, 18, 10, 8, 14, 11],
                    backgroundColor: 'rgba(139, 92, 246, 0.8)',
                    borderColor: 'rgba(139, 92, 246, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Salary Range Analysis Chart
    const salaryRangeCtx = document.getElementById('salaryRangeChart');
    if (salaryRangeCtx) {
        new Chart(salaryRangeCtx, {
            type: 'polarArea',
            data: {
                labels: ['15K-25K', '25K-35K', '35K-45K', '45K-55K', '55K-65K', '65K+'],
                datasets: [{
                    data: [8, 15, 12, 10, 6, 4],
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.6)',
                        'rgba(245, 158, 11, 0.6)',
                        'rgba(16, 185, 129, 0.6)',
                        'rgba(59, 130, 246, 0.6)',
                        'rgba(139, 92, 246, 0.6)',
                        'rgba(236, 72, 153, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Staff Growth Trend Chart (area -> line with fill)
    const growthCtx = document.getElementById('growthTrendChart');
    if (growthCtx) {
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: ['2019', '2020', '2021', '2022', '2023', '2024', '2025'],
                datasets: [{
                    label: 'Total Staff',
                    data: [25, 32, 38, 45, 52, 58, 68],
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.3)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
}

</script>
@endsection

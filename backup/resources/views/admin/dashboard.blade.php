@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted">Welcome back, {{ auth()->guard('admin')->user()->name ?? 'Admin' }}!</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Teachers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Teachers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_teachers'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Classes Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Classes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_classes'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row">
        <!-- Active Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_students'] ?? 0 }}</div>
                            <div class="text-xs text-muted">New this month: {{ $stats['new_students_this_month'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Fees Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pending Fees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['pending_fees'] ?? 0, 2) }}</div>
                            <div class="text-xs text-muted">This month: ₹{{ number_format($stats['this_month_revenue'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Books Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Library Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_books'] ?? 0 }}</div>
                            <div class="text-xs text-muted">Available: {{ $stats['available_books'] ?? 0 }} | Issued: {{ $stats['issued_books'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hostel Rooms Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Hostel Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['hostel_rooms'] ?? 0 }}</div>
                            <div class="text-xs text-muted">Occupied: {{ $stats['occupied_rooms'] ?? 0 }} | Available: {{ $stats['available_rooms'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Third Statistics Row -->
    <div class="row">
        <!-- Attendance Rate Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Today's Attendance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['attendance_rate'] ?? 0 }}%</div>
                            <div class="text-xs text-muted">Present today: {{ $stats['today_attendance'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Exams Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Upcoming Exams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming_exams'] ?? 0 }}</div>
                            <div class="text-xs text-muted">Total exams: {{ $stats['total_exams'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Assignments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_assignments'] ?? 0 }}</div>
                            <div class="text-xs text-muted">Total: {{ $stats['total_assignments'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Notifications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['unread_notifications'] ?? 0 }}</div>
                            <div class="text-xs text-muted">Total: {{ $stats['total_notifications'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Analytics Charts Row -->
    <div class="row">
        <!-- Examination Performance Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4 modern-chart-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-graduation-cap me-2"></i>Examination Performance Analytics
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary active" onclick="updateExamChart('monthly')">Monthly</button>
                        <button class="btn btn-outline-primary" onclick="updateExamChart('yearly')">Yearly</button>
                        <button class="btn btn-outline-primary" onclick="updateExamChart('classwise')">Class-wise</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="examinationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finance Overview Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 modern-chart-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-money-bill-wave me-2"></i>Finance Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenditure & Income Analytics Row -->
    <div class="row">
        <!-- Expenditure Analysis Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4 modern-chart-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Monthly Expenditure Analysis
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary active" onclick="updateExpenditureChart('monthly')">Monthly</button>
                        <button class="btn btn-outline-primary" onclick="updateExpenditureChart('category')">Category</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="expenditureChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income Analytics Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4 modern-chart-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Income Analytics
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary active" onclick="updateIncomeChart('trend')">Trend</button>
                        <button class="btn btn-outline-primary" onclick="updateIncomeChart('source')">Sources</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics Row -->
    <div class="row">
        <!-- Student Growth Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4 modern-chart-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Student Growth Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="studentGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Trend Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4 modern-chart-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-check me-2"></i>Attendance Trend (Last 7 Days)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Quick Actions -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>User</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr>
                                    <td>
                                        <i class="{{ $activity['icon'] }} me-2 text-{{ $activity['color'] }}"></i>
                                        {{ $activity['activity'] }}
                                    </td>
                                    <td>{{ $activity['user'] }}</td>
                                    <td>{{ $activity['time'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $activity['color'] }}">
                                            {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Add New Student
                        </a>
                        <a href="#" class="btn btn-success">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Add New Teacher
                        </a>
                        <a href="#" class="btn btn-warning">
                            <i class="fas fa-credit-card me-2"></i>Collect Fees
                        </a>
                        <a href="#" class="btn btn-info">
                            <i class="fas fa-clipboard-list me-2"></i>Schedule Exam
                        </a>
                        <a href="#" class="btn btn-secondary">
                            <i class="fas fa-book me-2"></i>Add New Book
                        </a>
                        <a href="#" class="btn btn-dark">
                            <i class="fas fa-bell me-2"></i>Send Notification
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events and Top Classes -->
    <div class="row">
        <!-- Upcoming Events -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Events</h6>
                </div>
                <div class="card-body">
                    @foreach($upcomingEvents as $event)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-{{ $event['color'] }} rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $event['title'] }}</h6>
                            <p class="text-muted mb-0">{{ $event['date'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $event['color'] }}">{{ ucfirst($event['type']) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Performing Classes -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Performing Classes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Attendance</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topClasses as $class)
                                <tr>
                                    <td>{{ $class['class'] }}</td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: {{ $class['attendance'] }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $class['attendance'] }}%</small>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-info" style="width: {{ $class['performance'] }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $class['performance'] }}%</small>
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

    <!-- System Status -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">System Status</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-server fa-3x text-success mb-3"></i>
                                <h5>Server Status</h5>
                                <span class="badge bg-success">Online</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-database fa-3x text-info mb-3"></i>
                                <h5>Database</h5>
                                <span class="badge bg-success">Connected</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-envelope fa-3x text-warning mb-3"></i>
                                <h5>Email Service</h5>
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                <h5>Security</h5>
                                <span class="badge bg-success">Protected</span>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabH3O/n8AeC9Lbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
// Fallback if Chart.js fails to load
if (typeof Chart === 'undefined') {
    console.log('Loading Chart.js fallback...');
    var script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js';
    script.onload = function() {
        console.log('Chart.js fallback loaded successfully');
        // Re-initialize charts after fallback loads
        setTimeout(function() {
            if (typeof Chart !== 'undefined') {
                initializeAllCharts();
            }
        }, 100);
    };
    document.head.appendChild(script);
}
</script>

<style>
.modern-chart-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.modern-chart-card:hover {
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.modern-chart-card .card-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
}

.modern-chart-card .card-header h6 {
    color: white;
    font-weight: 600;
}

.btn-group .btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.chart-area {
    position: relative;
    height: 300px;
}

.chart-pie {
    position: relative;
    height: 250px;
}
</style>

<script>
// Global chart variables
let examinationChart, financeChart, expenditureChart, incomeChart, studentGrowthChart, attendanceChart;

// Chart data from server
const chartData = {
    examination: @json($chartData['examination_performance'] ?? []),
    finance: @json($chartData['finance_overview'] ?? []),
    expenditure: @json($chartData['expenditure_analysis'] ?? []),
    income: @json($chartData['income_analytics'] ?? []),
    monthlyStudents: @json($chartData['monthly_students'] ?? []),
    attendance: @json($chartData['attendance_trend'] ?? [])
};

// Function to initialize all charts
function initializeAllCharts() {
    console.log('Chart data from server:', chartData);
    
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded. Please check the CDN link.');
        return;
    }
    
    try {
        initializeExaminationChart();
        initializeFinanceChart();
        initializeExpenditureChart();
        initializeIncomeChart();
        initializeStudentGrowthChart();
        initializeAttendanceChart();
        console.log('All charts initialized successfully');
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
}

// Initialize all charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeAllCharts();
});

// Examination Performance Chart
function initializeExaminationChart() {
    const ctx = document.getElementById('examinationChart').getContext('2d');
    examinationChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.examination.months || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Exam Performance (%)',
                data: chartData.examination.exam_performance || [85, 87, 89, 88, 91, 90, 88, 92, 89, 93, 91, 94],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }, {
                label: 'Pass Rate (%)',
                data: chartData.examination.pass_rates || [78, 82, 85, 83, 88, 86, 84, 89, 87, 91, 89, 92],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Examination Performance Trends',
                    font: { size: 16, weight: 'bold' }
                },
                legend: {
                    position: 'top',
                    labels: { font: { size: 12 } }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(0,0,0,0.1)' }
                },
                x: {
                    grid: { color: 'rgba(0,0,0,0.1)' }
                }
            }
        }
    });
}

// Finance Overview Chart
function initializeFinanceChart() {
    const ctx = document.getElementById('financeChart').getContext('2d');
    financeChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.finance.labels || ['Tuition Fees', 'Hostel Fees', 'Transport', 'Library', 'Other Income'],
            datasets: [{
                data: chartData.finance.data || [45, 25, 15, 10, 5],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Income Distribution',
                    font: { size: 16, weight: 'bold' }
                },
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 } }
                }
            }
        }
    });
}

// Expenditure Analysis Chart
function initializeExpenditureChart() {
    const ctx = document.getElementById('expenditureChart').getContext('2d');
    expenditureChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.expenditure.monthly?.months || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Expenditure (₹)',
                data: chartData.expenditure.monthly?.expenditures || [120000, 135000, 128000, 142000, 138000, 145000, 132000, 148000, 140000, 155000, 150000, 160000],
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                borderRadius: 5,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Expenditure Trends',
                    font: { size: 16, weight: 'bold' }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.1)' },
                    ticks: {
                        callback: function(value) {
                            return '₹' + (value / 1000) + 'K';
                        }
                    }
                },
                x: {
                    grid: { color: 'rgba(0,0,0,0.1)' }
                }
            }
        }
    });
}

// Income Analytics Chart
function initializeIncomeChart() {
    const ctx = document.getElementById('incomeChart').getContext('2d');
    incomeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.income.trend?.months || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Income (₹)',
                data: chartData.income.trend?.incomes || [180000, 195000, 188000, 202000, 198000, 215000, 208000, 225000, 220000, 235000, 230000, 245000],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Income Trends',
                    font: { size: 16, weight: 'bold' }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.1)' },
                    ticks: {
                        callback: function(value) {
                            return '₹' + (value / 1000) + 'K';
                        }
                    }
                },
                x: {
                    grid: { color: 'rgba(0,0,0,0.1)' }
                }
            }
        }
    });
}

// Student Growth Chart
function initializeStudentGrowthChart() {
    const ctx = document.getElementById('studentGrowthChart').getContext('2d');
    studentGrowthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.monthlyStudents.months || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'New Students',
                data: chartData.monthlyStudents.counts || [12, 15, 18, 14, 20, 22, 19, 25, 23, 28, 26, 30],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Student Growth Over Time',
                    font: { size: 16, weight: 'bold' }
                },
                legend: {
                    display: false
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

// Attendance Trend Chart
function initializeAttendanceChart() {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    attendanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.attendance.days || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Attendance Rate (%)',
                data: chartData.attendance.rates || [92, 88, 95, 90, 87, 85, 89],
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Attendance Trend (Last 7 Days)',
                    font: { size: 16, weight: 'bold' }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(0,0,0,0.1)' }
                },
                x: {
                    grid: { color: 'rgba(0,0,0,0.1)' }
                }
            }
        }
    });
}

// Chart update functions
function updateExamChart(type) {
    console.log('Updating exam chart:', type);
    // Add logic to update chart data based on type
}

function updateExpenditureChart(type) {
    console.log('Updating expenditure chart:', type);
    // Add logic to update chart data based on type
}

function updateIncomeChart(type) {
    console.log('Updating income chart:', type);
    // Add logic to update chart data based on type
}
</script>
@endsection

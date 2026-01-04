@extends('teacher.layout.app')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-description', 'View comprehensive reports and analytics for your teaching activities')

@section('content')
<!-- Overview Stats -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-primary shadow h-100 py-2" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Classes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $overviewStats['total_classes'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-chalkboard-teacher text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-success shadow h-100 py-2" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Students</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $overviewStats['total_students'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-warning shadow h-100 py-2" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Grades</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $overviewStats['total_grades'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-info shadow h-100 py-2" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Attendance Rate</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $overviewStats['attendance_rate'] }}%</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-info">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Report Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Reports</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.reports.grades') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-chart-bar me-2"></i>
                            Grade Reports
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.reports.attendance') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-calendar-check me-2"></i>
                            Attendance Reports
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.reports.schedule') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Schedule Reports
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.reports.student-performance') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-user-graduate me-2"></i>
                            Student Performance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grade Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="gradeDistributionChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Attendance Trend</h6>
            </div>
            <div class="card-body">
                <canvas id="attendanceTrendChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Grades</h6>
            </div>
            <div class="card-body">
                @if($recentGrades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Assignment</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentGrades as $grade)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar me-2">
                                                {{ strtoupper(substr($grade->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($grade->student->last_name ?? 'T', 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $grade->student->first_name ?? 'Unknown' }} {{ $grade->student->last_name ?? 'Student' }}</strong>
                                                <br><small class="text-muted">{{ $grade->class_name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $grade->assignment_name }}</strong>
                                        <br><small class="text-muted">{{ ucfirst($grade->assignment_type) }}</small>
                                    </td>
                                    <td>
                                        {!! $grade->grade_badge !!}
                                        <br><small class="text-muted">{{ number_format($grade->percentage, 1) }}%</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $grade->graded_date->format('M d, Y') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-graduation-cap fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No recent grades found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Attendance</h6>
            </div>
            <div class="card-body">
                @if($recentAttendance->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendance as $attendance)
                                <tr>
                                    <td>
                                        <strong>{{ $attendance->attendance_date->format('M d, Y') }}</strong>
                                        <br><small class="text-muted">{{ $attendance->attendance_date->format('l') }}</small>
                                    </td>
                                    <td>
                                        @if($attendance->status === 'present')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Present
                                            </span>
                                        @elseif($attendance->status === 'late')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Late
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Absent
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->remarks)
                                            <small class="text-muted">{{ Str::limit($attendance->remarks, 30) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No recent attendance found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Export Reports</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                <h5>PDF Reports</h5>
                                <p class="text-muted">Export detailed reports in PDF format</p>
                                <button class="btn btn-danger" onclick="exportReport('pdf')">
                                    <i class="fas fa-download me-1"></i>Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                                <h5>Excel Reports</h5>
                                <p class="text-muted">Export data in Excel format for analysis</p>
                                <button class="btn btn-success" onclick="exportReport('excel')">
                                    <i class="fas fa-download me-1"></i>Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="fas fa-file-csv fa-3x text-info mb-3"></i>
                                <h5>CSV Reports</h5>
                                <p class="text-muted">Export raw data in CSV format</p>
                                <button class="btn btn-info" onclick="exportReport('csv')">
                                    <i class="fas fa-download me-1"></i>Export CSV
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
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize AOS
AOS.init({
    duration: 1000,
    once: true
});

// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    });
}

// Initialize counters when page loads
document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
    
    // Initialize charts
    initGradeDistributionChart();
    initAttendanceTrendChart();
});

// Grade Distribution Chart
function initGradeDistributionChart() {
    const ctx = document.getElementById('gradeDistributionChart').getContext('2d');
    
    // Sample data - in real implementation, this would come from the controller
    const gradeData = {
        'A+': 5,
        'A': 12,
        'B+': 18,
        'B': 15,
        'C+': 8,
        'C': 6,
        'D': 3,
        'F': 1
    };
    
    const labels = Object.keys(gradeData);
    const data = Object.values(gradeData);
    const colors = [
        '#28a745', '#20c997', '#17a2b8', '#007bff',
        '#ffc107', '#fd7e14', '#6c757d', '#dc3545'
    ];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
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
}

// Attendance Trend Chart
function initAttendanceTrendChart() {
    const ctx = document.getElementById('attendanceTrendChart').getContext('2d');
    
    // Sample data - in real implementation, this would come from the controller
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const presentData = [95, 92, 88, 94, 96, 93];
    const absentData = [3, 5, 8, 4, 2, 5];
    const lateData = [2, 3, 4, 2, 2, 2];
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Present',
                data: presentData,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Absent',
                data: absentData,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Late',
                data: lateData,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Export Report
function exportReport(format) {
    // This would implement actual export functionality
    alert(`Export functionality for ${format.toUpperCase()} format would be implemented here`);
}

// Add hover effects to cards
document.querySelectorAll('.stats-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.transition = 'transform 0.3s ease';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.stats-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stats-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.student-avatar {
    width: 30px;
    height: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.8rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

@media (max-width: 768px) {
    .icon-circle {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}
</style>
@endsection

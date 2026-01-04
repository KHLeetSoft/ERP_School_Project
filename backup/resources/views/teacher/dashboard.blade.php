@extends('teacher.layout.app')

@section('title', 'Dashboard')
@section('page-title', 'Teacher Dashboard')
@section('page-description', 'Welcome back, ' . Auth::user()->name . '! Here\'s your comprehensive teaching overview.')

@section('content')
<!-- Daily Thought -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary shadow d-flex align-items-center" role="alert" data-aos="fade-up">
            <div class="me-3">
                <i class="fas fa-quote-left fa-2x"></i>
            </div>
            <div>
                <div><strong>Thought of the Day (English):</strong> {{ $data['daily_thought']['en'] ?? 'Have a wonderful day of learning!' }}</div>
                <div><strong>आज का विचार (Hindi):</strong> {{ $data['daily_thought']['hi'] ?? 'सीखने का हर दिन शुभ हो!' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Stats Cards with Animations -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-primary shadow h-100 py-2" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Classes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="8">0</div>
                        <div class="text-success small">
                            <i class="fas fa-arrow-up"></i> 12% from last month
                        </div>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Students</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="245">0</div>
                        <div class="text-success small">
                            <i class="fas fa-arrow-up"></i> 8% from last month
                        </div>
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
        <div class="card stats-card border-left-info shadow h-100 py-2" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Today's Classes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="3">0</div>
                        <div class="text-info small">
                            <i class="fas fa-clock"></i> Next: 10:30 AM
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-info">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-warning shadow h-100 py-2" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Grades</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="12">0</div>
                        <div class="text-warning small">
                            <i class="fas fa-exclamation-triangle"></i> Due in 2 days
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Analytics Row -->
<div class="row mb-4">
    <!-- Student Performance Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4" data-aos="fade-right">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Student Performance Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <a class="dropdown-item" href="#">View Details</a>
                        <a class="dropdown-item" href="#">Export Data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4" data-aos="fade-left">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Attendance Distribution</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="attendanceChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Present (85%)
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-warning"></i> Late (10%)
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-danger"></i> Absent (5%)
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule and Quick Actions -->
<div class="row mb-4">
    <!-- Today's Schedule -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Today's Schedule</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <a class="dropdown-item" href="#">View Full Schedule</a>
                        <a class="dropdown-item" href="#">Add New Class</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Time</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Room</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-success">
                                <td><strong>09:00 - 10:00</strong></td>
                                <td>Mathematics</td>
                                <td>Grade 10A</td>
                                <td>Room 101</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                </td>
                            </tr>
                            <tr class="table-warning">
                                <td><strong>10:30 - 11:30</strong></td>
                                <td>Physics</td>
                                <td>Grade 11B</td>
                                <td>Lab 2</td>
                                <td><span class="badge bg-warning">In Progress</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary">End Class</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>14:00 - 15:00</strong></td>
                                <td>Mathematics</td>
                                <td>Grade 9C</td>
                                <td>Room 105</td>
                                <td><span class="badge bg-secondary">Upcoming</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary">Start Class</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        Take Attendance
                    </button>
                    <button class="btn btn-success btn-lg">
                        <i class="fas fa-edit me-2"></i>
                        Add Grades
                    </button>
                    <button class="btn btn-info btn-lg">
                        <i class="fas fa-calendar me-2"></i>
                        View Schedule
                    </button>
                    <button class="btn btn-warning btn-lg">
                        <i class="fas fa-chart-bar me-2"></i>
                        View Reports
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="card shadow mt-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Math Class Completed</h6>
                            <p class="timeline-text">Grade 10A - 2 hours ago</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Attendance Marked</h6>
                            <p class="timeline-text">Physics Lab - 4 hours ago</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Grades Updated</h6>
                            <p class="timeline-text">Grade 9C Assignment - Yesterday</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events and Notifications -->
<div class="row">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Upcoming Events & Notifications</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-left-primary h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Parent-Teacher Meeting</h6>
                                        <p class="mb-0 text-muted">Tomorrow, 2:00 PM</p>
                                        <small class="text-primary">Room 201</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-success h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-graduation-cap fa-2x text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Exam Preparation</h6>
                                        <p class="mb-0 text-muted">Next Week</p>
                                        <small class="text-success">Mathematics</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-info h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chalkboard fa-2x text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Workshop Training</h6>
                                        <p class="mb-0 text-muted">Next Month</p>
                                        <small class="text-info">Professional Development</small>
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

@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
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
});

// Student Performance Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(performanceCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Average Score',
            data: [75, 78, 82, 85, 88, 90],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Attendance Rate',
            data: [85, 87, 89, 92, 94, 96],
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Monthly Performance Trends'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Attendance Pie Chart
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
const attendanceChart = new Chart(attendanceCtx, {
    type: 'doughnut',
    data: {
        labels: ['Present', 'Late', 'Absent'],
        datasets: [{
            data: [85, 10, 5],
            backgroundColor: [
                'rgb(40, 167, 69)',
                'rgb(255, 193, 7)',
                'rgb(220, 53, 69)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

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
/* Enhanced Styles */
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
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

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
    border-left: 3px solid #4e73df;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #2c3e50;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin: 0;
}

.chart-area {
    position: relative;
    height: 300px;
}

.chart-pie {
    position: relative;
    height: 250px;
}

/* Animation for cards */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-card {
    animation: slideInUp 0.6s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .icon-circle {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .chart-area {
        height: 250px;
    }
    
    .chart-pie {
        height: 200px;
    }
}
</style>
@endsection

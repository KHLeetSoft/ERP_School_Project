@extends('teacher.layout.app')

@section('title', 'Attendance')
@section('page-title', 'Attendance Management')
@section('page-description', 'Track and manage student attendance')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-primary shadow h-100 py-2" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Records</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $attendanceStats['total_records'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-calendar-check text-white"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Present Today</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $attendanceStats['present_today'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-danger shadow h-100 py-2" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Absent Today</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $attendanceStats['absent_today'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-danger">
                            <i class="fas fa-times-circle text-white"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Late Today</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $attendanceStats['late_today'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.attendance.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>
                            Mark Attendance
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.attendance.mark-today') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-calendar-day me-2"></i>
                            Today's Classes
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-info btn-lg w-100" onclick="showAttendanceChart()">
                            <i class="fas fa-chart-pie me-2"></i>
                            View Reports
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-warning btn-lg w-100" onclick="exportAttendance()">
                            <i class="fas fa-download me-2"></i>
                            Export Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Attendance -->
<div class="row">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Today's Attendance - {{ $today->format('M d, Y') }}</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" onclick="refreshAttendance()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($attendanceRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords as $record)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="attendance-avatar me-3">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $record->attendance_date->format('M d, Y') }}</strong>
                                                <br><small class="text-muted">{{ $record->attendance_date->format('l') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($record->status === 'present')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Present
                                            </span>
                                        @elseif($record->status === 'late')
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
                                        <small class="text-muted">{{ $record->created_at->format('H:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($record->remarks)
                                            <span class="text-muted">{{ Str::limit($record->remarks, 30) }}</span>
                                        @else
                                            <span class="text-muted">No remarks</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('teacher.attendance.show', $record) }}" class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.attendance.edit', $record) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('teacher.attendance.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No attendance recorded today</h5>
                            <p class="text-muted">Start by marking attendance for your classes.</p>
                            <a href="{{ route('teacher.attendance.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Mark Attendance
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Attendance Chart Modal -->
<div class="modal fade" id="attendanceChartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="attendancePieChart" width="400" height="400"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="attendanceBarChart" width="400" height="400"></canvas>
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
});

// Show attendance chart
function showAttendanceChart() {
    const modal = new bootstrap.Modal(document.getElementById('attendanceChartModal'));
    modal.show();
    
    // Create pie chart
    const pieCtx = document.getElementById('attendancePieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent', 'Late'],
            datasets: [{
                data: [
                    {{ $attendanceStats['present_today'] }},
                    {{ $attendanceStats['absent_today'] }},
                    {{ $attendanceStats['late_today'] }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#dc3545',
                    '#ffc107'
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
                    position: 'bottom'
                }
            }
        }
    });
    
    // Create bar chart
    const barCtx = document.getElementById('attendanceBarChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Present', 'Absent', 'Late'],
            datasets: [{
                label: 'Students',
                data: [
                    {{ $attendanceStats['present_today'] }},
                    {{ $attendanceStats['absent_today'] }},
                    {{ $attendanceStats['late_today'] }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#dc3545',
                    '#ffc107'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Export attendance
function exportAttendance() {
    // This would typically generate a CSV or Excel file
    alert('Export functionality would be implemented here');
}

// Refresh attendance
function refreshAttendance() {
    location.reload();
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

.border-left-danger {
    border-left: 0.25rem solid #dc3545 !important;
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

.student-avatar, .attendance-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.9rem;
}

.empty-state {
    padding: 2rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .icon-circle {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
    }
}
</style>
@endsection

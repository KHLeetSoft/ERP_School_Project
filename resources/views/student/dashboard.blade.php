@extends('student.layout.app')

@section('title', 'Student Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">Welcome back, {{ $user->name }}!</h4>
                        <p class="text-muted mb-0">
                            @if(isset($student) && $student)
                                Class: {{ $student->class_name ?? 'N/A' }} | 
                                Roll No: {{ $student->roll_no ?? 'N/A' }} | 
                                Admission No: {{ $student->admission_no ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        @if(isset($student) && $student && $student->profile_image)
                            <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-success">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Attendance</h6>
                    <h4 class="mb-0">{{ $stats['attendance_percentage'] }}%</h4>
                    <small class="text-muted">This month</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-info">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Upcoming Exams</h6>
                    <h4 class="mb-0">{{ count($stats['upcoming_exams']) }}</h4>
                    <small class="text-muted">Next 7 days</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Assignments</h6>
                    <h4 class="mb-0">{{ count($stats['recent_assignments']) }}</h4>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon {{ $stats['fee_status'] === 'Paid' ? 'bg-success' : 'bg-danger' }}">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Fee Status</h6>
                    <h4 class="mb-0">{{ $stats['fee_status'] }}</h4>
                    <small class="text-muted">Current month</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activities</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Assignment Submitted</h6>
                            <p class="timeline-text">Mathematics Assignment - Chapter 5</p>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Exam Scheduled</h6>
                            <p class="timeline-text">Physics Mid-term Exam - Tomorrow at 10:00 AM</p>
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Fee Payment Due</h6>
                            <p class="timeline-text">Monthly fee payment is due in 3 days</p>
                            <small class="text-muted">2 days ago</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Attendance Marked</h6>
                            <p class="timeline-text">Present for all classes today</p>
                            <small class="text-muted">3 days ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-check me-2"></i>View Attendance
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-chart-line me-2"></i>Check Results
                    </a>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-book me-2"></i>View Assignments
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-credit-card me-2"></i>Pay Fees
                    </a>
                    <a href="{{ route('student.profile') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user me-2"></i>Update Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Events</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Physics Mid-term Exam</h6>
                                <p class="text-muted mb-0">Tomorrow at 10:00 AM</p>
                                <small class="text-danger">Room: A-101</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Mathematics Assignment Due</h6>
                                <p class="text-muted mb-0">In 2 days</p>
                                <small class="text-info">Chapter 6 - Algebra</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Sports Day</h6>
                                <p class="text-muted mb-0">Next Friday</p>
                                <small class="text-success">School Ground</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Parent-Teacher Meeting</h6>
                                <p class="text-muted mb-0">Next Monday</p>
                                <small class="text-warning">Conference Room</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
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
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #e9ecef;
    }

    .timeline-title {
        margin-bottom: 5px;
        font-weight: 600;
    }

    .timeline-text {
        margin-bottom: 5px;
        color: #6c757d;
    }

    .stats-card {
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@extends('student.layout.app')

@section('title', 'My Attendance')
@section('page-title', 'Attendance')

@section('content')
<div class="row">
    <!-- Attendance Statistics -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Present Days</h6>
                            <h4 class="mb-0">{{ $stats['present_days'] }}</h4>
                            <small class="text-muted">This month</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Absent Days</h6>
                            <h4 class="mb-0">{{ $stats['absent_days'] }}</h4>
                            <small class="text-muted">This month</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Late Days</h6>
                            <h4 class="mb-0">{{ $stats['late_days'] }}</h4>
                            <small class="text-muted">This month</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Attendance %</h6>
                            <h4 class="mb-0">{{ $stats['attendance_percentage'] }}%</h4>
                            <small class="text-muted">This month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Selector -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Attendance for {{ $selectedDate->format('F Y') }}</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('student.attendance.index') }}" class="d-flex gap-2">
                            <input type="month" name="month" class="form-control" value="{{ $currentMonth }}" max="{{ date('Y-m') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Calendar -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Attendance Calendar</h5>
            </div>
            <div class="card-body">
                <div class="attendance-calendar">
                    <!-- Calendar Header -->
                    <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">{{ $selectedDate->format('F Y') }}</h6>
                        <div class="d-flex gap-2">
                            <a href="{{ route('student.attendance.calendar') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-calendar"></i> Full Calendar
                            </a>
                            <a href="{{ route('student.attendance.report') }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-chart-bar"></i> Report
                            </a>
                        </div>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div class="calendar-grid">
                        <!-- Days of week header -->
                        <div class="calendar-weekdays d-flex">
                            <div class="calendar-day-header">Sun</div>
                            <div class="calendar-day-header">Mon</div>
                            <div class="calendar-day-header">Tue</div>
                            <div class="calendar-day-header">Wed</div>
                            <div class="calendar-day-header">Thu</div>
                            <div class="calendar-day-header">Fri</div>
                            <div class="calendar-day-header">Sat</div>
                        </div>
                        
                        <!-- Calendar days -->
                        @foreach($calendarData as $week)
                            <div class="calendar-week d-flex">
                                @foreach($week as $day)
                                    <div class="calendar-day {{ !$day['is_current_month'] ? 'other-month' : '' }} {{ $day['attendance'] ? 'has-attendance' : '' }}">
                                        <div class="day-number">{{ $day['date']->day }}</div>
                                        @if($day['attendance'])
                                            <div class="attendance-status">
                                                @if($day['attendance']->status === 'present')
                                                    <i class="fas fa-check text-success"></i>
                                                @elseif($day['attendance']->status === 'absent')
                                                    <i class="fas fa-times text-danger"></i>
                                                @elseif($day['attendance']->status === 'late')
                                                    <i class="fas fa-clock text-warning"></i>
                                                @elseif($day['attendance']->status === 'leave')
                                                    <i class="fas fa-user-times text-info"></i>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Attendance</h5>
            </div>
            <div class="card-body">
                @if($attendances->count() > 0)
                    <div class="attendance-list">
                        @foreach($attendances->take(10) as $attendance)
                            <div class="attendance-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($attendance->date)->format('l') }}</small>
                                </div>
                                <div>
                                    @if($attendance->status === 'present')
                                        <span class="badge bg-success">Present</span>
                                    @elseif($attendance->status === 'absent')
                                        <span class="badge bg-danger">Absent</span>
                                    @elseif($attendance->status === 'late')
                                        <span class="badge bg-warning">Late</span>
                                    @elseif($attendance->status === 'leave')
                                        <span class="badge bg-info">Leave</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No attendance records found for this month.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('student.attendance.show', ['date' => date('Y-m-d')]) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-eye me-2"></i>Today's Attendance
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('student.attendance.calendar') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-calendar me-2"></i>Full Calendar
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('student.attendance.report') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-chart-bar me-2"></i>Generate Report
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .attendance-calendar {
        font-size: 0.9rem;
    }

    .calendar-weekdays {
        background: #f8f9fa;
        border-radius: 8px 8px 0 0;
        margin-bottom: 0;
    }

    .calendar-day-header {
        flex: 1;
        padding: 0.75rem;
        text-align: center;
        font-weight: 600;
        color: #6c757d;
        border-right: 1px solid #dee2e6;
    }

    .calendar-day-header:last-child {
        border-right: none;
    }

    .calendar-week {
        border-bottom: 1px solid #dee2e6;
    }

    .calendar-week:last-child {
        border-bottom: none;
        border-radius: 0 0 8px 8px;
    }

    .calendar-day {
        flex: 1;
        min-height: 60px;
        padding: 0.5rem;
        border-right: 1px solid #dee2e6;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .calendar-day:last-child {
        border-right: none;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
    }

    .calendar-day.other-month {
        color: #adb5bd;
        background-color: #f8f9fa;
    }

    .calendar-day.has-attendance {
        background-color: #e3f2fd;
    }

    .day-number {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .attendance-status {
        position: absolute;
        bottom: 0.25rem;
        right: 0.25rem;
        font-size: 0.8rem;
    }

    .attendance-item:last-child {
        border-bottom: none !important;
    }

    .attendance-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection

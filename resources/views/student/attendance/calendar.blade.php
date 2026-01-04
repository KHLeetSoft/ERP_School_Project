@extends('student.layout.app')

@section('title', 'Attendance Calendar')
@section('page-title', 'Attendance Calendar')

@section('content')
<div class="row">
    <!-- Year/Month Selector -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Attendance Calendar - {{ $year }}</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('student.attendance.calendar') }}" class="d-flex gap-2">
                            <select name="year" class="form-select">
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">Legend</h6>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="attendance-legend present me-2"></div>
                            <span>Present</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="attendance-legend absent me-2"></div>
                            <span>Absent</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="attendance-legend late me-2"></div>
                            <span>Late</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="attendance-legend leave me-2"></div>
                            <span>Leave</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Calendars -->
    <div class="col-12">
        <div class="row">
            @for($month = 1; $month <= 12; $month++)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="monthly-calendar">
                                <!-- Days of week header -->
                                <div class="calendar-weekdays d-flex mb-2">
                                    <div class="calendar-day-header">S</div>
                                    <div class="calendar-day-header">M</div>
                                    <div class="calendar-day-header">T</div>
                                    <div class="calendar-day-header">W</div>
                                    <div class="calendar-day-header">T</div>
                                    <div class="calendar-day-header">F</div>
                                    <div class="calendar-day-header">S</div>
                                </div>
                                
                                <!-- Calendar days -->
                                @php
                                    $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
                                    $endOfMonth = $startOfMonth->copy()->endOfMonth();
                                    $startOfWeek = $startOfMonth->copy()->startOfWeek();
                                    $endOfWeek = $endOfMonth->copy()->endOfWeek();
                                    
                                    $monthAttendance = $yearlyAttendance->get($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT), collect());
                                @endphp
                                
                                @php
                                    $current = $startOfWeek->copy();
                                    $weeks = [];
                                    while ($current->lte($endOfWeek)) {
                                        $week = [];
                                        for ($i = 0; $i < 7; $i++) {
                                            $attendance = $monthAttendance->where('date', $current->format('Y-m-d'))->first();
                                            $week[] = [
                                                'date' => $current->copy(),
                                                'attendance' => $attendance,
                                                'is_current_month' => $current->month === $month,
                                            ];
                                            $current->addDay();
                                        }
                                        $weeks[] = $week;
                                    }
                                @endphp
                                
                                @foreach($weeks as $week)
                                    <div class="calendar-week d-flex">
                                        @foreach($week as $day)
                                            <div class="calendar-day-small {{ !$day['is_current_month'] ? 'other-month' : '' }} {{ $day['attendance'] ? 'has-attendance' : '' }} {{ $day['attendance'] ? 'attendance-' . $day['attendance']->status : '' }}">
                                                <div class="day-number">{{ $day['date']->day }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.attendance.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Attendance
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.attendance.report') }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-bar me-2"></i>Generate Report
                        </a>
                        <a href="{{ route('student.attendance.show', ['date' => date('Y-m-d')]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>Today's Attendance
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
    .attendance-legend {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        display: inline-block;
    }
    
    .attendance-legend.present {
        background-color: #28a745;
    }
    
    .attendance-legend.absent {
        background-color: #dc3545;
    }
    
    .attendance-legend.late {
        background-color: #ffc107;
    }
    
    .attendance-legend.leave {
        background-color: #17a2b8;
    }
    
    .monthly-calendar {
        font-size: 0.8rem;
    }
    
    .calendar-weekdays {
        background: #f8f9fa;
        border-radius: 4px 4px 0 0;
        margin-bottom: 0;
    }
    
    .calendar-day-header {
        flex: 1;
        padding: 0.25rem;
        text-align: center;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.7rem;
    }
    
    .calendar-week {
        border-bottom: 1px solid #dee2e6;
    }
    
    .calendar-week:last-child {
        border-bottom: none;
        border-radius: 0 0 4px 4px;
    }
    
    .calendar-day-small {
        flex: 1;
        min-height: 30px;
        padding: 0.25rem;
        border-right: 1px solid #dee2e6;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .calendar-day-small:last-child {
        border-right: none;
    }
    
    .calendar-day-small.other-month {
        color: #adb5bd;
        background-color: #f8f9fa;
    }
    
    .calendar-day-small.has-attendance {
        position: relative;
    }
    
    .calendar-day-small.attendance-present {
        background-color: #d4edda;
        color: #155724;
    }
    
    .calendar-day-small.attendance-absent {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .calendar-day-small.attendance-late {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .calendar-day-small.attendance-leave {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .day-number {
        font-weight: 600;
        font-size: 0.7rem;
    }
</style>
@endsection

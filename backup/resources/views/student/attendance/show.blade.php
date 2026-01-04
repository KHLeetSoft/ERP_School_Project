@extends('student.layout.app')

@section('title', 'Attendance Details')
@section('page-title', 'Attendance Details')

@section('content')
<div class="row">
    <!-- Date Selector -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Attendance for {{ $selectedDate->format('l, F d, Y') }}</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('student.attendance.show') }}" class="d-flex gap-2">
                            <input type="date" name="date" class="form-control" value="{{ $selectedDate->format('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> View
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($attendance)
        <!-- Attendance Details -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Attendance Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date</label>
                            <p class="form-control-plaintext">{{ $selectedDate->format('l, F d, Y') }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div>
                                @if($attendance->status === 'present')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>Present
                                    </span>
                                @elseif($attendance->status === 'absent')
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times me-1"></i>Absent
                                    </span>
                                @elseif($attendance->status === 'late')
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>Late
                                    </span>
                                @elseif($attendance->status === 'leave')
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-user-times me-1"></i>Leave
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($attendance->check_in_time)
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Check-in Time</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}
                                </p>
                            </div>
                        @endif
                        
                        @if($attendance->check_out_time)
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Check-out Time</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}
                                </p>
                            </div>
                        @endif
                        
                        @if($attendance->remarks)
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Remarks</label>
                                <div class="alert alert-info">
                                    <i class="fas fa-comment me-2"></i>{{ $attendance->remarks }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info me-2"></i>Status Information</h5>
                </div>
                <div class="card-body">
                    @if($attendance->status === 'present')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Present</strong><br>
                            <small>You were marked present for this day.</small>
                        </div>
                    @elseif($attendance->status === 'absent')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Absent</strong><br>
                            <small>You were marked absent for this day.</small>
                        </div>
                    @elseif($attendance->status === 'late')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Late</strong><br>
                            <small>You arrived late for this day.</small>
                        </div>
                    @elseif($attendance->status === 'leave')
                        <div class="alert alert-info">
                            <i class="fas fa-user-times me-2"></i>
                            <strong>Leave</strong><br>
                            <small>You were on leave for this day.</small>
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            For any discrepancies, please contact your class teacher or school administration.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No Attendance Record -->
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">No Attendance Record</h4>
                    <p class="text-muted">No attendance record found for {{ $selectedDate->format('l, F d, Y') }}.</p>
                    <div class="mt-4">
                        <a href="{{ route('student.attendance.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Navigation -->
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
                        @if($attendance)
                            <a href="{{ route('student.attendance.show', ['date' => $selectedDate->copy()->subDay()->format('Y-m-d')]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-chevron-left me-1"></i>Previous Day
                            </a>
                            <a href="{{ route('student.attendance.show', ['date' => $selectedDate->copy()->addDay()->format('Y-m-d')]) }}" class="btn btn-outline-primary">
                                Next Day<i class="fas fa-chevron-right ms-1"></i>
                            </a>
                        @endif
                        <a href="{{ route('student.attendance.calendar') }}" class="btn btn-outline-info">
                            <i class="fas fa-calendar me-2"></i>Full Calendar
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
    .alert {
        border: none;
        border-radius: 8px;
    }
    
    .alert-success {
        background-color: #d1edff;
        color: #0c5460;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
    }
</style>
@endsection

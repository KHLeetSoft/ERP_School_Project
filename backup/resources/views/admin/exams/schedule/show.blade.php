@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-primary fw-bold">
            <i class="bi bi-calendar-week me-2"></i> Exam Schedule Details
        </h4>
        <a href="{{ route('admin.exams.schedule.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center rounded-top">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Schedule Information</strong>
        </div>

        <div class="card-body">
            <!-- Row 1 -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="small text-muted"><i class="bi bi-journal-bookmark me-1 text-primary"></i> Exam</div>
                    <div class="fw-semibold fs-6">{{ optional($schedule->exam)->title }}</div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted"><i class="bi bi-people me-1 text-success"></i> Class</div>
                    <div class="fw-semibold fs-6">{{ $schedule->class_name }} {{ $schedule->section_name }}</div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted"><i class="bi bi-book me-1 text-info"></i> Subject</div>
                    <div class="fw-semibold fs-6">{{ $schedule->subject_name }}</div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="small text-muted"><i class="bi bi-calendar-event me-1 text-primary"></i> Date</div>
                    <div class="fw-semibold">{{ optional($schedule->exam_date)->format('d-m-Y') }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted"><i class="bi bi-clock me-1 text-success"></i> Time</div>
                    <div class="fw-semibold">{{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted"><i class="bi bi-door-closed me-1 text-warning"></i> Room</div>
                    <div class="fw-semibold">{{ $schedule->room_no ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted"><i class="bi bi-person-badge me-1 text-danger"></i> Invigilator</div>
                    <div class="fw-semibold">{{ $schedule->invigilator_name ?? '-' }}</div>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="small text-muted"><i class="bi bi-graph-up me-1 text-success"></i> Max Marks</div>
                    <div class="fw-semibold">{{ $schedule->max_marks ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted"><i class="bi bi-graph-down me-1 text-danger"></i> Pass Marks</div>
                    <div class="fw-semibold">{{ $schedule->pass_marks ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted"><i class="bi bi-check-circle me-1 text-primary"></i> Status</div>
                    <div class="fw-semibold">
                        @if($schedule->status === 'active')
                            <span class="badge bg-success px-3 py-2">Active</span>
                        @elseif($schedule->status === 'completed')
                            <span class="badge bg-primary px-3 py-2">Completed</span>
                        @else
                            <span class="badge bg-secondary text-dark px-3 py-2">{{ ucfirst($schedule->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="row">
                <div class="col-12">
                    <div class="small text-muted"><i class="bi bi-sticky me-1 text-warning"></i> Notes</div>
                    <div class="fw-semibold text-dark p-2 bg-light rounded">
                        {{ $schedule->notes ?? 'No additional notes provided.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

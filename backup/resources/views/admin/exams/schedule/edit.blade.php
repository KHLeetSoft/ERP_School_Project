@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Exam Schedule</h4>
        <a href="{{ route('admin.exams.schedule.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
    </div>

    <form method="POST" action="{{ route('admin.exams.schedule.update', $schedule) }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Core Details</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Exam <span class="text-danger">*</span></label>
                                <select class="form-select" name="exam_id" required>
                                    @foreach($exams as $e)
                                        <option value="{{ $e->id }}" @selected($schedule->exam_id==$e->id)>{{ $e->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3"><label class="form-label">Class <span class="text-danger">*</span></label><input class="form-control" name="class_name" value="{{ $schedule->class_name }}" required></div>
                            <div class="col-md-3"><label class="form-label">Section</label><input class="form-control" name="section_name" value="{{ $schedule->section_name }}"></div>
                            <div class="col-md-6"><label class="form-label">Subject <span class="text-danger">*</span></label><input class="form-control" name="subject_name" value="{{ $schedule->subject_name }}" required></div>
                            <div class="col-md-3"><label class="form-label">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="exam_date" value="{{ optional($schedule->exam_date)->format('Y-m-d') }}" required></div>
                            <div class="col-md-3"><label class="form-label">Start Time</label><input type="time" class="form-control" name="start_time" value="{{ $schedule->start_time }}"></div>
                            <div class="col-md-3"><label class="form-label">End Time</label><input type="time" class="form-control" name="end_time" value="{{ $schedule->end_time }}"></div>
                            <div class="col-md-3"><label class="form-label">Room No</label><input class="form-control" name="room_no" value="{{ $schedule->room_no }}"></div>
                            <div class="col-md-3"><label class="form-label">Max Marks</label><input type="number" step="0.01" class="form-control" name="max_marks" value="{{ $schedule->max_marks }}"></div>
                            <div class="col-md-3"><label class="form-label">Pass Marks</label><input type="number" step="0.01" class="form-control" name="pass_marks" value="{{ $schedule->pass_marks }}"></div>
                            <div class="col-md-6"><label class="form-label">Invigilator</label><input class="form-control" name="invigilator_name" value="{{ $schedule->invigilator_name }}"></div>
                            <div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="2">{{ $schedule->notes }}</textarea></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Settings</strong></div>
                    <div class="card-body">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="scheduled" @selected($schedule->status==='scheduled')>Scheduled</option>
                            <option value="completed" @selected($schedule->status==='completed')>Completed</option>
                            <option value="postponed" @selected($schedule->status==='postponed')>Postponed</option>
                            <option value="cancelled" @selected($schedule->status==='cancelled')>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Schedule</button>
                    <a href="{{ route('admin.exams.schedule.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection



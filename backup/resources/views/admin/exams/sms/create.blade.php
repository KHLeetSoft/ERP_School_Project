@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-plus-square me-2 text-primary"></i> Create SMS Campaign</h4>
        <a href="{{ route('admin.exams.sms.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
    </div>

    <form method="POST" action="{{ route('admin.exams.sms.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Details</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8"><label class="form-label">Title <span class="text-danger">*</span></label><input class="form-control" name="title" required></div>
                            <div class="col-md-4">
                                <label class="form-label">Exam</label>
                                <select class="form-select" name="exam_id">
                                    <option value="">-- Optional --</option>
                                    @foreach($exams as $e)
                                        <option value="{{ $e->id }}">{{ $e->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Audience <span class="text-danger">*</span></label>
                                <select class="form-select" name="audience_type" required>
                                    <option value="students">Students</option>
                                    <option value="parents">Parents</option>
                                    <option value="staff">Staff</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div class="col-md-4"><label class="form-label">Class</label><input class="form-control" name="class_name"></div>
                            <div class="col-md-4"><label class="form-label">Section</label><input class="form-control" name="section_name"></div>
                            <div class="col-12"><label class="form-label">Message Template <span class="text-danger">*</span></label><textarea class="form-control" rows="5" name="message_template" required placeholder="Use {student_name}, {exam_title}, {percentage} etc."></textarea></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Schedule</strong></div>
                    <div class="card-body">
                        <label class="form-label">Schedule At</label>
                        <input type="datetime-local" class="form-control" name="schedule_at">
                        <label class="form-label mt-3">Status</label>
                        <select class="form-select" name="status">
                            <option value="draft">Draft</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="sent">Sent</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                    <a href="{{ route('admin.exams.sms.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection



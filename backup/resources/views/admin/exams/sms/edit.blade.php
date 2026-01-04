@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit SMS Campaign</h4>
        <a href="{{ route('admin.exams.sms.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
    </div>

    <form method="POST" action="{{ route('admin.exams.sms.update', $sms) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Details</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8"><label class="form-label">Title <span class="text-danger">*</span></label><input class="form-control" name="title" value="{{ $sms->title }}" required></div>
                            <div class="col-md-4">
                                <label class="form-label">Exam</label>
                                <select class="form-select" name="exam_id">
                                    <option value="">-- Optional --</option>
                                    @foreach($exams as $e)
                                        <option value="{{ $e->id }}" @selected($sms->exam_id==$e->id)>{{ $e->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Audience <span class="text-danger">*</span></label>
                                <select class="form-select" name="audience_type" required>
                                    <option value="students" @selected($sms->audience_type==='students')>Students</option>
                                    <option value="parents" @selected($sms->audience_type==='parents')>Parents</option>
                                    <option value="staff" @selected($sms->audience_type==='staff')>Staff</option>
                                    <option value="custom" @selected($sms->audience_type==='custom')>Custom</option>
                                </select>
                            </div>
                            <div class="col-md-4"><label class="form-label">Class</label><input class="form-control" name="class_name" value="{{ $sms->class_name }}"></div>
                            <div class="col-md-4"><label class="form-label">Section</label><input class="form-control" name="section_name" value="{{ $sms->section_name }}"></div>
                            <div class="col-12"><label class="form-label">Message Template <span class="text-danger">*</span></label><textarea class="form-control" rows="5" name="message_template" required>{{ $sms->message_template }}</textarea></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Schedule</strong></div>
                    <div class="card-body">
                        <label class="form-label">Schedule At</label>
                        <input type="datetime-local" class="form-control" name="schedule_at" value="{{ optional($sms->schedule_at)->format('Y-m-d\TH:i') }}">
                        <label class="form-label mt-3">Status</label>
                        <select class="form-select" name="status">
                            <option value="draft" @selected($sms->status==='draft')>Draft</option>
                            <option value="scheduled" @selected($sms->status==='scheduled')>Scheduled</option>
                            <option value="sent" @selected($sms->status==='sent')>Sent</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                    <a href="{{ route('admin.exams.sms.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection



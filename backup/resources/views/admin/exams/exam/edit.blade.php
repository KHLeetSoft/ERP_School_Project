@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Exam</h4>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
    </div>

    <form method="POST" action="{{ route('admin.exams.update', $exam) }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Exam Details</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input class="form-control" name="title" value="{{ $exam->title }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Type</label>
                                <input class="form-control" name="exam_type" value="{{ $exam->exam_type }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Academic Year</label>
                                <input class="form-control" name="academic_year" value="{{ $exam->academic_year }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ optional($exam->start_date)->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ optional($exam->end_date)->format('Y-m-d') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3">{{ $exam->description }}</textarea>
                            </div>
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
                            <option value="draft" @selected($exam->status==='draft')>Draft</option>
                            <option value="scheduled" @selected($exam->status==='scheduled')>Scheduled</option>
                            <option value="completed" @selected($exam->status==='completed')>Completed</option>
                            <option value="cancelled" @selected($exam->status==='cancelled')>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Exam</button>
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection



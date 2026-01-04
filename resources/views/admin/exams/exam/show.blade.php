@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-journal-text me-2 text-primary"></i> Exam</h4>
        <div>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><div class="text-muted">Title</div><div class="fw-semibold">{{ $exam->title }}</div></div>
                <div class="col-md-4"><div class="text-muted">Type</div><div class="fw-semibold">{{ $exam->exam_type ?? '-' }}</div></div>
                <div class="col-md-4"><div class="text-muted">Academic Year</div><div class="fw-semibold">{{ $exam->academic_year ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><div class="text-muted">Start Date</div><div class="fw-semibold">{{ optional($exam->start_date)->format('d-m-Y') ?: '-' }}</div></div>
                <div class="col-md-4"><div class="text-muted">End Date</div><div class="fw-semibold">{{ optional($exam->end_date)->format('d-m-Y') ?: '-' }}</div></div>
                <div class="col-md-4"><div class="text-muted">Status</div><div class="fw-semibold text-capitalize">{{ $exam->status }}</div></div>
            </div>
            <div class="row">
                <div class="col-12"><div class="text-muted">Description</div><div class="fw-semibold">{{ $exam->description ?? '-' }}</div></div>
            </div>
        </div>
    </div>
</div>
@endsection



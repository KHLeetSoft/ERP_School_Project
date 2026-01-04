@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-journal-text me-2 text-primary"></i> Mark Detail</h4>
        <div>
            <a href="{{ route('admin.exams.marks.print', $mark) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-printer me-1"></i> Print</a>
            <a href="{{ route('admin.exams.marks.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><div class="text-muted">Exam</div><div class="fw-semibold">{{ optional($mark->exam)->title }}</div></div>
                <div class="col-md-4"><div class="text-muted">Class</div><div class="fw-semibold">{{ $mark->class_name }} {{ $mark->section_name }}</div></div>
                <div class="col-md-4"><div class="text-muted">Subject</div><div class="fw-semibold">{{ $mark->subject_name }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><div class="text-muted">Student</div><div class="fw-semibold">{{ $mark->student_name }}</div></div>
                <div class="col-md-4"><div class="text-muted">Admission No</div><div class="fw-semibold">{{ $mark->admission_no ?? '-' }}</div></div>
                <div class="col-md-4"><div class="text-muted">Roll No</div><div class="fw-semibold">{{ $mark->roll_no ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><div class="text-muted">Marks</div><div class="fw-semibold">{{ $mark->obtained_marks }}/{{ $mark->max_marks }}</div></div>
                <div class="col-md-4"><div class="text-muted">Percentage</div><div class="fw-semibold">{{ $mark->percentage }}%</div></div>
                <div class="col-md-4"><div class="text-muted">Grade</div><div class="fw-semibold">{{ $mark->grade ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><div class="text-muted">Result</div><div class="fw-semibold text-capitalize">{{ $mark->result_status ?? '-' }}</div></div>
                <div class="col-md-4"><div class="text-muted">Status</div><div class="fw-semibold text-capitalize">{{ $mark->status }}</div></div>
            </div>
            <div class="row">
                <div class="col-12"><div class="text-muted">Remarks</div><div class="fw-semibold">{{ $mark->remarks ?? '-' }}</div></div>
            </div>
        </div>
    </div>
</div>
@endsection



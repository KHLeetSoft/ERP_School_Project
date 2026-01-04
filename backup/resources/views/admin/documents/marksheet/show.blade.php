@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i> Marksheet</h4>
        <div>
            <a href="{{ route('admin.documents.marksheet.print', $ms) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Print
            </a>
            <a href="{{ route('admin.documents.marksheet.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between">
                    <div><strong>Marksheet Details</strong></div>
                    <span class="badge bg-secondary">MS: {{ $ms->ms_number ?? '-' }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="text-muted">Student Name</div>
                            <div class="fw-semibold">{{ $ms->student_name }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Admission No</div>
                            <div class="fw-semibold">{{ $ms->admission_no ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Roll No</div>
                            <div class="fw-semibold">{{ $ms->roll_no ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="text-muted">Class</div>
                            <div class="fw-semibold">{{ $ms->class_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Section</div>
                            <div class="fw-semibold">{{ $ms->section_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Exam</div>
                            <div class="fw-semibold">{{ $ms->exam_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Issue Date</div>
                            <div class="fw-semibold">{{ optional($ms->issue_date)->format('d-m-Y') ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="text-muted">Total Marks</div>
                            <div class="fw-semibold">{{ $ms->total_marks ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Obtained</div>
                            <div class="fw-semibold">{{ $ms->obtained_marks ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Percentage</div>
                            <div class="fw-semibold">{{ $ms->percentage ?: '-' }}%</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Grade</div>
                            <div class="fw-semibold">{{ $ms->grade ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-muted">Result</div>
                            <div>
                                @if($ms->result_status==='pass')
                                    <span class="badge bg-success">Pass</span>
                                @elseif($ms->result_status==='fail')
                                    <span class="badge bg-danger">Fail</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-muted">Remarks</div>
                            <div class="fw-semibold">{{ $ms->remarks ?: '-' }}</div>
                        </div>
                        @if($ms->marks_json)
                        <div class="col-12 mt-3">
                            <div class="text-muted mb-1">Subject-wise Marks</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr><th>Subject</th><th class="text-end">Marks</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach(json_decode($ms->marks_json, true) as $row)
                                            <tr>
                                                <td>{{ $row['subject'] ?? '-' }}</td>
                                                <td class="text-end">{{ $row['marks'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Actions</strong></div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.documents.marksheet.edit', $ms) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.documents.marksheet.download', $ms) }}" class="btn btn-outline-success">
                        <i class="bi bi-download me-1"></i> Download CSV
                    </a>
                    <a href="{{ route('admin.documents.marksheet.print', $ms) }}" target="_blank" class="btn btn-outline-secondary">
                        <i class="bi bi-printer me-1"></i> Print
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-box-arrow-right me-2 text-primary"></i> Leaving Certificate</h4>
        <div>
            <a href="{{ route('admin.documents.leaving-certificate.print', $lc) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Print
            </a>
            <a href="{{ route('admin.documents.leaving-certificate.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between">
                    <div><strong>Certificate Details</strong></div>
                    <span class="badge bg-secondary">LC: {{ $lc->lc_number ?? '-' }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="text-muted">Student Name</div>
                            <div class="fw-semibold">{{ $lc->student_name }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Admission No</div>
                            <div class="fw-semibold">{{ $lc->admission_no ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">DOB</div>
                            <div class="fw-semibold">{{ optional($lc->date_of_birth)->format('d-m-Y') ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="text-muted">Class</div>
                            <div class="fw-semibold">{{ $lc->class_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Section</div>
                            <div class="fw-semibold">{{ $lc->section_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Issue Date</div>
                            <div class="fw-semibold">{{ optional($lc->issue_date)->format('d-m-Y') ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Status</div>
                            <div>
                                @if($lc->status==='issued')
                                    <span class="badge bg-success">Issued</span>
                                @elseif($lc->status==='cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-muted">Father's Name</div>
                            <div class="fw-semibold">{{ $lc->father_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">Mother's Name</div>
                            <div class="fw-semibold">{{ $lc->mother_name ?: '-' }}</div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-muted">Reason for Leaving</div>
                            <div class="fw-semibold">{{ $lc->reason_for_leaving ?: '-' }}</div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-muted">Conduct</div>
                            <div class="fw-semibold">{{ $lc->conduct ?: '-' }}</div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-muted">Remarks</div>
                            <div class="fw-semibold">{{ $lc->remarks ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Actions</strong></div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.documents.leaving-certificate.edit', $lc) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.documents.leaving-certificate.download', $lc) }}" class="btn btn-outline-success">
                        <i class="bi bi-download me-1"></i> Download CSV
                    </a>
                    <a href="{{ route('admin.documents.leaving-certificate.print', $lc) }}" target="_blank" class="btn btn-outline-secondary">
                        <i class="bi bi-printer me-1"></i> Print
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



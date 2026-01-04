@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-shield-check me-2 text-primary"></i> Conduct Certificate (Employee)</h4>
        <div>
            <a href="{{ route('admin.documents.employee-conduct-certificate.print', $ecc) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Print
            </a>
            <a href="{{ route('admin.documents.employee-conduct-certificate.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between">
                    <div><strong>Certificate Details</strong></div>
                    <span class="badge bg-secondary">ECC: {{ $ecc->ecc_number ?? '-' }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6"><div class="text-muted">Employee Name</div><div class="fw-semibold">{{ $ecc->employee_name }}</div></div>
                        <div class="col-md-3"><div class="text-muted">Employee ID</div><div class="fw-semibold">{{ $ecc->employee_id ?? '-' }}</div></div>
                        <div class="col-md-3"><div class="text-muted">Designation</div><div class="fw-semibold">{{ $ecc->designation ?? '-' }}</div></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><div class="text-muted">Department</div><div class="fw-semibold">{{ $ecc->department ?: '-' }}</div></div>
                        <div class="col-md-3"><div class="text-muted">Issue Date</div><div class="fw-semibold">{{ optional($ecc->issue_date)->format('d-m-Y') ?: '-' }}</div></div>
                        <div class="col-md-3"><div class="text-muted">Conduct</div><div class="fw-semibold">{{ $ecc->conduct ?: '-' }}</div></div>
                        <div class="col-md-3"><div class="text-muted">Status</div>
                            <div>
                                @if($ecc->status==='issued')
                                    <span class="badge bg-success">Issued</span>
                                @elseif($ecc->status==='cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-3"><div class="text-muted">Remarks</div><div class="fw-semibold">{{ $ecc->remarks ?: '-' }}</div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Actions</strong></div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.documents.employee-conduct-certificate.edit', $ecc) }}" class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i> Edit</a>
                    <a href="{{ route('admin.documents.employee-conduct-certificate.download', $ecc) }}" class="btn btn-outline-success"><i class="bi bi-download me-1"></i> Download CSV</a>
                    <a href="{{ route('admin.documents.employee-conduct-certificate.print', $ecc) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-printer me-1"></i> Print</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection













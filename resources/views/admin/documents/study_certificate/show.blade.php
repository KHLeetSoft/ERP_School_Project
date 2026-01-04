@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-award-fill me-2 text-primary"></i> Study Certificate
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.documents.study-certificate.print', $sc) }}" target="_blank" class="btn btn-sm btn-outline-primary shadow-sm">
                <i class="bi bi-printer me-1"></i> Print
            </a>
            <a href="{{ route('admin.documents.study-certificate.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Side --}}
        <div class="col-lg-8">

            {{-- Profile Card --}}
            <div class="card shadow-sm border-0 rounded-3 mb-3">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:60px;height:60px;font-size:20px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $sc->student_name }}</h5>
                        <small class="text-muted"><i class="bi bi-hash me-1"></i> Admission No: {{ $sc->admission_no ?? '-' }}</small>
                        <br>
                        <small class="text-muted"><i class="bi bi-person-badge me-1"></i> Roll No: {{ $sc->roll_no ?? '-' }}</small>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-primary px-3 py-2">SC: {{ $sc->sc_number ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Certificate Details --}}
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light">
                    <h6 class="fw-semibold mb-0"><i class="bi bi-info-circle me-2 text-secondary"></i> Certificate Details</h6>
                </div>
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <small class="text-muted">🏫 Class</small>
                            <div class="fw-semibold">{{ $sc->class_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">📌 Section</small>
                            <div class="fw-semibold">{{ $sc->section_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">🎂 DOB</small>
                            <div class="fw-semibold">{{ optional($sc->date_of_birth)->format('d-m-Y') ?: '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">🗓️ Issue Date</small>
                            <div class="fw-semibold">{{ optional($sc->issue_date)->format('d-m-Y') ?: '-' }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">👨 Father’s Name</small>
                            <div class="fw-semibold">{{ $sc->father_name ?: '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">👩 Mother’s Name</small>
                            <div class="fw-semibold">{{ $sc->mother_name ?: '-' }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">📅 Academic Year</small>
                            <div class="fw-semibold">{{ $sc->academic_year ?: '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">📍 Status</small>
                            <div>
                                @if($sc->status==='issued')
                                    <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i> Issued</span>
                                @elseif($sc->status==='cancelled')
                                    <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i> Cancelled</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2"><i class="bi bi-pencil me-1"></i> Draft</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <small class="text-muted">📝 Remarks</small>
                        <div class="fw-semibold">{{ $sc->remarks ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light">
                    <h6 class="fw-semibold mb-0"><i class="bi bi-tools me-2 text-secondary"></i> Actions</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.documents.study-certificate.edit', $sc) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Edit Certificate
                    </a>
                    <a href="{{ route('admin.documents.study-certificate.download', $sc) }}" class="btn btn-outline-success">
                        <i class="bi bi-download me-1"></i> Download CSV
                    </a>
                    <a href="{{ route('admin.documents.study-certificate.print', $sc) }}" target="_blank" class="btn btn-outline-secondary">
                        <i class="bi bi-printer me-1"></i> Print Certificate
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

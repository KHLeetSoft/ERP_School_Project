@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bx bx-bar-chart text-primary me-2"></i> Transfer Certificate Dashboard
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.documents.transfer-certificate.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-list"></i> Certificates
            </a>
            <a href="{{ route('admin.documents.transfer-certificate.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> New TC
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Issued</h6>
                <div class="fs-3 text-success fw-bold">{{ $statusCounts['issued'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Draft</h6>
                <div class="fs-3 text-secondary fw-bold">{{ $statusCounts['draft'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Cancelled</h6>
                <div class="fs-3 text-danger fw-bold">{{ $statusCounts['cancelled'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light py-3">
            <strong>Recent Certificates</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>TC No.</th>
                        <th>Student</th>
                        <th>Admission No</th>
                        <th>Class</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $tc)
                        <tr>
                            <td>{{ $tc->id }}</td>
                            <td>{{ $tc->tc_number ?? '-' }}</td>
                            <td>{{ $tc->student_name }}</td>
                            <td>{{ $tc->admission_no ?? '-' }}</td>
                            <td>{{ $tc->class_name }} {{ $tc->section_name }}</td>
                            <td>{{ optional($tc->issue_date)->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge bg-{{ $tc->status === 'issued' ? 'success' : ($tc->status === 'cancelled' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($tc->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No recent certificates</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection





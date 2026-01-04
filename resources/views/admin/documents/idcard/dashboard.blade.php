@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-fill text-primary me-2"></i> ID Card Dashboard
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.documents.idcard.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-list"></i> ID Cards
            </a>
            <a href="{{ route('admin.documents.idcard.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> New ID Card
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Active</h6>
                <div class="fs-3 text-success fw-bold">{{ $statusCounts['active'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Inactive</h6>
                <div class="fs-3 text-secondary fw-bold">{{ $statusCounts['inactive'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-clock-history me-2"></i> Recent ID Cards
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Issue</th>
                        <th>Expiry</th>
                        <th>Status</h>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $r)
                        <tr>
                            <td>{{ $r->student_name }}</td>
                            <td>{{ ($r->class_name ?? '-') . ' ' . ($r->section_name ?? '') }}</td>
                            <td>{{ optional($r->issue_date)->format('d M Y') }}</td>
                            <td>{{ optional($r->expiry_date)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $r->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($r->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No recent ID cards</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <div class="btn-group">
            <a href="{{ route('admin.documents.conduct-certificate.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Index
            </a>
            <a href="{{ route('admin.documents.employee-conduct-certificate.index') }}" class="btn btn-outline-dark btn-sm">
                <i class="bx bx-user"></i> Employee CC
            </a>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-success text-white"><div class="card-body text-center"><div class="fw-bold">Issued</div><div class="display-6">{{ $statusCounts['issued'] ?? 0 }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-primary text-white"><div class="card-body text-center"><div class="fw-bold">Draft</div><div class="display-6">{{ $statusCounts['draft'] ?? 0 }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-danger text-white"><div class="card-body text-center"><div class="fw-bold">Cancelled</div><div class="display-6">{{ $statusCounts['cancelled'] ?? 0 }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-dark text-white"><div class="card-body text-center"><div class="fw-bold">Total</div><div class="display-6">{{ $statusCounts->sum() }}</div></div></div></div>
    </div>
    <div class="card mt-4 shadow-sm"><div class="card-header fw-bold">Recent Conduct Certificates</div><div class="card-body p-0"><div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>#</th><th>CC No.</th><th>Student</th><th>Conduct</th><th>Issue Date</th><th>Status</th><th></th></tr></thead><tbody>@forelse($recent as $r)<tr><td>{{ $r->id }}</td><td>{{ $r->cc_number }}</td><td>{{ $r->student_name }}</td><td>{{ $r->conduct }}</td><td>{{ optional($r->issue_date)->format('Y-m-d') }}</td><td>{{ ucfirst($r->status) }}</td><td><a href="{{ route('admin.documents.conduct-certificate.show', $r) }}" class="btn btn-sm btn-outline-secondary">View</a></td></tr>@empty<tr><td colspan="7" class="text-center text-muted">No records</td></tr>@endforelse</tbody></table></div></div></div>
</div>
@endsection



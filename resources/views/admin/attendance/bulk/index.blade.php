@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Bulk Attendance Batches</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.attendance.bulk.export-template') }}" class="btn btn-sm btn-outline-secondary"><i class="bx bx-download"></i> Template</a>
                <a href="{{ route('admin.attendance.bulk.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-upload"></i> Upload Batch</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>File</th>
                            <th class="text-end">Totals</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $b)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($b->batch_date)->format('d M Y') }}</td>
                            <td>{{ $b->file_name ?? '-' }}</td>
                            <td class="text-end">
                                <span class="badge bg-secondary">Total: {{ $b->total }}</span>
                                <span class="badge bg-success">P: {{ $b->present }}</span>
                                <span class="badge bg-danger">A: {{ $b->absent }}</span>
                                <span class="badge bg-warning text-dark">L: {{ $b->late }}</span>
                                <span class="badge bg-info text-dark">HD: {{ $b->half_day }}</span>
                                <span class="badge bg-primary">Lv: {{ $b->leave }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">No batches yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $batches->links() }}
        </div>
    </div>
</div>
@endsection



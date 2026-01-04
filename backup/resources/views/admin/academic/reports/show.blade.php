@extends('admin.layout.app')

@section('content')
<div class="container">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-file-earmark-text text-primary me-2"></i> Report Details
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.academic.reports.edit', $report) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="{{ route('admin.academic.reports.download', $report) }}" class="btn btn-outline-success">
                <i class="bi bi-download"></i> Download
            </a>
            <form action="{{ route('admin.academic.reports.destroy', $report) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this report?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Report Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-bold d-flex justify-content-between">
            <span><i class="bi bi-info-circle me-2 text-primary"></i> Report #{{ $report->id }}</span>
            <span class="badge 
                @if($report->status === 'completed') bg-success
                @elseif($report->status === 'pending') bg-warning
                @elseif($report->status === 'cancelled') bg-secondary
                @else bg-info @endif">
                {{ ucfirst($report->status) }}
            </span>
        </div>

        <div class="card-body">

            <!-- Row 1 -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong><i class="bi bi-bookmark me-1 text-primary"></i> Title:</strong>
                    <div class="fs-6 text-dark">{{ $report->title }}</div>
                </div>
                <div class="col-md-3">
                    <strong><i class="bi bi-calendar-event me-1 text-success"></i> Date:</strong>
                    <div>{{ optional($report->report_date)->format('d M Y') }}</div>
                </div>
                <div class="col-md-3">
                    <strong><i class="bi bi-diagram-3 me-1 text-info"></i> Type:</strong>
                    <div>{{ $report->type ?? '-' }}</div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong><i class="bi bi-person-badge me-1 text-warning"></i> Teacher:</strong>
                    <div>{{ $report->teacher->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong><i class="bi bi-people me-1 text-success"></i> Class:</strong>
                    <div>{{ $report->class->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong><i class="bi bi-journal-bookmark me-1 text-danger"></i> Subject:</strong>
                    <div>{{ $report->subject->name ?? '-' }}</div>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong><i class="bi bi-person me-1 text-primary"></i> Created By:</strong>
                    <div>{{ $report->createdBy->name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <strong><i class="bi bi-person-check me-1 text-success"></i> Updated By:</strong>
                    <div>{{ $report->updatedBy->name ?? '-' }}</div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <strong><i class="bi bi-card-text me-1 text-secondary"></i> Description:</strong>
                <div class="border rounded p-3 bg-light">
                    {{ $report->description ?? 'No description available.' }}
                </div>
            </div>

            <!-- Attachments -->
            @if(!empty($report->attachments) && count($report->attachments))
            <div class="mb-3">
                <strong><i class="bi bi-paperclip me-1 text-dark"></i> Attachments:</strong>
                <ul class="list-group mt-2">
                    @foreach($report->attachments as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $file->filename }}</span>
                            <a href="{{ route('admin.academic.reports.downloadFile', $file->id) }}" 
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Footer -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.academic.reports.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Reports
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

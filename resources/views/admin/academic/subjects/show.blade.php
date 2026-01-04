@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-book-open text-primary me-2"></i>Subject Details</h4>
        <div>
            <a href="{{ route('admin.academic.subjects.edit', $subject) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.academic.subjects.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            
            <!-- Info Grid -->
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Name</h6>
                    <p class="fw-semibold">{{ $subject->name }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Code</h6>
                    <p class="fw-semibold">{{ $subject->code }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Type</h6>
                    <p class="fw-semibold">{{ ucfirst($subject->type) }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Credit Hours</h6>
                    <p class="fw-semibold">{{ $subject->credit_hours ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Status</h6>
                    <span class="badge {{ $subject->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $subject->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="col-md-12">
                    <h6 class="text-muted">Description</h6>
                    <p class="mb-0">{{ $subject->description ?: 'No description provided.' }}</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

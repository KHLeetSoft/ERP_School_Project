@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Assignment Details</h4>
       
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ $assignment->title }}</h4>
        </div>
        <div class="card-body">
            {{-- Assignment Info --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Class:</strong> {{ $assignment->schoolClass->name ?? '-' }}</p>
                    <p><strong>Section:</strong> {{ $assignment->section->name ?? '-' }}</p>
                    <p><strong>Subject:</strong> {{ $assignment->subject->name ?? '-' }}</p>
                    <p><strong>Teacher:</strong> {{ $assignment->teacher->name ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Assigned Date:</strong> {{ $assignment->assigned_date ?? '-' }}</p>
                    <p><strong>Due Date:</strong> {{ $assignment->due_date ?? '-' }}</p>
                    <p><strong>Priority:</strong> 
                        <span class="badge 
                            @if($assignment->priority == 'low') bg-success
                            @elseif($assignment->priority == 'medium') bg-warning
                            @else bg-danger
                            @endif text-white">
                            {{ ucfirst($assignment->priority) }}
                        </span>
                    </p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($assignment->status == 'pending') bg-warning
                            @elseif($assignment->status == 'submitted') bg-info
                            @elseif($assignment->status == 'checked') bg-primary
                            @elseif($assignment->status == 'completed') bg-success
                            @else bg-secondary
                            @endif text-white">
                            {{ ucfirst($assignment->status) }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <h5>Description</h5>
                <p class="border p-3 rounded">{{ $assignment->description ?? '-' }}</p>
            </div>

            {{-- File --}}
            @if($assignment->file)
            <div class="mb-3">
                <h5>Attached File</h5>
                <a href="{{ asset('storage/'.$assignment->file) }}" target="_blank" class="btn btn-outline-primary">
                    <i class="bx bx-download"></i> Download File
                </a>
            </div>
            @endif

            {{-- Marks --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Maximum Marks:</strong> {{ $assignment->max_marks ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Passing Marks:</strong> {{ $assignment->passing_marks ?? '-' }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-4">
                <a href="{{ route('admin.academic.assignments.edit', $assignment) }}" class="btn btn-warning me-2">
                    <i class="bx bxs-edit"></i> Edit Assignment
                </a>
                <a href="{{ route('admin.academic.assignments.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

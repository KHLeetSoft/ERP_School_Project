@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Coverage Details</h4>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ $coverage->title }}</h4>
        </div>
        <div class="card-body">
            {{-- Coverage Info --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Class:</strong> {{ $coverage->schoolClass->name ?? '-' }}</p>
                    <p><strong>Section:</strong> {{ $coverage->section->name ?? '-' }}</p>
                    <p><strong>Subject:</strong> {{ $coverage->subject->name ?? '-' }}</p>
                    <p><strong>Teacher:</strong> {{ $coverage->teacher->name ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date:</strong> {{ $coverage->date ? \Carbon\Carbon::parse($coverage->date)->format('d M Y') : '-' }}</p>
                    <p><strong>Priority:</strong> 
                        <span class="badge 
                            @if($coverage->priority == 'low') bg-success
                            @elseif($coverage->priority == 'medium') bg-warning
                            @else bg-danger
                            @endif text-white">
                            {{ ucfirst($coverage->priority ?? '-') }}
                        </span>
                    </p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($coverage->status == 'pending') bg-warning
                            @elseif($coverage->status == 'in-progress') bg-info
                            @elseif($coverage->status == 'completed') bg-success
                            @else bg-secondary
                            @endif text-white">
                            {{ ucfirst($coverage->status ?? '-') }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <h5>Description</h5>
                <p class="border p-3 rounded">{{ $coverage->description ?? '-' }}</p>
            </div>

            {{-- Attached File (if any) --}}
            @if(!empty($coverage->file))
            <div class="mb-3">
                <h5>Attached File</h5>
                <a href="{{ asset('storage/'.$coverage->file) }}" target="_blank" class="btn btn-outline-primary">
                    <i class="bx bx-download"></i> Download File
                </a>
            </div>
            @endif

            {{-- Created & Updated --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Created At:</strong> {{ $coverage->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Last Updated:</strong> {{ $coverage->updated_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-4">
                <a href="{{ route('admin.academic.coverage.edit', $coverage) }}" class="btn btn-warning me-2">
                    <i class="bx bxs-edit"></i> Edit Coverage
                </a>
                <a href="{{ route('admin.academic.coverage.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('parent.layout.app')

@section('title', 'My Children')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Children</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Children</li>
        </ol>
    </nav>
</div>

<div class="row">
    @forelse($children as $child)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $child->first_name }} {{ $child->last_name }}</h5>
                            <p class="text-muted mb-1">{{ $child->admission_number }}</p>
                            <span class="badge {{ $child->status ? 'badge-success' : 'badge-warning' }}">
                                {{ $child->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="fw-bold text-primary">{{ $child->schoolClass->name ?? 'N/A' }}</div>
                                <small class="text-muted">Class</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="fw-bold text-info">{{ $child->section->name ?? 'N/A' }}</div>
                                <small class="text-muted">Section</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-success">{{ $child->roll_number ?? 'N/A' }}</div>
                            <small class="text-muted">Roll No.</small>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('parent.children.show', $child) }}" class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i>View Details
                        </a>
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('parent.children.progress', $child) }}" class="btn btn-outline-info btn-sm w-100">
                                    <i class="fas fa-chart-line me-1"></i>Progress
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('parent.children.attendance', $child) }}" class="btn btn-outline-warning btn-sm w-100">
                                    <i class="fas fa-calendar-check me-1"></i>Attendance
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-child fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">No Children Registered</h4>
                    <p class="text-muted">You don't have any children registered in the system yet.</p>
                    <p class="text-muted">Please contact the school administration to register your children.</p>
                    <a href="{{ route('parent.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($children->hasPages())
    <div class="d-flex justify-content-center">
        {{ $children->links() }}
    </div>
@endif
@endsection

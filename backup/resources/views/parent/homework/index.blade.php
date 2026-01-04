@extends('parent.layout.app')

@section('title', 'Homework & Assignments')

@section('content')
<div class="page-header">
    <h1 class="page-title">Homework & Assignments</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Homework & Assignments</li>
        </ol>
    </nav>
</div>

<div class="row">
    @forelse($children as $child)
        <div class="col-lg-6 mb-4">
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
                            <p class="text-muted mb-1">{{ $child->schoolClass->name ?? 'N/A' }} - {{ $child->section->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Assignment Stats -->
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-success mb-1">8</div>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-warning mb-1">3</div>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h4 text-info mb-1">2</div>
                            <small class="text-muted">Overdue</small>
                        </div>
                    </div>
                    
                    <!-- Recent Assignments -->
                    <div class="mb-3">
                        <h6 class="mb-2">Recent Assignments</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">Mathematics - Algebra</h6>
                                        <small class="text-muted">Due: Dec 20, 2024</small>
                                    </div>
                                    <span class="badge badge-success">Completed</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">English - Essay</h6>
                                        <small class="text-muted">Due: Dec 22, 2024</small>
                                    </div>
                                    <span class="badge badge-warning">Pending</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">Science - Project</h6>
                                        <small class="text-muted">Due: Dec 18, 2024</small>
                                    </div>
                                    <span class="badge badge-danger">Overdue</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('parent.homework.child', $child) }}" class="btn btn-primary">
                            <i class="fas fa-book me-2"></i>View All Assignments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">No Children Found</h4>
                    <p class="text-muted">You don't have any children registered in the system yet.</p>
                    <a href="{{ route('parent.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Overall Assignment Summary -->
@if($children->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie me-2"></i>Overall Assignment Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stats-number">24</div>
                            <div class="stats-label">Total Completed</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stats-number">9</div>
                            <div class="stats-label">Pending</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stats-number">3</div>
                            <div class="stats-label">Overdue</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="stats-number">73%</div>
                            <div class="stats-label">Completion Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

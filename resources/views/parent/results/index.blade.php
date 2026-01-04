@extends('parent.layout.app')

@section('title', 'Results & Performance')

@section('content')
<div class="page-header">
    <h1 class="page-title">Results & Performance</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Results & Performance</li>
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
                    
                    <!-- Performance Stats -->
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-success mb-1">A+</div>
                                <small class="text-muted">Current Grade</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-info mb-1">85%</div>
                                <small class="text-muted">Overall %</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h4 text-warning mb-1">3rd</div>
                            <small class="text-muted">Class Rank</small>
                        </div>
                    </div>
                    
                    <!-- Subject-wise Performance -->
                    <div class="mb-3">
                        <h6 class="mb-2">Subject-wise Performance</h6>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <small>Mathematics</small>
                                <small class="text-success">95%</small>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 95%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <small>English</small>
                                <small class="text-info">88%</small>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: 88%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <small>Science</small>
                                <small class="text-warning">82%</small>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: 82%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('parent.results.child', $child) }}" class="btn btn-primary">
                            <i class="fas fa-chart-line me-2"></i>View Detailed Results
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chart-line fa-4x text-muted mb-4"></i>
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

<!-- Performance Comparison Chart -->
@if($children->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar me-2"></i>Performance Comparison
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stats-number">A+</div>
                            <div class="stats-label">Average Grade</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="stats-number">87%</div>
                            <div class="stats-label">Average Percentage</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stats-number">+5%</div>
                            <div class="stats-label">Improvement</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stats-number">2</div>
                            <div class="stats-label">Subjects Need Attention</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

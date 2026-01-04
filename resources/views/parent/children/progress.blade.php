@extends('parent.layout.app')

@section('title', $child->first_name . ' - Academic Progress')

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $child->first_name }} {{ $child->last_name }} - Academic Progress</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('parent.children') }}">My Children</a></li>
            <li class="breadcrumb-item"><a href="{{ route('parent.children.show', $child) }}">{{ $child->first_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Progress</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Progress Overview -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-line me-2"></i>Academic Progress
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="stats-number">85%</div>
                            <div class="stats-label">Overall Grade</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stats-number">A</div>
                            <div class="stats-label">Grade</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stats-number">12</div>
                            <div class="stats-label">Subjects</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="stats-number">3</div>
                            <div class="stats-label">Exams</div>
                        </div>
                    </div>
                </div>
                
                <!-- Subject-wise Progress -->
                <h6 class="mb-3">Subject-wise Performance</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Grade</th>
                                <th>Percentage</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mathematics</td>
                                <td>A+</td>
                                <td>95%</td>
                                <td><span class="badge badge-success">Excellent</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">View Details</a></td>
                            </tr>
                            <tr>
                                <td>English</td>
                                <td>A</td>
                                <td>88%</td>
                                <td><span class="badge badge-success">Good</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">View Details</a></td>
                            </tr>
                            <tr>
                                <td>Science</td>
                                <td>B+</td>
                                <td>82%</td>
                                <td><span class="badge badge-info">Good</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">View Details</a></td>
                            </tr>
                            <tr>
                                <td>Social Studies</td>
                                <td>A-</td>
                                <td>85%</td>
                                <td><span class="badge badge-success">Good</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">View Details</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Assignments -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-book me-2"></i>Recent Assignments
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Mathematics Assignment</h6>
                                <p class="text-muted mb-1">Algebra and Geometry problems</p>
                                <small class="text-muted">Due: Dec 15, 2024</small>
                            </div>
                            <div>
                                <span class="badge badge-success">Completed</span>
                                <div class="mt-1">
                                    <small class="text-muted">Grade: A+</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">English Essay</h6>
                                <p class="text-muted mb-1">Write about your favorite book</p>
                                <small class="text-muted">Due: Dec 20, 2024</small>
                            </div>
                            <div>
                                <span class="badge badge-warning">Pending</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Science Project</h6>
                                <p class="text-muted mb-1">Solar system model</p>
                                <small class="text-muted">Due: Dec 25, 2024</small>
                            </div>
                            <div>
                                <span class="badge badge-info">In Progress</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Progress Chart -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie me-2"></i>Performance Chart
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="progress-circle mx-auto mb-3" style="width: 150px; height: 150px;">
                        <div class="progress-circle-inner d-flex align-items-center justify-content-center">
                            <div>
                                <div class="h3 mb-0">85%</div>
                                <small class="text-muted">Overall</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <div class="h5 text-success mb-1">12</div>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="h5 text-warning mb-1">3</div>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Grades -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-star me-2"></i>Recent Grades
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Mathematics Test</h6>
                                <small class="text-muted">Dec 10, 2024</small>
                            </div>
                            <div>
                                <span class="badge badge-success">A+</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">English Quiz</h6>
                                <small class="text-muted">Dec 8, 2024</small>
                            </div>
                            <div>
                                <span class="badge badge-success">A</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Science Lab</h6>
                                <small class="text-muted">Dec 5, 2024</small>
                            </div>
                            <div>
                                <span class="badge badge-info">B+</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress-circle {
        position: relative;
        border-radius: 50%;
        background: conic-gradient(#2ecc71 0deg 306deg, #e9ecef 306deg 360deg);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .progress-circle-inner {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
    }
</style>
@endpush

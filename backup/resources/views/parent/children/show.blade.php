@extends('parent.layout.app')

@section('title', $child->first_name . ' ' . $child->last_name)

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $child->first_name }} {{ $child->last_name }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('parent.children') }}">My Children</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $child->first_name }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Student Information -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-user me-2"></i>Student Information
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                </div>
                
                <h5>{{ $child->first_name }} {{ $child->last_name }}</h5>
                <p class="text-muted">{{ $child->admission_number }}</p>
                
                <div class="row text-center mt-3">
                    <div class="col-6">
                        <div class="border-end">
                            <div class="fw-bold text-primary">{{ $child->schoolClass->name ?? 'N/A' }}</div>
                            <small class="text-muted">Class</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold text-info">{{ $child->section->name ?? 'N/A' }}</div>
                        <small class="text-muted">Section</small>
                    </div>
                </div>
                
                <div class="mt-3">
                    <span class="badge {{ $child->status ? 'badge-success' : 'badge-warning' }}">
                        {{ $child->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('parent.children.progress', $child) }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-line me-2"></i>Academic Progress
                    </a>
                    <a href="{{ route('parent.children.attendance', $child) }}" class="btn btn-outline-info">
                        <i class="fas fa-calendar-check me-2"></i>Attendance Record
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-book me-2"></i>Assignments
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-trophy me-2"></i>Results
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Academic Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-graduation-cap me-2"></i>Academic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Admission Number</label>
                            <div class="fw-bold">{{ $child->admission_number ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Roll Number</label>
                            <div class="fw-bold">{{ $child->roll_number ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Class</label>
                            <div class="fw-bold">{{ $child->schoolClass->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Section</label>
                            <div class="fw-bold">{{ $child->section->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Date of Birth</label>
                            <div class="fw-bold">{{ $child->date_of_birth ? $child->date_of_birth->format('M d, Y') : 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Gender</label>
                            <div class="fw-bold">{{ ucfirst($child->gender ?? 'N/A') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Blood Group</label>
                            <div class="fw-bold">{{ $child->blood_group ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <div>
                                <span class="badge {{ $child->status ? 'badge-success' : 'badge-warning' }}">
                                    {{ $child->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-clock me-2"></i>Recent Activities
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-circle text-primary" style="font-size: 0.5rem;"></i>
                            </div>
                            <div>
                                <p class="mb-1">Student profile updated</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i>
                            </div>
                            <div>
                                <p class="mb-1">New assignment posted</p>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-circle text-info" style="font-size: 0.5rem;"></i>
                            </div>
                            <div>
                                <p class="mb-1">Attendance marked</p>
                                <small class="text-muted">2 days ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Academic Progress Overview -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-line me-2"></i>Academic Progress Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
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
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stats-number">92%</div>
                            <div class="stats-label">Attendance</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stats-number">12</div>
                            <div class="stats-label">Assignments</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stats-number">2</div>
                            <div class="stats-label">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

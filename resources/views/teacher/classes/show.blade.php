@extends('teacher.layout.app')

@section('title', 'Class Details')
@section('page-title', 'Class Details')
@section('page-description', 'View detailed information about your class')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>{{ $class->class_name }}</h5>
                <div class="card-tools">
                    <a href="{{ route('teacher.classes.edit', $class) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Subject:</strong><br>
                            <span class="text-muted">{{ $class->subject->subject_name ?? 'N/A' }} 
                                @if($class->subject && $class->subject->subject_code)
                                    ({{ $class->subject->subject_code }})
                                @endif
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>School Class:</strong><br>
                            <span class="text-muted">{{ $class->schoolClass->name ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Room Number:</strong><br>
                            <span class="text-muted">{{ $class->room_number ?? 'Not specified' }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Time:</strong><br>
                            <span class="text-muted">
                                {{ $class->start_time ? \Carbon\Carbon::parse($class->start_time)->format('g:i A') : 'N/A' }} - 
                                {{ $class->end_time ? \Carbon\Carbon::parse($class->end_time)->format('g:i A') : 'N/A' }}
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Days:</strong><br>
                            <span class="text-muted">
                                @if($class->days && is_array($class->days))
                                    {{ implode(', ', $class->days) }}
                                @else
                                    Not specified
                                @endif
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $class->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($class->status ?? 'Unknown') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($class->description)
                    <div class="mb-3">
                        <strong>Description:</strong><br>
                        <span class="text-muted">{{ $class->description }}</span>
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Created:</strong><br>
                            <small class="text-muted">{{ $class->created_at->format('M d, Y \a\t g:i A') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted">{{ $class->updated_at->format('M d, Y \a\t g:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Students Section -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-users me-2"></i>Students in this Class</h6>
            </div>
            <div class="card-body">
                @if($students && $students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->name ?? 'N/A' }}</td>
                                        <td>{{ $student->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $student->status ? 'success' : 'secondary' }}">
                                                {{ $student->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('teacher.students.show', $student) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No students found for this class.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card modern-card">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $students ? $students->count() : 0 }}</h4>
                            <small class="text-muted">Total Students</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div>
                            <h4 class="text-success mb-1">{{ $class->days ? count($class->days) : 0 }}</h4>
                            <small class="text-muted">Days per Week</small>
                        </div>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-info mb-1">
                                @if($class->start_time && $class->end_time)
                                    {{ \Carbon\Carbon::parse($class->start_time)->diffInMinutes(\Carbon\Carbon::parse($class->end_time)) }}m
                                @else
                                    N/A
                                @endif
                            </h4>
                            <small class="text-muted">Duration</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            <h4 class="text-warning mb-1">
                                @if($class->days)
                                    {{ count($class->days) * 4 }}h
                                @else
                                    N/A
                                @endif
                            </h4>
                            <small class="text-muted">Weekly Hours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-tools me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.classes.edit', $class) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Class
                    </a>
                    <a href="{{ route('teacher.attendance.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-check-circle me-2"></i>Take Attendance
                    </a>
                    <a href="{{ route('teacher.grades.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-graduation-cap me-2"></i>Manage Grades
                    </a>
                    <a href="{{ route('teacher.assignments.create') }}" class="btn btn-outline-warning">
                        <i class="fas fa-tasks me-2"></i>Create Assignment
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

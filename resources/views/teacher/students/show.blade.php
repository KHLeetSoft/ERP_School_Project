@extends('teacher.layout.app')

@section('title', 'Student Details')
@section('page-title', 'Student Details')
@section('page-description', 'View detailed information about the student')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Student Information Card -->
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>{{ $student->first_name }} {{ $student->last_name }}
                </h5>
                <div class="card-tools">
                    <a href="{{ route('teacher.students.edit', $student) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Student ID:</strong><br>
                            <span class="text-muted">{{ $student->student_id ?? 'Not assigned' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Email:</strong><br>
                            <span class="text-muted">{{ $student->user->email ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Phone:</strong><br>
                            <span class="text-muted">{{ $student->phone ?? 'Not provided' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Date of Birth:</strong><br>
                            <span class="text-muted">
                                {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : 'Not provided' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Class:</strong><br>
                            <span class="text-muted">{{ $student->class->name ?? 'Not assigned' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Gender:</strong><br>
                            <span class="text-muted">{{ ucfirst($student->gender ?? 'Not specified') }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $student->status ? 'success' : 'secondary' }}">
                                {{ $student->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Parent/Guardian:</strong><br>
                            <span class="text-muted">{{ $student->parent_name ?? 'Not provided' }}</span>
                        </div>
                    </div>
                </div>
                
                @if($student->address)
                    <div class="mb-3">
                        <strong>Address:</strong><br>
                        <span class="text-muted">{{ $student->address }}</span>
                    </div>
                @endif
                
                @if($student->parent_phone)
                    <div class="mb-3">
                        <strong>Parent/Guardian Phone:</strong><br>
                        <span class="text-muted">{{ $student->parent_phone }}</span>
                    </div>
                @endif
                
                @if($student->emergency_contact_name)
                    <div class="mb-3">
                        <strong>Emergency Contact:</strong><br>
                        <span class="text-muted">
                            {{ $student->emergency_contact_name }}
                            @if($student->emergency_contact_phone)
                                ({{ $student->emergency_contact_phone }})
                            @endif
                            @if($student->emergency_contact_relation)
                                - {{ $student->emergency_contact_relation }}
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Attendance Records -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-check-circle me-2"></i>Attendance Records</h6>
            </div>
            <div class="card-body">
                @if($attendanceRecords && $attendanceRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords->take(10) as $record)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'absent' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $record->remarks ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($attendanceRecords->count() > 10)
                        <div class="text-center mt-3">
                            <small class="text-muted">Showing last 10 records</small>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No attendance records found.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Grades -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-graduation-cap me-2"></i>Grades</h6>
            </div>
            <div class="card-body">
                @if($grades && $grades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades->take(10) as $grade)
                                    <tr>
                                        <td>{{ $grade->subject ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $grade->grade >= 80 ? 'success' : ($grade->grade >= 60 ? 'warning' : 'danger') }}">
                                                {{ $grade->grade ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $grade->date ? \Carbon\Carbon::parse($grade->date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $grade->remarks ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($grades->count() > 10)
                        <div class="text-center mt-3">
                            <small class="text-muted">Showing last 10 records</small>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No grades found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card modern-card">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">
                                {{ $attendanceRecords ? $attendanceRecords->where('status', 'present')->count() : 0 }}
                            </h4>
                            <small class="text-muted">Present Days</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div>
                            <h4 class="text-danger mb-1">
                                {{ $attendanceRecords ? $attendanceRecords->where('status', 'absent')->count() : 0 }}
                            </h4>
                            <small class="text-muted">Absent Days</small>
                        </div>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-success mb-1">
                                {{ $grades ? $grades->avg('grade') : 0 }}
                            </h4>
                            <small class="text-muted">Average Grade</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            <h4 class="text-info mb-1">
                                {{ $grades ? $grades->count() : 0 }}
                            </h4>
                            <small class="text-muted">Total Grades</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-tools me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.students.edit', $student) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Student
                    </a>
                    <a href="{{ route('teacher.attendance.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-check-circle me-2"></i>Take Attendance
                    </a>
                    <a href="{{ route('teacher.grades.index') }}" class="btn btn-outline-warning">
                        <i class="fas fa-graduation-cap me-2"></i>Manage Grades
                    </a>
                    <a href="{{ route('teacher.assignments.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-tasks me-2"></i>View Assignments
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Student Timeline -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Student Created</h6>
                            <p class="timeline-text">{{ $student->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($student->updated_at != $student->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Last Updated</h6>
                                <p class="timeline-text">{{ $student->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
}
</style>
@endsection

@extends('student.layout.app')

@section('title', 'My Assignments')
@section('page-title', 'Assignments')

@section('content')
<div class="row">
    <!-- Assignment Statistics -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Total Assignments</h6>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                            <small class="text-muted">All assignments</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Submitted</h6>
                            <h4 class="mb-0">{{ $stats['submitted'] }}</h4>
                            <small class="text-muted">{{ $stats['submission_rate'] }}% completion</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Pending</h6>
                            <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                            <small class="text-muted">Not submitted</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Overdue</h6>
                            <h4 class="mb-0">{{ $stats['overdue'] }}</h4>
                            <small class="text-muted">Past due date</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('student.assignments.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="submitted" {{ $status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="subject" class="form-label">Subject</label>
                        <select name="subject" id="subject" class="form-select">
                            <option value="all" {{ $subject == 'all' ? 'selected' : '' }}>All Subjects</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}" {{ $subject == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" id="priority" class="form-select">
                            <option value="all" {{ $priority == 'all' ? 'selected' : '' }}>All Priority</option>
                            <option value="high" {{ $priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ $priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ $priority == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('student.assignments.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-1"></i>History
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assignments List -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Assignments</h5>
            </div>
            <div class="card-body">
                @if($assignments->count() > 0)
                    <div class="row">
                        @foreach($assignments as $assignment)
                            <div class="col-lg-6 mb-4">
                                <div class="assignment-card {{ $assignment->submissions->isNotEmpty() ? 'submitted' : 'pending' }}">
                                    <div class="assignment-header">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="assignment-title">{{ $assignment->title }}</h6>
                                                <p class="assignment-subject text-muted mb-1">
                                                    <i class="fas fa-book me-1"></i>{{ $assignment->subject->name ?? 'N/A' }}
                                                </p>
                                                <p class="assignment-teacher text-muted mb-0">
                                                    <i class="fas fa-user me-1"></i>{{ $assignment->teacher->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="assignment-status">
                                                @if($assignment->submissions->isNotEmpty())
                                                    <span class="badge bg-success">Submitted</span>
                                                @elseif($assignment->due_date && \Carbon\Carbon::now()->gt($assignment->due_date))
                                                    <span class="badge bg-danger">Overdue</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="assignment-body">
                                        <p class="assignment-description">{{ Str::limit($assignment->description, 150) }}</p>
                                        
                                        <div class="assignment-details">
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Due: {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') : 'No due date' }}
                                                    </small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-star me-1"></i>
                                                        Priority: <span class="badge bg-{{ $assignment->priority === 'high' ? 'danger' : ($assignment->priority === 'medium' ? 'warning' : 'info') }}">{{ ucfirst($assignment->priority) }}</span>
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            @if($assignment->max_marks)
                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <small class="text-muted">
                                                            <i class="fas fa-trophy me-1"></i>
                                                            Max Marks: {{ $assignment->max_marks }}
                                                        </small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">
                                                            <i class="fas fa-flag me-1"></i>
                                                            Pass Marks: {{ $assignment->passing_marks ?? 'N/A' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="assignment-footer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($assignment->file)
                                                    <a href="{{ asset('storage/' . $assignment->file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download me-1"></i>Download
                                                    </a>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tasks text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No Assignments Found</h4>
                        <p class="text-muted">No assignments found for the selected criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.assignments.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Submission History
                        </a>
                        <a href="{{ route('student.assignments.index', ['status' => 'pending']) }}" class="btn btn-outline-warning">
                            <i class="fas fa-clock me-2"></i>Pending Assignments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .assignment-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        border-left: 4px solid #007bff;
        height: 100%;
    }

    .assignment-card:hover {
        transform: translateY(-5px);
    }

    .assignment-card.submitted {
        border-left-color: #28a745;
    }

    .assignment-card.pending {
        border-left-color: #ffc107;
    }

    .assignment-header {
        margin-bottom: 1rem;
    }

    .assignment-title {
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .assignment-subject,
    .assignment-teacher {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .assignment-body {
        margin-bottom: 1rem;
    }

    .assignment-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .assignment-details {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 0.75rem;
    }

    .assignment-footer {
        border-top: 1px solid #dee2e6;
        padding-top: 1rem;
    }
</style>
@endsection

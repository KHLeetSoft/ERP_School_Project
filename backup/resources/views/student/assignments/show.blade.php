@extends('student.layout.app')

@section('title', 'Assignment Details')
@section('page-title', 'Assignment Details')

@section('content')
<div class="row">
    <!-- Assignment Information -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">{{ $assignment->title }}</h4>
                        <p class="text-muted mb-1">
                            <i class="fas fa-book me-1"></i>
                            Subject: {{ $assignment->subject->name ?? 'N/A' }}
                        </p>
                        <p class="text-muted mb-1">
                            <i class="fas fa-user me-1"></i>
                            Teacher: {{ $assignment->teacher->name ?? 'N/A' }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-1"></i>
                            Due Date: {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y h:i A') : 'No due date' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="assignment-status">
                            @if($submission)
                                <span class="badge bg-success fs-6">Submitted</span>
                            @elseif($assignment->due_date && \Carbon\Carbon::now()->gt($assignment->due_date))
                                <span class="badge bg-danger fs-6">Overdue</span>
                            @else
                                <span class="badge bg-warning fs-6">Pending</span>
                            @endif
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-{{ $assignment->priority === 'high' ? 'danger' : ($assignment->priority === 'medium' ? 'warning' : 'info') }}">
                                {{ ucfirst($assignment->priority) }} Priority
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignment Details -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Assignment Description</h5>
            </div>
            <div class="card-body">
                <div class="assignment-description">
                    {!! nl2br(e($assignment->description)) !!}
                </div>
                
                @if($assignment->file)
                    <div class="mt-3">
                        <h6>Attached File:</h6>
                        <a href="{{ asset('storage/' . $assignment->file) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i>Download Assignment File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Assignment Info -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info me-2"></i>Assignment Information</h5>
            </div>
            <div class="card-body">
                <div class="assignment-info">
                    <div class="info-item mb-3">
                        <label class="fw-bold">Class:</label>
                        <p class="mb-0">{{ $assignment->schoolClass->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="info-item mb-3">
                        <label class="fw-bold">Section:</label>
                        <p class="mb-0">{{ $assignment->section->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="info-item mb-3">
                        <label class="fw-bold">Assigned Date:</label>
                        <p class="mb-0">{{ $assignment->assigned_date ? \Carbon\Carbon::parse($assignment->assigned_date)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    
                    <div class="info-item mb-3">
                        <label class="fw-bold">Due Date:</label>
                        <p class="mb-0">
                            @if($assignment->due_date)
                                {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y h:i A') }}
                                @if(\Carbon\Carbon::now()->gt($assignment->due_date))
                                    <span class="text-danger">(Overdue)</span>
                                @endif
                            @else
                                No due date
                            @endif
                        </p>
                    </div>
                    
                    @if($assignment->max_marks)
                        <div class="info-item mb-3">
                            <label class="fw-bold">Max Marks:</label>
                            <p class="mb-0">{{ $assignment->max_marks }}</p>
                        </div>
                    @endif
                    
                    @if($assignment->passing_marks)
                        <div class="info-item mb-3">
                            <label class="fw-bold">Passing Marks:</label>
                            <p class="mb-0">{{ $assignment->passing_marks }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Section -->
    @if($submission)
        <!-- Submitted Assignment -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Your Submission</h5>
                </div>
                <div class="card-body">
                    <div class="submission-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Submitted on:</strong> {{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-{{ $submission->status === 'graded' ? 'success' : 'info' }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        @if($submission->submission_text)
                            <div class="mt-3">
                                <h6>Your Response:</h6>
                                <div class="submission-text">
                                    {!! nl2br(e($submission->submission_text)) !!}
                                </div>
                            </div>
                        @endif
                        
                        @if($submission->submission_file)
                            <div class="mt-3">
                                <h6>Attached File:</h6>
                                <a href="{{ route('student.assignments.download', $submission->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-download me-1"></i>Download Submission
                                </a>
                            </div>
                        @endif
                        
                        @if($submission->grade !== null)
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Grade: <span class="text-success">{{ $submission->grade }}/{{ $assignment->max_marks ?? 'N/A' }}</span></h6>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Percentage: <span class="text-success">{{ $submission->grade_percentage }}%</span></h6>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($submission->feedback)
                            <div class="mt-3">
                                <h6>Teacher Feedback:</h6>
                                <div class="feedback-text">
                                    {!! nl2br(e($submission->feedback)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Submission Form -->
        @if(!$assignment->due_date || \Carbon\Carbon::now()->lte($assignment->due_date))
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Submit Assignment</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="submission_text" class="form-label">Your Response</label>
                                <textarea name="submission_text" id="submission_text" class="form-control" rows="6" placeholder="Write your response here...">{{ old('submission_text') }}</textarea>
                                @error('submission_text')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="submission_file" class="form-label">Attach File (Optional)</label>
                                <input type="file" name="submission_file" id="submission_file" class="form-control" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                <div class="form-text">Supported formats: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG (Max: 10MB)</div>
                                @error('submission_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('student.assignments.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Assignments
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Submit Assignment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Assignment Overdue -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-danger">Assignment Overdue</h4>
                        <p class="text-muted">The submission deadline for this assignment has passed.</p>
                        <a href="{{ route('student.assignments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Assignments
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Navigation -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.assignments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Assignments
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.assignments.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Submission History
                        </a>
                        @if($assignment->file)
                            <a href="{{ asset('storage/' . $assignment->file) }}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Download Assignment
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .assignment-description {
        font-size: 1rem;
        line-height: 1.6;
        color: #333;
    }

    .assignment-info .info-item {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 0.75rem;
    }

    .assignment-info .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .submission-text,
    .feedback-text {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 1rem;
        border-left: 4px solid #007bff;
    }

    .feedback-text {
        border-left-color: #28a745;
    }
</style>
@endsection

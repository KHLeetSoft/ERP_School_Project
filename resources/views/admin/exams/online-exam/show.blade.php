@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0"><i class="bx bx-show me-2 text-primary"></i> Online Exam Details</h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.online-exam.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back to List
                </a>
                @if($onlineExam->status === 'draft' || (!$onlineExam->isActive() && !$onlineExam->isCompleted() && !$onlineExam->attempts()->exists()))
                    <a href="{{ route('admin.online-exam.edit', $onlineExam) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit me-1"></i> Edit
                    </a>
                @endif
                @if($onlineExam->completedAttempts()->exists())
                    <a href="{{ route('admin.online-exam.results', $onlineExam) }}" class="btn btn-success btn-sm">
                        <i class="bx bx-bar-chart me-1"></i> View Results
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i> Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <p class="mb-0">{{ $onlineExam->title }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="mb-0">
                                @switch($onlineExam->status)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draft</span>
                                        @break
                                    @case('published')
                                        @if($onlineExam->isActive())
                                            <span class="badge bg-success">Active</span>
                                        @elseif($onlineExam->isUpcoming())
                                            <span class="badge bg-info">Upcoming</span>
                                        @elseif($onlineExam->isCompleted())
                                            <span class="badge bg-primary">Completed</span>
                                        @else
                                            <span class="badge bg-warning">Published</span>
                                        @endif
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($onlineExam->status) }}</span>
                                @endswitch
                            </p>
                        </div>
                        @if($onlineExam->description)
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <p class="mb-0">{{ $onlineExam->description }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Class</label>
                            <p class="mb-0">{{ $onlineExam->schoolClass->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Section</label>
                            <p class="mb-0">{{ $onlineExam->section->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Subject</label>
                            <p class="mb-0">{{ $onlineExam->subject->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam Configuration -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bx bx-cog me-2"></i> Exam Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Duration</label>
                            <p class="mb-0"><i class="bx bx-time me-1"></i>{{ $onlineExam->duration_minutes }} minutes</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Total Marks</label>
                            <p class="mb-0"><i class="bx bx-bar-chart me-1"></i>{{ $onlineExam->total_marks }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Passing Marks</label>
                            <p class="mb-0"><i class="bx bx-check me-1"></i>{{ $onlineExam->passing_marks }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Start Date & Time</label>
                            <p class="mb-0">
                                <i class="bx bx-calendar me-1"></i>{{ $onlineExam->start_datetime->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">End Date & Time</label>
                            <p class="mb-0">
                                <i class="bx bx-calendar me-1"></i>{{ $onlineExam->end_datetime->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Max Attempts</label>
                            <p class="mb-0">{{ $onlineExam->max_attempts }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Negative Marking</label>
                            <p class="mb-0">
                                @if($onlineExam->negative_marking)
                                    <span class="badge bg-warning">Enabled</span> ({{ $onlineExam->negative_marks }} marks)
                                @else
                                    <span class="badge bg-success">Disabled</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bx bx-help-circle me-2"></i> Questions ({{ $onlineExam->questions->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($onlineExam->questions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Question</th>
                                        <th>Type</th>
                                        <th>Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($onlineExam->questions as $question)
                                        <tr>
                                            <td>{{ $question->pivot->order_number }}</td>
                                            <td>{{ Str::limit($question->question_text, 100) }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ strtoupper($question->type) }}</span>
                                            </td>
                                            <td>{{ $question->pivot->marks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bx bx-help-circle" style="font-size: 48px;"></i>
                            <p>No questions added to this exam yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Instructions -->
            @if($onlineExam->instructions)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-list-ul me-2"></i> Instructions</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $onlineExam->instructions }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Exam Settings -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bx bx-cog me-2"></i> Exam Settings</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Randomize Questions</span>
                            @if($onlineExam->randomize_questions)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Show Result Immediately</span>
                            @if($onlineExam->show_result_immediately)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Allow Calculator</span>
                            @if($onlineExam->allow_calculator)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Allow Notes</span>
                            @if($onlineExam->allow_notes)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 border-bottom-0">
                            <span>Enable Proctoring</span>
                            @if($onlineExam->enable_proctoring)
                                <span class="badge bg-warning">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bx bx-bar-chart me-2"></i> Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ $statistics['total_attempts'] }}</h4>
                                <small class="text-muted">Total Attempts</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1">{{ $statistics['passed_count'] }}</h4>
                                <small class="text-muted">Passed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-danger mb-1">{{ $statistics['failed_count'] }}</h4>
                                <small class="text-muted">Failed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1">{{ number_format($statistics['average_score'], 1) }}%</h4>
                                <small class="text-muted">Average Score</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bx bx-bolt me-2"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($onlineExam->status === 'draft')
                            <button type="button" class="btn btn-success" onclick="publishExam()">
                                <i class="bx bx-check me-1"></i> Publish Exam
                            </button>
                        @endif
                        
                        @if($onlineExam->status === 'published' && !$onlineExam->isCompleted())
                            <button type="button" class="btn btn-warning" onclick="cancelExam()">
                                <i class="bx bx-x me-1"></i> Cancel Exam
                            </button>
                        @endif
                        
                        @if($onlineExam->completedAttempts()->exists())
                            <a href="{{ route('admin.online-exam.results', $onlineExam) }}" class="btn btn-info">
                                <i class="bx bx-bar-chart me-1"></i> Detailed Results
                            </a>
                        @endif
                        
                        @if(!$onlineExam->attempts()->exists())
                            <button type="button" class="btn btn-danger" onclick="deleteExam()">
                                <i class="bx bx-trash me-1"></i> Delete Exam
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Publish Confirmation Modal -->
<div class="modal fade" id="publishModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Publish Online Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to publish this online exam? Once published, students will be able to take the exam according to the scheduled time.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.online-exam.publish', $onlineExam) }}" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Publish Exam</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Online Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this online exam? This will prevent students from taking the exam.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.online-exam.cancel', $onlineExam) }}" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">Cancel Exam</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Online Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this online exam? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.online-exam.destroy', $onlineExam) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function publishExam() {
    $('#publishModal').modal('show');
}

function cancelExam() {
    $('#cancelModal').modal('show');
}

function deleteExam() {
    $('#deleteModal').modal('show');
}

$(document).ready(function() {
    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>
@endsection

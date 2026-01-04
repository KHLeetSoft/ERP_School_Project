@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0"><i class="bx bx-desktop me-2 text-primary"></i> Online Exams</h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.online-exam.create') }}" class="btn btn-primary btn-sm" title="Create Online Exam" data-bs-toggle="tooltip">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive p-3">
            <table class="table table-striped align-middle w-100" id="onlineExamTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Class & Section</th>
                        <th>Subject</th>
                        <th>Duration</th>
                        <th>Marks</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($onlineExams as $index => $exam)
                        <tr>
                            <td>{{ $onlineExams->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold">{{ $exam->title }}</div>
                                @if($exam->description)
                                    <small class="text-muted">{{ Str::limit($exam->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $exam->schoolClass->name ?? 'N/A' }}</span>
                                <span class="badge bg-secondary">{{ $exam->section->name ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $exam->subject->name ?? 'N/A' }}</td>
                            <td>
                                <i class="bx bx-time me-1"></i>{{ $exam->duration_minutes }} min
                            </td>
                            <td>
                                <span class="fw-bold">{{ $exam->total_marks }}</span>
                                <small class="text-muted">(Pass: {{ $exam->passing_marks }})</small>
                            </td>
                            <td>
                                <div>{{ $exam->start_datetime->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $exam->start_datetime->format('h:i A') }}</small>
                            </td>
                            <td>
                                @switch($exam->status)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draft</span>
                                        @break
                                    @case('published')
                                        @if($exam->isActive())
                                            <span class="badge bg-success">Active</span>
                                        @elseif($exam->isUpcoming())
                                            <span class="badge bg-info">Upcoming</span>
                                        @elseif($exam->isCompleted())
                                            <span class="badge bg-primary">Completed</span>
                                        @else
                                            <span class="badge bg-warning">Published</span>
                                        @endif
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($exam->status) }}</span>
                                @endswitch
                            </td>
                            <td>
    <div class="btn-group" role="group">
        <!-- View -->
        <a href="{{ route('admin.online-exam.show', $exam) }}" 
           class="btn btn-sm " title="View">
            <i class="bx bx-show"></i>
        </a>
        
        <!-- Edit -->
        @if($exam->status === 'draft' || (!$exam->isActive() && !$exam->isCompleted() && !$exam->attempts()->exists()))
            <a href="{{ route('admin.online-exam.edit', $exam) }}" 
               class="btn btn-sm " title="Edit">
                <i class="bx bxs-edit"></i>
            </a>
        @endif
        
        <!-- Publish -->
        @if($exam->status === 'draft')
            <button type="button" class="btn btn-sm  publish-btn" 
                    data-exam-id="{{ $exam->id }}" title="Publish">
                <i class="bx bx-check"></i>
            </button>
        @endif
        
        <!-- Results -->
        @if($exam->completedAttempts()->exists())
            <a href="{{ route('admin.online-exam.results', $exam) }}" 
               class="btn btn-sm " title="Results">
                <i class="bx bx-bar-chart"></i>
            </a>
        @endif
        
        <!-- Delete -->
        @if(!$exam->attempts()->exists())
            <button type="button" class="btn btn-sm  delete-btn" 
                    data-exam-id="{{ $exam->id }}" title="Delete">
                <i class="bx bx-trash"></i>
            </button>
        @endif
    </div>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bx bx-desktop" style="font-size: 48px;"></i>
                                    <p class="mt-2">No online exams found.</p>
                                    <a href="{{ route('admin.online-exam.create') }}" class="btn btn-primary" title="Create Online Exam" data-bs-toggle="tooltip">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($onlineExams->hasPages())
            <div class="card-footer">
                {{ $onlineExams->links() }}
            </div>
        @endif
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
                <form id="publishForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Publish Exam</button>
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
                <form id="deleteForm" method="POST" style="display: inline;">
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
$(document).ready(function() {
    // Enable Bootstrap tooltips for icon-only buttons
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Publish exam
    $('.publish-btn').on('click', function() {
        const examId = $(this).data('exam-id');
        const publishUrl = "{{ route('admin.online-exam.publish', ':id') }}".replace(':id', examId);
        $('#publishForm').attr('action', publishUrl);
        $('#publishModal').modal('show');
    });
    
    // Delete exam
    $('.delete-btn').on('click', function() {
        const examId = $(this).data('exam-id');
        const deleteUrl = "{{ route('admin.online-exam.destroy', ':id') }}".replace(':id', examId);
        $('#deleteForm').attr('action', deleteUrl);
        $('#deleteModal').modal('show');
    });
    
    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>
@endsection

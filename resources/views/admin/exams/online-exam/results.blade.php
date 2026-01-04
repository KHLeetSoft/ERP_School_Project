@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0"><i class="bx bx-bar-chart me-2 text-primary"></i> Online Exam Results</h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.online-exam.show', $onlineExam) }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back to Exam
                </a>
                <a href="{{ route('admin.online-exam.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-list-ul me-1"></i> All Exams
                </a>
            </div>
        </div>
    </div>

    <!-- Exam Information -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i> {{ $onlineExam->title }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Class & Section:</strong><br>
                    <span class="badge bg-info">{{ $onlineExam->schoolClass->name ?? 'N/A' }}</span>
                    <span class="badge bg-secondary">{{ $onlineExam->section->name ?? 'N/A' }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Subject:</strong><br>
                    {{ $onlineExam->subject->name ?? 'N/A' }}
                </div>
                <div class="col-md-3">
                    <strong>Total Marks:</strong><br>
                    {{ $onlineExam->total_marks }} (Pass: {{ $onlineExam->passing_marks }})
                </div>
                <div class="col-md-3">
                    <strong>Duration:</strong><br>
                    {{ $onlineExam->duration_minutes }} minutes
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="bx bx-user-check text-primary" style="font-size: 2rem;"></i>
                    <h4 class="mt-2 mb-1">{{ $statistics['total_attempts'] }}</h4>
                    <p class="text-muted mb-0">Total Attempts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="bx bx-check-circle text-success" style="font-size: 2rem;"></i>
                    <h4 class="mt-2 mb-1">{{ $statistics['passed_count'] }}</h4>
                    <p class="text-muted mb-0">Passed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="bx bx-x-circle text-danger" style="font-size: 2rem;"></i>
                    <h4 class="mt-2 mb-1">{{ $statistics['failed_count'] }}</h4>
                    <p class="text-muted mb-0">Failed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="bx bx-trending-up text-info" style="font-size: 2rem;"></i>
                    <h4 class="mt-2 mb-1">{{ number_format($statistics['average_score'], 1) }}%</h4>
                    <p class="text-muted mb-0">Average Score</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Results -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-table me-2"></i> Detailed Results</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-sm" onclick="exportResults('excel')">
                    <i class="bx bx-file me-1"></i> Export Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="exportResults('pdf')">
                    <i class="bx bx-file-pdf me-1"></i> Export PDF
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($attempts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="resultsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Rank</th>
                                <th>Student Name</th>
                                <th>Roll Number</th>
                                <th>Attempt</th>
                                <th>Start Time</th>
                                <th>Submit Time</th>
                                <th>Time Taken</th>
                                <th>Marks Obtained</th>
                                <th>Percentage</th>
                                <th>Result</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $index => $attempt)
                                <tr>
                                    <td>
                                        @if($index === 0)
                                            <span class="badge bg-warning text-dark">🥇 1st</span>
                                        @elseif($index === 1)
                                            <span class="badge bg-secondary">🥈 2nd</span>
                                        @elseif($index === 2)
                                            <span class="badge bg-info">🥉 3rd</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2">
                                                {{ substr($attempt->student->name ?? 'N/A', 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $attempt->student->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $attempt->student->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attempt->student->student_id ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $attempt->attempt_number }}</span>
                                    </td>
                                    <td>
                                        <div>{{ $attempt->started_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $attempt->started_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($attempt->submitted_at)
                                            <div>{{ $attempt->submitted_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $attempt->submitted_at->format('h:i A') }}</small>
                                        @else
                                            <span class="text-muted">Not submitted</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attempt->time_taken_minutes)
                                            {{ $attempt->time_taken_minutes }} min
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $attempt->total_marks_obtained }}</strong> / {{ $onlineExam->total_marks }}
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $attempt->percentage >= 80 ? 'bg-success' : ($attempt->percentage >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                                 role="progressbar" style="width: {{ $attempt->percentage }}%">
                                                {{ number_format($attempt->percentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($attempt->is_passed)
                                            <span class="badge bg-success">Passed</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info view-details-btn" 
                                                    data-attempt-id="{{ $attempt->id }}" title="View Details">
                                                <i class="bx bx-show"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary download-result-btn" 
                                                    data-attempt-id="{{ $attempt->id }}" title="Download Result">
                                                <i class="bx bx-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bx bx-bar-chart text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Results Available</h5>
                    <p class="text-muted">No students have completed this exam yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Result Details Modal -->
<div class="modal fade" id="resultDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Result Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="resultDetailsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#resultsTable').DataTable({
        responsive: true,
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-success btn-sm',
                text: '<i class="bx bx-file me-1"></i> Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm',
                text: '<i class="bx bx-file-pdf me-1"></i> PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                },
                customize: function(doc) {
                    doc.content[1].table.widths = ['8%', '15%', '10%', '8%', '12%', '12%', '8%', '10%', '10%', '7%'];
                }
            },
            {
                extend: 'print',
                className: 'btn btn-info btn-sm',
                text: '<i class="bx bx-printer me-1"></i> Print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
            }
        ],
        order: [[8, 'desc']], // Order by percentage descending
        columnDefs: [
            { orderable: false, targets: [10] } // Disable ordering on Actions column
        ]
    });
    
    // View result details
    $('.view-details-btn').on('click', function() {
        const attemptId = $(this).data('attempt-id');
        loadResultDetails(attemptId);
    });
    
    // Download individual result
    $('.download-result-btn').on('click', function() {
        const attemptId = $(this).data('attempt-id');
        downloadIndividualResult(attemptId);
    });
    
    function loadResultDetails(attemptId) {
        $('#resultDetailsContent').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
        $('#resultDetailsModal').modal('show');
        
        // AJAX call to load result details
        // You would implement this endpoint in your controller
        $.get(`/admin/online-exam/attempt/${attemptId}/details`)
            .done(function(data) {
                $('#resultDetailsContent').html(data);
            })
            .fail(function() {
                $('#resultDetailsContent').html('<div class="alert alert-danger">Failed to load result details.</div>');
            });
    }
    
    function downloadIndividualResult(attemptId) {
        // Open result download in new window
        window.open(`/admin/online-exam/attempt/${attemptId}/download`, '_blank');
    }
});

function exportResults(format) {
    if (format === 'excel') {
        $('#resultsTable').DataTable().button(0).trigger();
    } else if (format === 'pdf') {
        $('#resultsTable').DataTable().button(1).trigger();
    }
}
</script>
@endsection

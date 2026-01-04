@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i> Lesson Plans Management
            </h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.academic.lesson-plans.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Add Lesson Plan
                </a>
                <a href="{{ route('admin.academic.lesson-plans.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i> Export CSV
                </a>
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import me-1"></i> Import CSV
                </button>
            </div>
        </div>
    </div>

    {{-- Statistics Dashboard --}}
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-list-alt fa-2x text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['total'] }}</h4>
                    <p class="text-muted mb-0 small">Total Plans</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-calendar-plus fa-2x text-info"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['planned'] }}</h4>
                    <p class="text-muted mb-0 small">Planned</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['in_progress'] }}</h4>
                    <p class="text-muted mb-0 small">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['completed'] }}</h4>
                    <p class="text-muted mb-0 small">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['overdue'] }}</h4>
                    <p class="text-muted mb-0 small">Overdue</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-calendar-day fa-2x text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['upcoming'] }}</h4>
                    <p class="text-muted mb-0 small">Upcoming</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">{{ $errors->first() }}</div>
    @endif

    {{-- Data Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.academic.lesson-plans.bulk-delete') }}" id="bulk-form">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="lesson-plans-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Subject</th>
                                <th>Syllabus</th>
                                <th>Ls_Info</th>
                                <th>Title</th>
                                <th>Duration</th>
                                <th>Difficulty</th>
                                <th>Status</th>
                                <th>Plan Date</th>
                                <th>Progress</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.academic.lesson-plans.import') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-import me-2"></i> Import Lesson Plans</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">CSV File</label>
                    <input type="file" name="file" class="form-control" accept=".csv" required>
                    <small class="text-muted">Only CSV files allowed. Use semicolon (;) to separate array values.</small>
                </div>
                <div class="alert alert-info">
                    <h6>CSV Format:</h6>
                    <small>
                        subject_code, syllabus_title, title, lesson_number, unit_number, learning_objectives, prerequisites, materials_needed, lesson_duration, teaching_methods, activities, assessment_methods, homework, notes, planned_date, actual_date, completion_status, difficulty_level, estimated_student_count, room_requirements, technology_needed, special_considerations, status
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm rounded-pill" type="submit">Import</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Confirm Bulk Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the selected lesson plans? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-sm rounded-pill" type="submit" form="bulk-form">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
let table;

function loadTable() {
    if (table) table.destroy();
    
    table = $('#lesson-plans-table').DataTable({
        processing: true,
        serverSide: true,
        order: [[4, 'asc']], // Order by Title column by default
        ajax: {
            url: "{{ route('admin.academic.lesson-plans.index') }}",
            type: 'GET'
        },
         dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +             // Left side: Show
                '<"col-md-6 text-end"f>' +    // Right side: Search
            '>' +
            '<"row mb-3"' +
                '<"col-12 text-end"B>' +      // Next line: Buttons full width right aligned
            '>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            { extend: 'csv', className: 'btn btn-success btn-sm rounded-pill', text: '<i class="fas fa-file-csv me-1"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-danger btn-sm rounded-pill', text: '<i class="fas fa-file-pdf me-1"></i> PDF' },
            { extend: 'print', className: 'btn btn-warning btn-sm rounded-pill', text: '<i class="fas fa-print me-1"></i> Print' },
            { extend: 'copy', className: 'btn btn-info btn-sm rounded-pill', text: '<i class="fas fa-copy me-1"></i> Copy' }
        ],
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'syllabus', name: 'syllabus' },
            { data: 'lesson_info', name: 'lesson_info' },
            { data: 'title', name: 'title' },
            { data: 'duration', name: 'duration' },
            { data: 'difficulty', name: 'difficulty' },
            { data: 'status_badge', name: 'status_badge' },
            { data: 'planned_date', name: 'planned_date' },
            { data: 'progress', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });
}

// Select all checkbox functionality
document.getElementById('select-all')?.addEventListener('change', function(e) {
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = e.target.checked);
});

// Single row delete
$(document).on('click', '.delete-lesson-plan-btn', function() {
    const lessonPlanId = $(this).data('id');
    if (confirm('Are you sure you want to delete this lesson plan?')) {
        $.ajax({
            url: `/admin/academic/lesson-plans/${lessonPlanId}`,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                table.ajax.reload();
                toastr.success('Lesson plan deleted successfully!');
            },
            error: function(xhr) {
                toastr.error('Error deleting lesson plan.');
            }
        });
    }
});

// Initialize table
$(document).ready(function() {
    loadTable();
});
</script>
@endsection

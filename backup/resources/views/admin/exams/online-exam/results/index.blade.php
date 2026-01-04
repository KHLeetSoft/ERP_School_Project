@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 d-flex align-items-center text-primary">
                <i class="bx bx-bar-chart me-2 fs-4"></i> 
                Online Exam Results
            </h4>
            <a href="{{ route('admin.online-exam.index') }}" 
               class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                <i class="bx bx-list-ul me-1"></i> All Exams
            </a>
        </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
        <div class="row mb-3">
    <div class="col-md-3">
        <select id="filterClass" class="form-select">
            <option value="">All Classes</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select id="filterSection" class="form-select">
            <option value="">All Sections</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select id="filterSubject" class="form-select">
            <option value="">All Subjects</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select id="filterStatus" class="form-select">
            <option value="">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
</div>

            <div class="table-responsive">
                <table class="table table-striped align-middle" id="examsResultsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Exam</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Subject</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th>Attempts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    $('#examsResultsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
             url: '{{ route("admin.online-exam.results.index") }}',
            data: function (d) {
                d.class_id   = $('#filterClass').val();
                d.section_id = $('#filterSection').val();
                d.subject_id = $('#filterSubject').val();
                d.status     = $('#filterStatus').val();
            }
        },
        dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +
                '<"col-md-6 text-end"f>' +
            '>' +
            '<"row mb-3"<"col-12 text-end"B>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            { extend: 'csv', className: 'btn btn-success btn-sm rounded-pill', text: '<i class="fas fa-file-csv me-1"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-danger btn-sm rounded-pill', text: '<i class="fas fa-file-pdf me-1"></i> PDF' },
            { extend: 'print', className: 'btn btn-warning btn-sm rounded-pill', text: '<i class="fas fa-print me-1"></i> Print' },
            { extend: 'copy', className: 'btn btn-info btn-sm rounded-pill', text: '<i class="fas fa-copy me-1"></i> Copy' }
        ],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'class_name', name: 'class_name' },
            { data: 'section_name', name: 'section_name' },
            { data: 'subject_name', name: 'subject_name' },
            { data: 'start_datetime', name: 'start_datetime' },
            { data: 'end_datetime', name: 'end_datetime' },
            { data: 'status', name: 'status' },
            { data: 'completed_attempts', name: 'completed_attempts' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        pageLength: 25,
        order: [[ 5, 'desc' ]] // default order by start_datetime desc
    });
});
</script>
@endsection

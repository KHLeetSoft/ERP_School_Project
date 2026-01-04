@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0"><i class="bx bx-bar-chart-alt me-2 text-primary"></i> Exam Marks</h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.exams.marks.dashboard') }}" class="btn btn-info btn-sm"><i class="bx bx-bar-chart"></i> Dashboard</a>
                <a href="{{ route('admin.exams.marks.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Create</a>
                <a href="{{ route('admin.exams.marks.export') }}" class="btn btn-success btn-sm"><i class="fas fa-file-export me-1"></i> Export</a>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal"><i class="fas fa-file-import me-1"></i> Import</button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="p-3">
            <div class="row g-3 mb-2">
                <div class="col-md-3">
                    <label class="form-label">Filter by Exam</label>
                    <select id="filterExam" class="form-select">
                        <option value="">All</option>
                        @foreach($exams as $e)
                            <option value="{{ $e->id }}">{{ $e->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <input id="filterClass" class="form-control" placeholder="e.g. Class 10">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Student</label>
                    <input id="filterStudent" class="form-control" placeholder="Student name">
                </div>
            </div>
            <div class="table-responsive">
                <table id="markTable" class="table table-striped align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Exam</th>
                            <th>Class</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Percent</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Import Marks</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <form action="{{ route('admin.exams.marks.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Upload File (.xlsx, .csv, .txt)</label>
                            <input type="file" class="form-control" name="file" accept=".xlsx,.csv,.txt" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
    const table = $('#markTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.exams.marks.index') }}",
            data: function(d){
                d.exam_id = $('#filterExam').val();
                d.class_name = $('#filterClass').val();
                d.student_name = $('#filterStudent').val();
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
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false},
            {data: 'exam_title', name: 'exam_title'},
            {data: 'class_name', name: 'class_name'},
            {data: 'student_name', name: 'student_name'},
            {data: 'subject_name', name: 'subject_name'},
            {data: null, name: 'marks', render:(d)=> (d.obtained_marks||0)+'/'+(d.max_marks||0)},
            {data: 'percentage', name: 'percentage'},
            {data: 'grade', name: 'grade'},
            {data: 'status', name: 'status', render:(d)=> d==='published' ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-secondary">Draft</span>'},
            {data: 'actions', name: 'actions', orderable:false, searchable:false}
        ]
    });

    $('#filterExam, #filterClass, #filterStudent').on('change keyup', function(){ table.ajax.reload(); });

    $(document).on('click', '.delete-mark-btn', function() {
        let action = $(this).data('action');
        if(confirm('Delete this mark?')) {
            $.post(action, {_method:'DELETE', _token:'{{ csrf_token() }}'}, function(){
                table.ajax.reload();
            });
        }
    });
});
</script>
@endsection



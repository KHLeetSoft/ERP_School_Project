@extends('admin.layout.app')

@section('title', 'Timetable List')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">
        <i class="fas fa-calendar-alt me-2 text-primary"></i> Timetable Management
    </h4>
    <div class="btn-group" role="group">
        <a href="{{ route('admin.academic.timetable.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Timetable
        </a>
        <a href="{{ route('admin.academic.timetable.export', 'excel') }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-export me-1"></i> Export Excel
        </a>
        <a href="{{ route('admin.academic.timetable.export', 'pdf') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-import me-1"></i> Import Excel
        </button>
        
    </div>
</div>

    <div class="card-body">
        <table id="timetable-table" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                    <th width="120px">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.academic.timetable.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Timetable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="file" class="form-label">Select Excel File</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            Are you sure you want to delete this timetable?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    let deleteId = null;

    let table = $('#timetable-table').DataTable({
    processing: true,
    serverSide: true,
    order: [[1, 'asc']],
    ajax: {
        url: "{{ route('admin.academic.timetable.index') }}",
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
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'class_name', name: 'class.name' },
        { data: 'section_name', name: 'section.name' },
        { data: 'subject_name', name: 'subject.name' },
        { data: 'teacher_name', name: 'teacher.name' },
        { data: 'time_slot', name: 'time_slot', orderable: false, searchable: false },
        { data: 'status_badge', name: 'status', orderable: false, searchable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
    ]
});

    // Open delete modal
    $(document).on('click', '.delete-timetable-btn', function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    // Confirm delete
    $('#confirmDeleteBtn').click(function() {
        $.ajax({
            url: '/admin/academic/timetable/' + deleteId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                $('#deleteModal').modal('hide');
                table.ajax.reload();
            }
        });
    });
});
</script>
@endsection

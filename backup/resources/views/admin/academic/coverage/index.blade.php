@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-book-open me-2 text-primary"></i> Coverage Management
            </h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.academic.coverage.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Add Coverage
                </a>
                  <a href="{{ route('admin.academic.coverage.dashboard') }}" class="btn btn-secondary btn-sm me-2">
            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
        </a>
                <a href="{{ route('admin.academic.coverage.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i> Export CSV
                </a>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import me-1"></i> Import CSV
                </button>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- DataTable --}}
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle" id="coveragesTable" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this coverage? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let table = $('#coveragesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.academic.coverage.index') }}",
        dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +             
                '<"col-md-6 text-end"f>' +    
            '>' +
            '<"row mb-3"' +
                '<"col-12 text-end"B>' +      
            '>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            { extend: 'csv', className: 'btn btn-success btn-sm rounded-pill', text: '<i class="fas fa-file-csv me-1"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-danger btn-sm rounded-pill', text: '<i class="fas fa-file-pdf me-1"></i> PDF' },
            { extend: 'print', className: 'btn btn-warning btn-sm rounded-pill', text: '<i class="fas fa-print me-1"></i> Print' },
            { extend: 'copy', className: 'btn btn-info btn-sm rounded-pill', text: '<i class="fas fa-copy me-1"></i> Copy' },
        ],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'class_name', name: 'schoolClass.name'},
            {data: 'subject_name', name: 'subject.subject_name'},
            {data: 'teacher_name', name: 'teacher.name'},
            {data: 'title', name: 'title'},
            {data: 'date', name: 'date'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true
    });

    // Handle delete button click
    $(document).on('click', '.delete-coverage-btn', function() {
        let coverageId = $(this).data('id');
        let url = "{{ route('admin.academic.coverage.destroy', ':id') }}".replace(':id', coverageId);
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });
});
</script>
@endsection

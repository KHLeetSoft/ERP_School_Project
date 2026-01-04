@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 rounded-top">
        <h4 class="mb-0 text-dark fw-semibold">
            <i class="bx bx-file-pdf me-2 text-danger"></i> 
            Result Publications
        </h4>
        <a href="{{ route('admin.result-announcement.publications.create') }}" 
           class="btn btn-sm btn-outline-primary d-flex align-items-center px-3 rounded-pill shadow-sm">
            <i class="bx bx-plus me-1 fs-5"></i> 
            <span class="fw-semibold">New Publication</span>
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle" id="publicationsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Announcement</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Created By</th>
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
    $('#publicationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.result-announcement.publications.index") }}',
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
              {
                extend: 'csv',
                className: 'btn btn-success btn-sm rounded-pill',
                text: '<i class="fas fa-file-csv me-1"></i> CSV'
              },
              {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm rounded-pill',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF'
              },
              {
                extend: 'print',
                className: 'btn btn-warning btn-sm rounded-pill',
                text: '<i class="fas fa-print me-1"></i> Print'
                
              },
              {
                extend: 'copy',
                className: 'btn btn-info btn-sm rounded-pill',
                text: '<i class="fas fa-copy me-1"></i> Copy'
              }
            ],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'publication_title', name: 'publication_title' },
            { data: 'announcement_title', name: 'announcement_title' },
            { data: 'publication_type', name: 'publication_type' },
            { data: 'status', name: 'status' },
            { data: 'is_featured', name: 'is_featured' },
            { data: 'created_by', name: 'created_by' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true,
    });
});
</script>
@endsection

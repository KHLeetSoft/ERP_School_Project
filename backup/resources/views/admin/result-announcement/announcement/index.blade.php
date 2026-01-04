@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    
    <!-- Page Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <i class="bx bx-bullhorn text-primary fs-3 me-2"></i>
                <h4 class="mb-0 fw-bold">Result Announcements</h4>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <!-- Search -->
                <div class="input-group input-group-sm" style="max-width: 220px;">
                    <span class="input-group-text bg-light"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search announcement...">
                </div>
                <!-- Sort Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bx bx-sort"></i> Sort
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Latest</a></li>
                        <li><a class="dropdown-item" href="#">Oldest</a></li>
                        <li><a class="dropdown-item" href="#">Title A-Z</a></li>
                    </ul>
                </div>
                <!-- Export -->
                <div class="dropdown">
                    <button class="btn btn-outline-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bx bx-export me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Export as PDF</a></li>
                        <li><a class="dropdown-item" href="#">Export as Excel</a></li>
                        <li><a class="dropdown-item" href="#">Export as CSV</a></li>
                    </ul>
                </div>
                <!-- New Announcement -->
                <a href="{{ route('admin.result-announcement.announcement.create') }}" 
                   class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i> New Announcement
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle" id="announcementTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Exam</th>
                            <th>Status</th>
                            <th>create At</th>
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
    $('#announcementTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.result-announcement.announcement.index") }}',
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
            { data: 'title', name: 'title' },
            { data: 'announcement_type', name: 'announcement_type' },
            { data: 'exam_name', name: 'exam_name' },
            { data: 'status', name: 'status' },
            { data: 'date', name: 'date' }, 
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true,
    });
});
</script>
@endsection

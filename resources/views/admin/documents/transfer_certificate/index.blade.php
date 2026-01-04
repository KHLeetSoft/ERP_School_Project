@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0">
                <i class="bx bx-transfer me-2 text-primary"></i> Transfer Certificates
            </h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.documents.transfer-certificate.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Create
                </a>
                <a href="{{ route('admin.documents.transfer-certificate.dashboard') }}" class="btn btn-info btn-sm">
                  <i class="bx bx-bar-chart"></i> Dashboard
                </a>
                <a href="{{ route('admin.documents.transfer-certificate.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i> Export
                </a>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import me-1"></i> Import
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive p-3">
            <table id="tcTable" class="table table-striped align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>TC No.</th>
                        <th>Student</th>
                        <th>Admission No</th>
                        <th>Class</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Transfer Certificates</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.documents.transfer-certificate.import') }}" method="POST" enctype="multipart/form-data">
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
<!-- DataTables CSS & JS (Bootstrap 5 styling) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
    $('#tcTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.documents.transfer-certificate.index') }}",
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
            {data: 'tc_number', name: 'tc_number'},
            {data: 'student_name', name: 'student_name'},
            {data: 'admission_no', name: 'admission_no'},
            {data: 'class_name', name: 'class_name'},
            {data: 'issue_date', name: 'issue_date'},
            {data: 'status', name: 'status',
                render: function(data){
                    if(data === 'issued') return '<span class="badge bg-success">Issued</span>';
                    if(data === 'cancelled') return '<span class="badge bg-danger">Cancelled</span>';
                    return '<span class="badge bg-secondary">'+data+'</span>';
                }
            },
            {data: 'actions', name: 'actions', orderable:false, searchable:false}
        ]
    });

    // enable tooltips
    $(document).on('mouseenter', '[data-bs-toggle="tooltip"]', function() {
        var tooltip = new bootstrap.Tooltip(this);
        tooltip.show();
    });

    // Delete button event (with confirm)
    $(document).on('click', '.delete-tc-btn', function() {
        let action = $(this).data('action');
        if(confirm('Are you sure to delete this Transfer Certificate?')) {
            $.post(action, {_method:'DELETE', _token:'{{ csrf_token() }}'}, function(){
                $('#tcTable').DataTable().ajax.reload();
            });
        }
    });
});
</script>
@endsection

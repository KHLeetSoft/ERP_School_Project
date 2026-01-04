@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
<div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0">
            <i class="fas fa-file-alt me-2 text-primary"></i> Academic Reports
        </h4>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.academic.reports.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Create
            </a>
            <a href="{{ route('admin.academic.reports.dashboard') }}"  class="btn btn-info me-2">
                <i class="bx bx-bar-chart"></i> Dashboard
            </a>
            <a href="{{ route('admin.academic.reports.export') }}" class="btn btn-success btn-sm">
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
        <div class="card-body">
            <table id="reports-datatable" class="table table-striped table-hover align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash-alt me-2"></i> Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="fs-6">Are you sure you want to delete this report?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteReportForm" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill">Delete</button>
                </form>
            </div>
        </div>
    </div>
    </div>

<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-file-csv me-2"></i> Import Reports (XLSX/CSV)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-2">
                    <i class="fas fa-info-circle me-1"></i> Expected columns: 
                    <span class="fw-semibold">title, description, report_date, type, status</span>
                </p>
                <form id="importForm" method="POST" action="{{ route('admin.academic.reports.import') }}" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control mb-3" type="file" name="file" accept=".xlsx,.csv,.txt" required />
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary rounded-pill" id="importSubmitBtn">Import</button>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
<script>
$(function(){
    const table = $('#reports-datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{ url:'{{ route('admin.academic.reports.index') }}', type:'GET' },
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
        columns:[
            {data:'id', name:'id'},
            {data:'title', name:'title'},
            {data:'report_date', name:'report_date'},
            {data:'type', name:'type'},
            {
                data:'status',
                name:'status',
                render:function(data){
                    let badgeClass = data === 'published' ? 'success' : (data === 'draft' ? 'warning' : 'secondary');
                    return `<span class="badge bg-${badgeClass}">${data}</span>`;
                }
            },
            {data:'actions', name:'actions', orderable:false, searchable:false, className:'text-center'}
        ],
        order: [[0,'desc']]
    });

    $(document).on('click', '.delete-report-btn', function(){
        const action = $(this).data('action');
        $('#deleteReportForm').attr('action', action);
        new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
    });

    $('#importSubmitBtn').on('click', function(){ $('#importForm').submit(); });
});
</script>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">RFID Attendance</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.attendance.rfid.dashboard') }}" class="btn btn-sm btn-outline-secondary"><i class="bx bx-bar-chart"></i> Dashboard</a>
                <a href="{{ route('admin.attendance.rfid.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus"></i> New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('admin.attendance.rfid.export') }}" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Export</a>
                <form method="POST" action="{{ route('admin.attendance.rfid.import') }}" enctype="multipart/form-data" class="d-flex gap-2">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.csv" required style="max-width: 260px;">
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bx bx-upload"></i> Import</button>
                </form>
            </div>
            <table class="table table-striped align-middle" id="rfidTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Staff</th>
                        <th>Card UID</th>
                        <th>Direction</th>
                        <th>Device</th>
                        <th>Timestamp</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    $('#rfidTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.attendance.rfid.index') }}',
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
            { data: 'staff_name', name: 'staff_name' },
            { data: 'card_uid', name: 'card_uid' },
            { data: 'direction', name: 'direction' },
            { data: 'device_name', name: 'device_name' },
            { data: 'timestamp', name: 'timestamp' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        order: [[5, 'desc']],
        pageLength: 25,
        responsive: true,
    });
});
</script>
@endsection



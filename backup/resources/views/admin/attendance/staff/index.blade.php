@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Staff Attendance</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.attendance.staff.dashboard') }}" class="btn btn-sm btn-outline-secondary"><i class="bx bx-bar-chart"></i> Dashboard</a>
                <a href="{{ route('admin.attendance.staff.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus"></i> Mark Attendance</a>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <form class="d-flex gap-2" method="GET" action="{{ route('admin.attendance.staff.export') }}">
                    <input type="month" name="month" class="form-control form-control-sm" style="max-width: 200px;">
                    <button type="submit" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Export</button>
                </form>
                <form class="d-flex gap-2" method="POST" action="{{ route('admin.attendance.staff.import') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.csv" required style="max-width: 260px;">
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bx bx-upload"></i> Import</button>
                </form>
            </div>
            <table class="table table-striped align-middle" id="staffAttendanceTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Staff</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
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
    $('#staffAttendanceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.attendance.staff.index') }}',
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
            { data: 'attendance_date', name: 'attendance_date' },
            { data: 'status', name: 'status' },
            { data: 'remarks', name: 'remarks' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        order: [[2, 'desc']],
        pageLength: 25,
        responsive: true,
    });
});
</script>
@endsection



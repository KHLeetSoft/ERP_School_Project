@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Student Payments</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.finance.student-payments.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus"></i> New</a>
                <a href="{{ route('admin.finance.student-payments.dashboard') }}" class="btn btn-sm btn-outline-secondary"><i class="bx bx-bar-chart"></i> Dashboard</a>
                <a href="{{ route('admin.finance.student-payments.export') }}" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Export</a>
                <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bx bx-upload"></i> Import</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle" id="paymentsTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Reference</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="importModalLabel">Import Student Payments</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.finance.student-payments.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File (.xlsx, .csv) *</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
                        <div class="form-text">Headers: admission_no, student_name, payment_date, amount, method, status, reference, notes</div>
                    </div>
                    <div>
                        <a href="{{ route('admin.finance.student-payments.export') }}" class="small">Download sample</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark"><i class="bx bx-upload me-1"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    $('#paymentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.finance.student-payments.index') }}',
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
            { data: 'student', name: 'student.first_name' },
            { data: 'payment_date', name: 'payment_date' },
            { data: 'amount', name: 'amount' },
            { data: 'method', name: 'method' },
            { data: 'status', name: 'status' },
            { data: 'reference', name: 'reference' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        order: [[2, 'desc']],
        pageLength: 25,
        responsive: true,
    });
});
</script>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Payroll Management</h6>
        <div>
            <a href="{{ route('admin.hr.payroll.dashboard') }}" class="btn btn-sm btn-info me-2">
                <i class="bx bx-bar-chart-alt-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.hr.payroll.create') }}" class="btn btn-sm btn-primary me-2">
                <i class="bx bx-plus"></i> Create Payroll
            </a>
            <a href="{{ route('admin.hr.payroll.export') }}" class="btn btn-sm btn-success me-2">
                <i class="bx bx-download"></i> Export
            </a>
            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bx bx-upload"></i> Import
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-2">
                    <label for="month" class="form-label">Month</label>
                    <select name="month" id="month" class="form-select form-select-sm">
                        <option value="">All Months</option>
                        @foreach($months as $key => $month)
                            <option value="{{ $key }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="year" class="form-label">Year</label>
                    <select name="year" id="year" class="form-select form-select-sm">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="staff_id" class="form-label">Staff Member</label>
                    <select name="staff_id" id="staff_id" class="form-select form-select-sm">
                        <option value="">All Staff</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }} ({{ $member->employee_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        <i class="bx bx-filter-alt"></i> Filter
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="resetFilters()">
                        <i class="bx bx-refresh"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="payrollTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Period</th>
                            <th>Basic Salary</th>
                            <th>Gross Salary</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Payrolls</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.hr.payroll.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Supported formats: Excel (.xlsx, .xls) and CSV (.csv)</div>
                    </div>
                    <div class="alert alert-info">
                        <h6>Import Format:</h6>
                        <p class="mb-1">Required columns: Employee ID, Payroll Period, Basic Salary</p>
                        <p class="mb-1">Payroll Period format: "Month Year" (e.g., "January 2025")</p>
                        <p class="mb-0">Download sample template from Export section</p>
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#payrollTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.hr.payroll.index") }}',
            data: function(d) {
                d.month = $('#month').val();
                d.year = $('#year').val();
                d.status = $('#status').val();
                d.staff_id = $('#staff_id').val();
            }
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
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'staff_name', name: 'staff_name'},
            {data: 'payroll_period', name: 'payroll_period'},
            {data: 'basic_salary', name: 'basic_salary'},
            {data: 'gross_salary_formatted', name: 'gross_salary'},
            {data: 'net_salary_formatted', name: 'net_salary'},
            {data: 'status_badge', name: 'status'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[2, 'desc'], [1, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            processing: "Processing...",
            emptyTable: "No payroll records found",
            zeroRecords: "No matching payroll records found"
        }
    });

    // Filter form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        table.draw();
    });

    // Reset filters
    window.resetFilters = function() {
        $('#filterForm')[0].reset();
        table.draw();
    };

    // Handle status toggle
    $(document).on('click', '.toggle-status', function() {
        var url = $(this).data('url');
        var currentStatus = $(this).data('status');
        
        // Show status selection modal or dropdown
        var newStatus = prompt('Enter new status (pending/approved/paid/rejected):', currentStatus);
        
        if (newStatus && ['pending', 'approved', 'paid', 'rejected'].includes(newStatus.toLowerCase())) {
            $.ajax({
                url: url,
                method: 'PATCH',
                data: {
                    status: newStatus.toLowerCase(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.draw();
                    } else {
                        toastr.error(response.error || 'Status update failed');
                    }
                },
                error: function(xhr) {
                    var error = xhr.responseJSON?.error || 'Status update failed';
                    toastr.error(error);
                }
            });
        }
    });

    // Handle delete
    $(document).on('click', '.delete-payroll', function() {
        var url = $(this).data('url');
        
        if (confirm('Are you sure you want to delete this payroll? This action cannot be undone.')) {
            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success('Payroll deleted successfully');
                    table.draw();
                },
                error: function(xhr) {
                    var error = xhr.responseJSON?.error || 'Delete failed';
                    toastr.error(error);
                }
            });
        }
    });
});
</script>
@endsection

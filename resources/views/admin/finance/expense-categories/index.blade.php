@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Expense Categories</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.finance.expense-categories.dashboard') }}" class="btn btn-sm btn-info">
                    <i class="bx bx-bar-chart"></i> Dashboard
                </a>
                <a href="{{ route('admin.finance.expense-categories.create') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus"></i> New Category
                </a>
                <a href="{{ route('admin.finance.expense-categories.export') }}" class="btn btn-sm btn-success">
                    <i class="bx bx-download"></i> Export
                </a>
                <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bx bx-upload"></i> Import
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle" id="expenseCategoriesTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Code</th>
                        <th>Budget Limit</th>
                        <th>Total Expenses</th>
                        <th>Monthly Expenses</th>
                        <th>Budget Utilization</th>
                        <th>Status</th>
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
                <h6 class="modal-title" id="importModalLabel">Import Expense Categories</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.finance.expense-categories.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File (.xlsx, .csv) *</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
                        <div class="form-text">
                            <strong>Required columns:</strong> name, code, description, color, icon, budget_limit, budget_period, status<br>
                            <strong>Optional:</strong> All other fields will use defaults
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <strong>Sample data format:</strong><br>
                        name, code, description, color, icon, budget_limit, budget_period, status<br>
                        "Utilities", "UTIL", "Electricity bills", "#3b82f6", "bx bx-bulb", 50000, "monthly", "Active"
                    </div>
                    <div>
                        <a href="{{ route('admin.finance.expense-categories.export') }}" class="small">Download sample file</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">
                        <i class="bx bx-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    $('#expenseCategoriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.finance.expense-categories.index') }}',
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
            { 
                data: 'name', 
                name: 'name',
                render: function(data, type, row) {
                    return `<div class="d-flex align-items-center">
                        <div class="rounded-circle me-2" style="width: 20px; height: 20px; background-color: ${row.color}"></div>
                        <span>${data}</span>
                    </div>`;
                }
            },
            { data: 'code', name: 'code' },
            { 
                data: 'budget_limit', 
                name: 'budget_limit',
                render: function(data, type, row) {
                    return data ? `₹${parseFloat(data).toLocaleString()}` : '-';
                }
            },
            { data: 'total_expenses', name: 'total_expenses' },
            { data: 'monthly_expenses', name: 'monthly_expenses' },
            { data: 'budget_utilization', name: 'budget_utilization' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            search: "Search categories:",
            lengthMenu: "Show _MENU_ categories per page",
            info: "Showing _START_ to _END_ of _TOTAL_ categories",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});
</script>
@endsection 
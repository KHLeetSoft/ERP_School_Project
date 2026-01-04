@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Fee Structure Management</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.finance.fee-structure.dashboard') }}" class="btn btn-sm btn-info">
                    <i class="bx bx-bar-chart"></i> Dashboard
                </a>
                <a href="{{ route('admin.finance.fee-structure.create') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus"></i> New Fee Structure
                </a>
                <a href="{{ route('admin.finance.fee-structure.export') }}" class="btn btn-sm btn-success">
                    <i class="bx bx-download"></i> Export
                </a>
                <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bx bx-upload"></i> Import
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <select class="form-select" id="classFilter">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Academic Year</label>
                    <select class="form-select" id="yearFilter">
                        <option value="">All Years</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2022-2023">2022-2023</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fee Type</label>
                    <select class="form-select" id="feeTypeFilter">
                        <option value="">All Types</option>
                        <option value="Tuition Fee">Tuition Fee</option>
                        <option value="Transport Fee">Transport Fee</option>
                        <option value="Library Fee">Library Fee</option>
                        <option value="Laboratory Fee">Laboratory Fee</option>
                        <option value="Sports Fee">Sports Fee</option>
                        <option value="Computer Fee">Computer Fee</option>
                        <option value="Examination Fee">Examination Fee</option>
                        <option value="Development Fee">Development Fee</option>
                        <option value="Admission Fee">Admission Fee</option>
                        <option value="Other Fee">Other Fee</option>
                    </select>
                </div>
            </div>

            <table class="table table-striped align-middle" id="feeStructureTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Academic Year</th>
                        <th>Fee Type</th>
                        <th>Amount</th>
                        <th>Frequency</th>
                        <th>Due Date</th>
                        <th>Late Fee</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
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
                    <h6 class="modal-title">Import Fee Structures</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.finance.fee-structure.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Excel/CSV File</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
                        </div>
                        <div class="alert alert-info">
                            <strong>Required Columns:</strong><br>
                            class, academic_year, fee_type, amount, frequency, due_date, late_fee, discount_applicable, max_discount, description, status
                        </div>
                        <div class="alert alert-warning">
                            <strong>Note:</strong> Class names must match exactly with existing classes in the system.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-dark" type="submit">
                            <i class="bx bx-upload"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
    $(function(){
        const table = $('#feeStructureTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.finance.fee-structure.index") }}',
                data: function(d) {
                    d.class_id = $('#classFilter').val();
                    d.academic_year = $('#yearFilter').val();
                    d.status = $('#statusFilter').val();
                    d.fee_type = $('#feeTypeFilter').val();
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
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'class.name', name: 'class.name' },
                { data: 'academic_year', name: 'academic_year' },
                { data: 'fee_type', name: 'fee_type' },
                { data: 'amount', name: 'amount' },
                { data: 'frequency', name: 'frequency' },
                { data: 'due_date', name: 'due_date' },
                { data: 'late_fee', name: 'late_fee' },
                { data: 'max_discount', name: 'max_discount' },
                { data: 'is_active', name: 'is_active' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            order: [[1, 'asc'], [2, 'desc'], [3, 'asc']],
            pageLength: 25,
            responsive: true
        });

        // Filter change handlers
        $('#classFilter, #yearFilter, #statusFilter, #feeTypeFilter').on('change', function() {
            table.ajax.reload();
        });
    });
})();
</script>
@endsection

@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Staff Management</h6>
        <div>
            
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select class="form-select" id="departmentFilter">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Designation</label>
                    <select class="form-select" id="designationFilter">
                        <option value="">All Designations</option>
                        @foreach($designations as $desig)
                            <option value="{{ $desig }}">{{ $desig }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Employment Type</label>
                    <select class="form-select" id="employmentTypeFilter">
                        <option value="">All Types</option>
                        @foreach($employmentTypes as $key => $type)
                            <option value="{{ $key }}">{{ $type }}</option>
                        @endforeach
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
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Staff List</h6>
            <div>
                <button type="button" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bx bx-import"></i> Import
                </button>
                <a href="{{ route('admin.hr.staff.export') }}" class="btn btn-sm btn-warning">
                    <i class="bx bx-export"></i> Export
                </a>
                <a href="{{ route('admin.hr.staff.dashboard') }}" class="btn btn-sm btn-info me-2">
                    <i class="bx bx-bar-chart-alt-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.hr.staff.create') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus"></i> Add Staff
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="staffTable">
                    <thead class ="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Salary</th>
                            <th>Employment Type</th>
                            <th>Joining Date</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Staff Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hr.staff.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select File</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.csv" required>
                        <div class="form-text">Supported formats: Excel (.xlsx), CSV (.csv)</div>
                    </div>
                    <div class="mb-3">
                        <a href="#" class="text-decoration-none" onclick="downloadTemplate()">
                            <i class="bx bx-download"></i> Download Template
                        </a>
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
    var table = $('#staffTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.hr.staff.index') }}",
            data: function(d) {
                d.department = $('#departmentFilter').val();
                d.designation = $('#designationFilter').val();
                d.employment_type = $('#employmentTypeFilter').val();
                d.status = $('#statusFilter').val();
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
            {data: 'employee_id', name: 'employee_id'},
            {data: 'full_name', name: 'full_name'},
            {data: 'department', name: 'department'},
            {data: 'designation', name: 'designation'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'salary', name: 'salary'},
            {data: 'employment_type', name: 'employment_type'},
            {data: 'joining_date', name: 'joining_date'},
            {data: 'experience', name: 'experience'},
            {data: 'status', name: 'status'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true
    });

    // Filter change events
    $('#departmentFilter, #designationFilter, #employmentTypeFilter, #statusFilter').change(function() {
        table.draw();
    });

    // Handle status toggle
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var row = $(this).closest('tr');
        
        $.ajax({
            url: url,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update the status badge
                    var statusCell = row.find('td:eq(10)');
                    if (response.is_active) {
                        statusCell.html('<span class="badge bg-success">Active</span>');
                    } else {
                        statusCell.html('<span class="badge bg-danger">Inactive</span>');
                    }
                    
                    // Show success message
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('Error updating status');
            }
        });
    });

    // Handle delete
    $(document).on('click', '.delete-staff', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        
        if (confirm('Are you sure you want to delete this staff member?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.draw();
                    toastr.success('Staff member deleted successfully');
                },
                error: function() {
                    toastr.error('Error deleting staff member');
                }
            });
        }
    });
});

function downloadTemplate() {
    // Create a simple template structure
    var template = [
        ['Employee ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Date of Birth', 'Gender', 'Address', 'City', 'State', 'Country', 'Postal Code', 'Designation', 'Department', 'Joining Date', 'Contract End Date', 'Salary', 'Employment Type', 'Status', 'Emergency Contact Name', 'Emergency Contact Phone', 'Bank Name', 'Bank Account Number', 'IFSC Code', 'PAN Number', 'Aadhar Number'],
        ['EMP20250001', 'John', 'Doe', 'john.doe@school.com', '+919876543210', '15/06/1985', 'Male', '123 Main St', 'Mumbai', 'Maharashtra', 'India', '400001', 'Teacher', 'Academic', '01/06/2020', '', '35000', 'full_time', 'Active', 'Jane Doe', '+919876543211', 'SBI', '1234567890', 'SBIN0001234', 'ABCDE1234F', '123456789012']
    ];
    
    var csvContent = "data:text/csv;charset=utf-8,";
    template.forEach(function(rowArray) {
        var row = rowArray.map(function(field) {
            return '"' + field + '"';
        }).join(",");
        csvContent += row + "\r\n";
    });
    
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "staff_import_template.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection

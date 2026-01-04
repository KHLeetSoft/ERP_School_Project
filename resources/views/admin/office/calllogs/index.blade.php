@extends('admin.layout.app')

@section('title', 'Call Logs')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Call Logs</h1>
        <div class="d-none d-sm-inline-block">
            <a href="{{ route('admin.office.calllogs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Call Log
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Calls</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-calls">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-phone fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Calls</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="today-calls">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Week</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="week-calls">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="month-calls">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Call Logs Management</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm" onclick="exportCallLogs()">
                    <i class="fas fa-download me-1"></i>Export
                </button>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-upload me-1"></i>Import
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="purpose_filter" class="form-label">Purpose</label>
                    <select class="form-select" id="purpose_filter">
                        <option value="">All Purposes</option>
                        <option value="Inquiry">Inquiry</option>
                        <option value="Complaint">Complaint</option>
                        <option value="Support">Support</option>
                        <option value="General">General</option>
                        <option value="Admission">Admission</option>
                        <option value="Fee">Fee</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date_to">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button class="btn btn-primary" onclick="filterCallLogs()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <button class="btn btn-secondary" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="callLogsTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Caller Name</th>
                            <th>Purpose</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Duration</th>
                            <th>Note</th>
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
                <h5 class="modal-title" id="importModalLabel">Import Call Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.office.calllogs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.csv" required>
                        <div class="form-text">Supported formats: Excel (.xlsx) and CSV (.csv)</div>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this call log? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#callLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.office.calllogs.index') }}",
            data: function(d) {
                d.purpose = $('#purpose_filter').val();
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                alert('Error loading data: ' + error);
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'caller_name', name: 'caller_name'},
            {data: 'purpose', name: 'purpose'},
            {data: 'phone', name: 'phone'},
            {data: 'date', name: 'date'},
            {data: 'time', name: 'time'},
            {data: 'duration', name: 'duration'},
            {data: 'note', name: 'note'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Filter function
    window.filterCallLogs = function() {
        table.draw();
    };

    // Clear filters function
    window.clearFilters = function() {
        $('#purpose_filter').val('');
        $('#date_from').val('');
        $('#date_to').val('');
        table.draw();
    };

    // Export function
    window.exportCallLogs = function() {
        window.location.href = "{{ route('admin.office.calllogs.export') }}";
    };

    // Delete functionality
    $(document).on('click', '.delete-calllog-btn', function() {
        var callLogId = $(this).data('id');
        $('#confirmDelete').data('id', callLogId);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var callLogId = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.office.calllogs.destroy', '') }}/" + callLogId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                table.draw();
                toastr.success('Call log deleted successfully');
            },
            error: function() {
                toastr.error('Error deleting call log');
            }
        });
    });

    // Load statistics
    loadStatistics();
});

function loadStatistics() {
    $.ajax({
        url: "{{ route('admin.office.calllogs.index') }}",
        type: 'GET',
        data: { stats: true },
        success: function(response) {
            $('#total-calls').text(response.total || 0);
            $('#today-calls').text(response.today || 0);
            $('#week-calls').text(response.week || 0);
            $('#month-calls').text(response.month || 0);
        }
    });
}
</script>
@endsection
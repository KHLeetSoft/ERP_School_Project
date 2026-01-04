@extends('admin.layout.app')

@section('title', 'Parent Communications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Parent Communications</h4>
                    <div>
                        <a href="{{ route('admin.parents.communication.create') }}" class="btn btn-primary me-2">
                            <i class="bx bx-plus"></i> New Communication
                        </a>
                        <a href="{{ route('admin.parents.communication.dashboard') }}" class="btn btn-info me-2">
                            <i class="bx bx-bar-chart"></i> Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select class="form-select" id="filter_type">
                                <option value="">All Types</option>
                                @foreach($communicationTypes as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="filter_status">
                                <option value="">All Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Priority</label>
                            <select class="form-select" id="filter_priority">
                                <option value="">All Priority</option>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="filter_category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" id="filter_date_from">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" id="filter_date_to">
                        </div>
                    </div>

                    <!-- Import/Export -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('admin.parents.communication.import') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bx bx-import"></i> Import
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.parents.communication.export') }}" class="btn btn-warning">
                                <i class="bx bx-export"></i> Export
                            </a>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" id="select-all" class="form-check-input me-2">
                                <label for="select-all" class="form-check-label me-3">Select All</label>
                                
                                <select id="bulk-action" class="form-select me-2" style="width: auto;">
                                    <option value="">Bulk Actions</option>
                                    <option value="delete">Delete Selected</option>
                                    <option value="update_status">Update Status</option>
                                    <option value="update_priority">Update Priority</option>
                                </select>
                                
                                <button id="apply-bulk-action" class="btn btn-secondary" disabled>Apply</button>
                                
                                <span id="selected-count" class="ms-3 text-muted">0 items selected</span>
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table id="communications-table" class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="20">
                                        <input type="checkbox" class="select-all-checkbox">
                                    </th>
                                    <th>#</th>
                                    <th>Parent</th>
                                    <th>Student</th>
                                    <th>Type</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Sent Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bulk-action-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-bulk-action">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let table;
let selectedIds = [];

$(document).ready(function(){
    // Initialize DataTable
    table = $('#communications-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.parents.communication.index') }}",
            data: function(d) {
                d.communication_type = $('#filter_type').val();
                d.status = $('#filter_status').val();
                d.priority = $('#filter_priority').val();
                d.category = $('#filter_category').val();
                d.date_from = $('#filter_date_from').val();
                d.date_to = $('#filter_date_to').val();
            }
        },     dom:
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
            { 
                data: null, 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return '<input type="checkbox" class="row-checkbox" value="' + row.id + '">';
                }
            },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'parent_name', name: 'parent_name' },
            { data: 'student_name', name: 'student_name' },
            { data: 'communication_type_badge', name: 'communication_type' },
            { data: 'subject', name: 'subject' },
            { data: 'status_badge', name: 'status' },
            { data: 'priority_badge', name: 'priority' },
            { data: 'sent_date', name: 'sent_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
        order: [[8, 'desc']], // Sort by sent date descending
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // Filter change events
    $('#filter_type, #filter_status, #filter_priority, #filter_category, #filter_date_from, #filter_date_to').on('change', function() {
        table.ajax.reload();
    });

    // Select all functionality
    $('.select-all-checkbox, #select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.row-checkbox, .select-all-checkbox').prop('checked', isChecked);
        updateSelectedCount();
    });

    // Individual row selection
    $(document).on('change', '.row-checkbox', function() {
        updateSelectedCount();
        updateSelectAllState();
    });

    // Bulk action handling
    $('#apply-bulk-action').on('click', function() {
        const action = $('#bulk-action').val();
        if (!action || selectedIds.length === 0) return;

        showBulkActionModal(action);
    });

    // Delete communication
    $(document).on('click', '.delete-communication', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this communication?')) {
            deleteCommunication(id);
        }
    });
});

function updateSelectedCount() {
    selectedIds = $('.row-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    $('#selected-count').text(selectedIds.length + ' items selected');
    $('#apply-bulk-action').prop('disabled', selectedIds.length === 0);
}

function updateSelectAllState() {
    const totalCheckboxes = $('.row-checkbox').length;
    const checkedCheckboxes = $('.row-checkbox:checked').length;
    
    if (checkedCheckboxes === 0) {
        $('.select-all-checkbox, #select-all').prop('indeterminate', false).prop('checked', false);
    } else if (checkedCheckboxes === totalCheckboxes) {
        $('.select-all-checkbox, #select-all').prop('indeterminate', false).prop('checked', true);
    } else {
        $('.select-all-checkbox, #select-all').prop('indeterminate', true);
    }
}

function showBulkActionModal(action) {
    let content = '';
    
    switch(action) {
        case 'delete':
            content = '<p>Are you sure you want to delete <strong>' + selectedIds.length + '</strong> selected communications?</p>';
            break;
        case 'update_status':
            content = `
                <p>Update status for <strong>${selectedIds.length}</strong> selected communications:</p>
                <select class="form-select" id="bulk-status">
                    <option value="sent">Sent</option>
                    <option value="delivered">Delivered</option>
                    <option value="read">Read</option>
                    <option value="failed">Failed</option>
                </select>
            `;
            break;
        case 'update_priority':
            content = `
                <p>Update priority for <strong>${selectedIds.length}</strong> selected communications:</p>
                <select class="form-select" id="bulk-priority">
                    <option value="low">Low</option>
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            `;
            break;
    }
    
    $('#bulk-action-content').html(content);
    $('#bulkActionModal').modal('show');
}

$('#confirm-bulk-action').on('click', function() {
    const action = $('#bulk-action').val();
    let data = {
        action: action,
        ids: selectedIds,
        _token: '{{ csrf_token() }}'
    };
    
    // Add additional data based on action
    if (action === 'update_status') {
        data.status = $('#bulk-status').val();
    } else if (action === 'update_priority') {
        data.priority = $('#bulk-priority').val();
    }
    
    $.ajax({
        url: '{{ route("admin.parents.communication.bulk-action") }}',
        method: 'POST',
        data: data,
        success: function(response) {
            $('#bulkActionModal').modal('hide');
            showAlert('success', response.message);
            table.ajax.reload();
            selectedIds = [];
            updateSelectedCount();
            $('.row-checkbox, .select-all-checkbox, #select-all').prop('checked', false);
        },
        error: function(xhr) {
            showAlert('error', 'An error occurred while processing the request.');
        }
    });
});

function deleteCommunication(id) {
    $.ajax({
        url: '{{ url("admin/parents/communication") }}/' + id,
        method: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            showAlert('success', response.message);
            table.ajax.reload();
        },
        error: function(xhr) {
            showAlert('error', 'An error occurred while deleting the communication.');
        }
    });
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.card-body').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection

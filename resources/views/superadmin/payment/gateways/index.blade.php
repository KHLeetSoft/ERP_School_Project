@extends('superadmin.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bx bx-credit-card me-2"></i>Payment Gateways
                    </h1>
                    <p class="text-muted mb-0">Manage payment gateways and their configurations</p>
                </div>
                <a href="{{ route('superadmin.payment.gateways.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-2"></i>Add New Gateway
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Total Gateways</h5>
                            <h2 class="mb-0 text-white" id="totalGateways">0</h2>
                        </div>
                        <i class="bx bx-credit-card display-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Active Gateways</h5>
                            <h2 class="mb-0 text-white" id="activeGateways">0</h2>
                        </div>
                        <i class="bx bx-check-circle display-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Inactive Gateways</h5>
                            <h2 class="mb-0 text-white" id="inactiveGateways">0</h2>
                        </div>
                        <i class="bx bx-pause-circle display-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Schools Count</h5>
                            <h2 class="mb-0 text-white" id="schoolsCount">0</h2>
                        </div>
                        <i class="bx bx-building display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-4">
            <select class="form-select" id="schoolFilter">
                <option value="">All Schools</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-outline-secondary" onclick="refreshTable()">
                <i class="bx bx-refresh me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="gateways-table">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold text-dark">#</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-credit-card me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Gateway Info</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-cog me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Configuration</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-building me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Schools</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-calendar me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Created</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bx bx-cog me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Actions</span>
                                </div>
                            </th>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bx bx-trash me-2"></i>Delete Gateway
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bx bx-error-circle display-1 text-danger mb-3"></i>
                    <h5>Are you sure you want to delete this gateway?</h5>
                    <p class="text-muted">This action cannot be undone. All associated data will be permanently removed.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bx bx-trash me-2"></i>Delete Gateway
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#gateways-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('superadmin.payment.gateways.index') }}",
            data: function(d) {
                d.school_id = $('#schoolFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'gateway_info', name: 'name', orderable: false },
            { data: 'configuration', name: 'provider', orderable: false },
            { data: 'schools_count', name: 'schools_count', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[4, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        }
    });

    // Load statistics
    loadStats();

    // Filter change events
    $('#schoolFilter, #statusFilter').on('change', function() {
        table.draw();
        loadStats();
    });

    // Toggle status
    $(document).on('click', '.toggle-status-btn', function() {
        var gatewayId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: "{{ route('superadmin.payment.gateways.toggle-status', '') }}/" + gatewayId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    table.draw();
                    loadStats();
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('An error occurred while updating status', 'error');
            }
        });
    });

    // Delete gateway
    var deleteGatewayId;
    $(document).on('click', '.delete-gateway-btn', function() {
        deleteGatewayId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        if (deleteGatewayId) {
            $.ajax({
                url: "{{ route('superadmin.payment.gateways.destroy', '') }}/" + deleteGatewayId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
                        $('#deleteModal').modal('hide');
                        table.draw();
                        loadStats();
                    } else {
                        showAlert(response.message, 'error');
                    }
                },
                error: function() {
                    showAlert('An error occurred while deleting gateway', 'error');
                }
            });
        }
    });

    // Test connection
    $(document).on('click', '.test-connection-btn', function() {
        var gatewayId = $(this).data('id');
        var button = $(this);
        
        button.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Testing...');
        
        $.ajax({
            url: "{{ route('superadmin.payment.gateways.test-connection', '') }}/" + gatewayId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('Connection test failed', 'error');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="bx bx-wifi me-2"></i>Test Connection');
            }
        });
    });
});

function loadStats() {
    $.ajax({
        url: "{{ route('superadmin.payment.gateways.index') }}",
        data: {
            stats_only: true,
            school_id: $('#schoolFilter').val(),
            status: $('#statusFilter').val()
        },
        success: function(response) {
            $('#totalGateways').text(response.total || 0);
            $('#activeGateways').text(response.active || 0);
            $('#inactiveGateways').text(response.inactive || 0);
            $('#schoolsCount').text(response.schools || 0);
        }
    });
}

function refreshTable() {
    $('#gateways-table').DataTable().ajax.reload();
    loadStats();
}

function showAlert(message, type) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>';
    
    $('.container-fluid').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection

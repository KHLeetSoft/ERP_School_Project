@extends('superadmin.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bx bx-package me-2"></i>Payment Plans
                    </h1>
                    <p class="text-muted mb-0">Manage subscription plans and pricing</p>
                </div>
                <a href="{{ route('superadmin.payment.plans.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-2"></i>Add New Plan
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
                            <h5 class="card-title text-white mb-0">Total Plans</h5>
                            <h2 class="mb-0 text-white" id="totalPlans">0</h2>
                        </div>
                        <i class="bx bx-package display-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Active Plans</h5>
                            <h2 class="mb-0 text-white" id="activePlans">0</h2>
                        </div>
                        <i class="bx bx-check-circle display-4"></i>
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
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Total Revenue</h5>
                            <h2 class="mb-0 text-white" id="totalRevenue">₹0</h2>
                        </div>
                        <i class="bx bx-rupee display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-3">
            <select class="form-select" id="gatewayFilter">
                <option value="">All Gateways</option>
                @foreach($gateways as $gateway)
                    <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="priceTypeFilter">
                <option value="">All Price Types</option>
                <option value="fixed">Fixed Price</option>
                <option value="recurring">Recurring</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-outline-secondary" onclick="refreshTable()">
                <i class="bx bx-refresh me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="plans-table">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold text-dark">#</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-package me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Plan Info</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-rupee me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Pricing</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-credit-card me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Gateway</span>
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
                    <i class="bx bx-trash me-2"></i>Delete Plan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bx bx-error-circle display-1 text-danger mb-3"></i>
                    <h5>Are you sure you want to delete this plan?</h5>
                    <p class="text-muted">This action cannot be undone. All associated data will be permanently removed.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bx bx-trash me-2"></i>Delete Plan
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
    var table = $('#plans-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('superadmin.payment.plans.index') }}",
            data: function(d) {
                d.gateway_id = $('#gatewayFilter').val();
                d.price_type = $('#priceTypeFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'plan_info', name: 'name', orderable: false },
            { data: 'pricing', name: 'price', orderable: false },
            { data: 'gateway_info', name: 'gateway.name', orderable: false },
            { data: 'schools_count', name: 'schools_count', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[5, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        }
    });

    // Load statistics
    loadStats();

    // Filter change events
    $('#gatewayFilter, #priceTypeFilter, #statusFilter').on('change', function() {
        table.draw();
        loadStats();
    });

    // Toggle status
    $(document).on('click', '.toggle-status-btn', function() {
        var planId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: "{{ route('superadmin.payment.plans.toggle-status', '') }}/" + planId,
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

    // Delete plan
    var deletePlanId;
    $(document).on('click', '.delete-plan-btn', function() {
        deletePlanId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        if (deletePlanId) {
            $.ajax({
                url: "{{ route('superadmin.payment.plans.destroy', '') }}/" + deletePlanId,
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
                    showAlert('An error occurred while deleting plan', 'error');
                }
            });
        }
    });
});

function loadStats() {
    $.ajax({
        url: "{{ route('superadmin.payment.plans.index') }}",
        data: {
            stats_only: true,
            gateway_id: $('#gatewayFilter').val(),
            price_type: $('#priceTypeFilter').val(),
            status: $('#statusFilter').val()
        },
        success: function(response) {
            $('#totalPlans').text(response.total || 0);
            $('#activePlans').text(response.active || 0);
            $('#schoolsCount').text(response.schools || 0);
            $('#totalRevenue').text('₹' + (response.revenue || 0).toLocaleString());
        }
    });
}

function refreshTable() {
    $('#plans-table').DataTable().ajax.reload();
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

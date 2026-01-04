@extends('superadmin.app')

@section('title', 'User Role Assignment')

@section('content')
<div class="container-fluid p-0">
    <!-- Enhanced Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-success text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-user-plus me-3"></i>User Role Assignment
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Assign roles and manage user permissions</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-lg px-4 py-3 rounded-pill shadow-sm" onclick="bulkAssignModal()">
                                <i class="fas fa-users me-2"></i>Bulk Assign
                            </button>
                            <button class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill shadow-sm" onclick="refreshTable()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Background Pattern -->
                <div class="position-absolute top-0 end-0 w-100 h-100" style="background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"userPattern\" width=\"30\" height=\"30\" patternUnits=\"userSpaceOnUse\"><circle cx=\"15\" cy=\"15\" r=\"3\" fill=\"white\" opacity=\"0.1\"/><circle cx=\"5\" cy=\"25\" r=\"2\" fill=\"white\" opacity=\"0.1\"/><circle cx=\"25\" cy=\"5\" r=\"2\" fill=\"white\" opacity=\"0.1\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23userPattern)\"/></svg>'); pointer-events: none;"></div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-primary fw-bold small mb-1">Total Users</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="totalUsers">0</div>
                            <div class="text-primary small">
                                <i class="fas fa-users me-1"></i>System Users
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.05) 100%); pointer-events: none;"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-success fw-bold small mb-1">Active Users</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="activeUsers">0</div>
                            <div class="text-success small">
                                <i class="fas fa-check-circle me-1"></i>Currently Active
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(25, 135, 84, 0.05) 100%); pointer-events: none;"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-warning fw-bold small mb-1">With Roles</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="usersWithRoles">0</div>
                            <div class="text-warning small">
                                <i class="fas fa-user-check me-1"></i>Role Assigned
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-check fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%); pointer-events: none;"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-info fw-bold small mb-1">Without Roles</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="usersWithoutRoles">0</div>
                            <div class="text-info small">
                                <i class="fas fa-user-times me-1"></i>No Role
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-times fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.05) 100%); pointer-events: none;"></div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted">Status Filter</label>
                            <select class="form-select form-select-lg" id="filterStatus">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted">Role Filter</label>
                            <select class="form-select form-select-lg" id="filterRole">
                                <option value="">All Roles</option>
                                <option value="with_roles">With Roles</option>
                                <option value="without_roles">Without Roles</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Search Users</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="search" id="globalSearch" class="form-control border-0 bg-light" placeholder="Search by name, email, or school...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Users & Roles Overview</h5>
                            <p class="text-muted mb-0">Manage user role assignments and permissions</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshTable()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="exportData()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="users-datatable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold text-muted">#</th>
                                    <th class="border-0 fw-semibold text-muted">User Information</th>
                                    <th class="border-0 fw-semibold text-muted">School</th>
                                    <th class="border-0 fw-semibold text-muted">Roles</th>
                                    <th class="border-0 fw-semibold text-muted">Permissions</th>
                                    <th class="border-0 fw-semibold text-muted">Status</th>
                                    <th class="border-0 fw-semibold text-muted text-center">Actions</th>
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
    </div>
</div>

<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1" aria-labelledby="bulkAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="bulkAssignModalLabel">
                    <i class="fas fa-users me-2"></i>Bulk Role Assignment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bulkAssignForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Select Users</label>
                            <select class="form-select" id="bulkUsers" multiple>
                                <!-- Users will be loaded via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Select Roles</label>
                            <select class="form-select" id="bulkRoles" multiple>
                                <!-- Roles will be loaded via AJAX -->
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processBulkAssign()">
                    <i class="fas fa-save me-1"></i>Assign Roles
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let table;

    function initDataTable() {
        if (table) {
            table.destroy();
        }

        table = $('#users-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("superadmin.user-roles.index") }}',
                data: function(d) {
                    d.status = $('#filterStatus').val();
                    d.role_filter = $('#filterRole').val();
                }
            },
            dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center'
                },
                { 
                    data: 'user_info', 
                    name: 'user_info',
                    className: 'fw-semibold'
                },
                { 
                    data: 'school_name', 
                    name: 'school_name',
                    className: 'text-center'
                },
                { 
                    data: 'roles', 
                    name: 'roles', 
                    orderable: false,
                    className: 'text-center'
                },
                { 
                    data: 'permissions_count', 
                    name: 'permissions_count', 
                    orderable: false,
                    className: 'text-center'
                },
                { 
                    data: 'status', 
                    name: 'status',
                    className: 'text-center'
                },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [[1, 'asc']],
            pageLength: 25,
            language: {
                processing: '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><br>Loading users...</div>',
                emptyTable: '<div class="text-center py-5"><i class="fas fa-users fa-3x text-muted mb-3"></i><h5 class="text-muted">No users found</h5><p class="text-muted">No users available for role assignment</p></div>',
                zeroRecords: '<div class="text-center py-5"><i class="fas fa-search fa-3x text-muted mb-3"></i><h5 class="text-muted">No matching users found</h5><p class="text-muted">Try adjusting your search criteria</p></div>'
            },
            drawCallback: function() {
                // Add hover effects to table rows
                $('#users-datatable tbody tr').hover(
                    function() {
                        $(this).addClass('table-active');
                    },
                    function() {
                        $(this).removeClass('table-active');
                    }
                );
            }
        });
    }

    // Initialize DataTable
    initDataTable();

    // Filter change events
    $('#filterStatus, #filterRole').on('change', function() {
        table.draw();
        updateStatistics();
    });

    // Global search
    $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Load statistics
    function updateStatistics() {
        $.ajax({
            url: '{{ route("superadmin.user-roles.index") }}',
            data: { stats_only: true },
            success: function(data) {
                $('#totalUsers').text(data.total || 0);
                $('#activeUsers').text(data.active || 0);
                $('#usersWithRoles').text(data.with_roles || 0);
                $('#usersWithoutRoles').text(data.without_roles || 0);
            }
        });
    }

    // Refresh table
    window.refreshTable = function() {
        table.draw();
        updateStatistics();
        showAlert('success', 'Table refreshed successfully!');
    };

    // Export data
    window.exportData = function() {
        showAlert('info', 'Export functionality will be implemented soon!');
    };

    // Bulk assign modal
    window.bulkAssignModal = function() {
        $('#bulkAssignModal').modal('show');
        loadBulkAssignData();
    };

    // Load data for bulk assign
    function loadBulkAssignData() {
        // Load users
        $.ajax({
            url: '{{ route("superadmin.user-roles.index") }}',
            data: { users_only: true },
            success: function(data) {
                $('#bulkUsers').empty();
                data.users.forEach(function(user) {
                    $('#bulkUsers').append(`<option value="${user.id}">${user.name} (${user.email})</option>`);
                });
            }
        });

        // Load roles
        $.ajax({
            url: '{{ route("superadmin.roles.index") }}',
            data: { roles_only: true },
            success: function(data) {
                $('#bulkRoles').empty();
                data.roles.forEach(function(role) {
                    $('#bulkRoles').append(`<option value="${role.id}">${role.name}</option>`);
                });
            }
        });
    }

    // Process bulk assign
    window.processBulkAssign = function() {
        const userIds = $('#bulkUsers').val();
        const roleIds = $('#bulkRoles').val();

        if (!userIds || !roleIds) {
            showAlert('error', 'Please select both users and roles!');
            return;
        }

        $.ajax({
            url: '{{ route("superadmin.user-roles.bulk-assign") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                user_ids: userIds,
                role_ids: roleIds
            },
            success: function(response) {
                if (response.success) {
                    $('#bulkAssignModal').modal('hide');
                    table.draw();
                    updateStatistics();
                    showAlert('success', response.message);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'Something went wrong!');
            }
        });
    };

    // Initial statistics load
    updateStatistics();

    // Add smooth animations
    $('.card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('fade-in-up');
    });
});
</script>
@endsection
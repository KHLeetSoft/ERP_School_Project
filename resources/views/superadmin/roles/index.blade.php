@extends('superadmin.app')

@section('title', 'Roles Management')

@section('content')
<div class="container-fluid p-0">
    <!-- Enhanced Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-shield-alt me-3"></i>Roles Management
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Manage system roles and permissions with advanced controls</p>
                        </div>
                        <a href="{{ route('superadmin.roles.create') }}" class="btn btn-light btn-lg px-4 py-3 rounded-pill shadow-sm">
                            <i class="fas fa-plus me-2"></i>Create New Role
                        </a>
                    </div>
                </div>
                <!-- Background Pattern -->
                <div class="position-absolute top-0 end-0 w-100 h-100" style="background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"grain\" width=\"100\" height=\"100\" patternUnits=\"userSpaceOnUse\"><circle cx=\"25\" cy=\"25\" r=\"1\" fill=\"white\" opacity=\"0.1\"/><circle cx=\"75\" cy=\"75\" r=\"1\" fill=\"white\" opacity=\"0.1\"/><circle cx=\"50\" cy=\"10\" r=\"0.5\" fill=\"white\" opacity=\"0.1\"/><circle cx=\"10\" cy=\"60\" r=\"0.5\" fill=\"white\" opacity=\"0.1\"/><circle cx=\"90\" cy=\"40\" r=\"0.5\" fill=\"white\" opacity=\"0.1\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23grain)\"/></svg>'); pointer-events: none;"></div>
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
                            <div class="text-uppercase text-primary fw-bold small mb-1">Total Roles</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="totalRoles">0</div>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up me-1"></i>Active System
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
                            <div class="text-uppercase text-success fw-bold small mb-1">Active Roles</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="activeRoles">0</div>
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
                            <div class="text-uppercase text-warning fw-bold small mb-1">System Roles</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="systemRoles">0</div>
                            <div class="text-warning small">
                                <i class="fas fa-cog me-1"></i>Built-in Roles
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-cog fa-2x text-warning"></i>
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
                            <div class="text-uppercase text-info fw-bold small mb-1">Custom Roles</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="customRoles">0</div>
                            <div class="text-info small">
                                <i class="fas fa-user-plus me-1"></i>User Created
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-plus fa-2x text-info"></i>
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
                            <label class="form-label fw-semibold text-muted">Type Filter</label>
                            <select class="form-select form-select-lg" id="filterType">
                                <option value="">All Types</option>
                                <option value="1">System</option>
                                <option value="0">Custom</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Search Roles</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="search" id="globalSearch" class="form-control border-0 bg-light" placeholder="Search by role name, description...">
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
                            <h5 class="mb-1 fw-bold">Roles Overview</h5>
                            <p class="text-muted mb-0">Manage and configure system roles</p>
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
                        <table id="roles-datatable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold text-muted">#</th>
                                    <th class="border-0 fw-semibold text-muted">Role Information</th>
                                    <th class="border-0 fw-semibold text-muted">Permissions</th>
                                    <th class="border-0 fw-semibold text-muted">Users</th>
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let table;

    function initDataTable() {
        if (table) {
            table.destroy();
        }

        table = $('#roles-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("superadmin.roles.index") }}',
                data: function(d) {
                    d.status = $('#filterStatus').val();
                    d.type = $('#filterType').val();
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
                    data: 'role_info', 
                    name: 'role_info',
                    className: 'fw-semibold'
                },
                { 
                    data: 'permissions_count', 
                    name: 'permissions_count', 
                    orderable: false,
                    className: 'text-center'
                },
                { 
                    data: 'users_count', 
                    name: 'users_count', 
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
                processing: '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><br>Loading roles...</div>',
                emptyTable: '<div class="text-center py-5"><i class="fas fa-inbox fa-3x text-muted mb-3"></i><h5 class="text-muted">No roles found</h5><p class="text-muted">Create your first role to get started</p></div>',
                zeroRecords: '<div class="text-center py-5"><i class="fas fa-search fa-3x text-muted mb-3"></i><h5 class="text-muted">No matching roles found</h5><p class="text-muted">Try adjusting your search criteria</p></div>'
            },
            drawCallback: function() {
                // Add hover effects to table rows
                $('#roles-datatable tbody tr').hover(
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
    $('#filterStatus, #filterType').on('change', function() {
        table.draw();
        updateStatistics();
    });

    // Global search
    $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Toggle role status
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        let roleId = $(this).data('role-id');
        let currentStatus = $(this).data('status');
        
        if (!confirm('Are you sure you want to change the status of this role?')) {
            return;
        }
        
        $.ajax({
            url: '{{ url("superadmin/roles") }}/' + roleId + '/toggle-status',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $(e.target).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    table.draw();
                    updateStatistics();
                    showAlert('success', response.message);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'Something went wrong!');
            },
            complete: function() {
                $(e.target).prop('disabled', false).html(currentStatus ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>');
            }
        });
    });

    // Load statistics
    function updateStatistics() {
        $.ajax({
            url: '{{ route("superadmin.roles.index") }}',
            data: { stats_only: true },
            success: function(data) {
                $('#totalRoles').text(data.total || 0);
                $('#activeRoles').text(data.active || 0);
                $('#systemRoles').text(data.system || 0);
                $('#customRoles').text(data.custom || 0);
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
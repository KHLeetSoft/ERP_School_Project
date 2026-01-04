@extends('superadmin.app')

@section('title', 'Module Permissions')

@section('content')
<div class="container-fluid p-0">
    <!-- Enhanced Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-info text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-key me-3"></i>Module Permissions
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Manage system permissions and access controls</p>
                        </div>
                        <a href="{{ route('superadmin.permissions.create') }}" class="btn btn-light btn-lg px-4 py-3 rounded-pill shadow-sm">
                            <i class="fas fa-plus me-2"></i>Create Permission
                        </a>
                    </div>
                </div>
                <!-- Background Pattern -->
                <div class="position-absolute top-0 end-0 w-100 h-100" style="background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"keyPattern\" width=\"20\" height=\"20\" patternUnits=\"userSpaceOnUse\"><rect width=\"20\" height=\"20\" fill=\"white\" opacity=\"0.05\"/><circle cx=\"10\" cy=\"10\" r=\"2\" fill=\"white\" opacity=\"0.1\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23keyPattern)\"/></svg>'); pointer-events: none;"></div>
            </div>
        </div>
    </div>

    <!-- Module Overview Cards -->
    <div class="row mb-4">
        @foreach($modules as $module)
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body p-3">
                    <div class="mb-3">
                        <div class="bg-{{ $loop->index % 2 == 0 ? 'primary' : 'info' }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-{{ $module == 'teacher' ? 'chalkboard-teacher' : ($module == 'student' ? 'graduation-cap' : ($module == 'payment' ? 'credit-card' : 'cog')) }} fa-lg text-{{ $loop->index % 2 == 0 ? 'primary' : 'info' }}"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">{{ ucfirst($module) }}</h6>
                    <small class="text-muted">Module</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Enhanced Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted">Module Filter</label>
                            <select class="form-select form-select-lg" id="filterModule">
                                <option value="">All Modules</option>
                                @foreach($modules as $module)
                                <option value="{{ $module }}">{{ ucfirst($module) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted">Action Filter</label>
                            <select class="form-select form-select-lg" id="filterAction">
                                <option value="">All Actions</option>
                                <option value="view">View</option>
                                <option value="create">Create</option>
                                <option value="edit">Edit</option>
                                <option value="delete">Delete</option>
                                <option value="export">Export</option>
                                <option value="import">Import</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted">Search Permissions</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="search" id="globalSearch" class="form-control border-0 bg-light" placeholder="Search permissions...">
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
                            <h5 class="mb-1 fw-bold">Permissions Overview</h5>
                            <p class="text-muted mb-0">Manage system permissions and access controls</p>
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
                        <table id="permissions-datatable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold text-muted">#</th>
                                    <th class="border-0 fw-semibold text-muted">Permission Details</th>
                                    <th class="border-0 fw-semibold text-muted">Module</th>
                                    <th class="border-0 fw-semibold text-muted">Action</th>
                                    <th class="border-0 fw-semibold text-muted">Roles</th>
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

        table = $('#permissions-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("superadmin.permissions.index") }}',
                data: function(d) {
                    d.module = $('#filterModule').val();
                    d.action = $('#filterAction').val();
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
                    data: 'permission_info', 
                    name: 'permission_info',
                    className: 'fw-semibold'
                },
                { 
                    data: 'module_badge', 
                    name: 'module_badge', 
                    orderable: false,
                    className: 'text-center'
                },
                { 
                    data: 'action_badge', 
                    name: 'action_badge', 
                    orderable: false,
                    className: 'text-center'
                },
                { 
                    data: 'roles_count', 
                    name: 'roles_count', 
                    orderable: false,
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
                processing: '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><br>Loading permissions...</div>',
                emptyTable: '<div class="text-center py-5"><i class="fas fa-key fa-3x text-muted mb-3"></i><h5 class="text-muted">No permissions found</h5><p class="text-muted">Create your first permission to get started</p></div>',
                zeroRecords: '<div class="text-center py-5"><i class="fas fa-search fa-3x text-muted mb-3"></i><h5 class="text-muted">No matching permissions found</h5><p class="text-muted">Try adjusting your search criteria</p></div>'
            },
            drawCallback: function() {
                // Add hover effects to table rows
                $('#permissions-datatable tbody tr').hover(
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
    $('#filterModule, #filterAction').on('change', function() {
        table.draw();
    });

    // Global search
    $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Refresh table
    window.refreshTable = function() {
        table.draw();
        showAlert('success', 'Table refreshed successfully!');
    };

    // Export data
    window.exportData = function() {
        showAlert('info', 'Export functionality will be implemented soon!');
    };

    // Add smooth animations
    $('.card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('fade-in-up');
    });
});
</script>
@endsection
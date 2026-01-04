@extends('admin.layout.app')

@section('title', 'Transport Vehicles')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
    .btn-group .btn {
        border-radius: 6px !important;
        margin: 0 2px;
    }
    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .card {
        border-radius: 12px;
        border: none;
    }
    .table-responsive {
        border-radius: 12px;
    }
    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px;
        margin: 0 2px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        border-color: #007bff;
        color: white !important;
    }
    .dataTables_wrapper .dataTables_info {
        color: #6c757d;
        font-size: 0.875rem;
    }
    .link {
        color: #007bff;
        text-decoration: none;
    }
    .link:hover {
        color: #0056b3;
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">🚗 Transport Vehicles</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Transport Vehicles</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.transport.vehicles.dashboard') }}" class="btn btn-info">
                <i class="fas fa-chart-line me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.transport.vehicles.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i> Add Vehicle
            </a>
        </div>
    </div>

    <!-- Modern Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Vehicles -->
        <div class="col-md-3">
            <div class="card border-0 shadow-lg rounded-4 h-100 stats-card bg-gradient-primary text-white">
                <div class="card-body text-center">
                    <div class="icon-circle bg-white text-primary mb-3 mx-auto">
                        <i class="fas fa-bus fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($stats['total_vehicles'] ?? 0) }}</h3>
                    <p class="mb-0 text-white-50">Total Vehicles</p>
                </div>
            </div>
        </div>

        <!-- Active Vehicles -->
        <div class="col-md-3">
            <div class="card border-0 shadow-lg rounded-4 h-100 stats-card bg-gradient-success text-white">
                <div class="card-body text-center">
                    <div class="icon-circle bg-white text-success mb-3 mx-auto">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($stats['active_vehicles'] ?? 0) }}</h3>
                    <p class="mb-0 text-white-50">Active</p>
                </div>
            </div>
        </div>

        <!-- Available Vehicles -->
        <div class="col-md-3">
            <div class="card border-0 shadow-lg rounded-4 h-100 stats-card bg-gradient-info text-white">
                <div class="card-body text-center">
                    <div class="icon-circle bg-white text-info mb-3 mx-auto">
                        <i class="fas fa-road fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($stats['available_vehicles'] ?? 0) }}</h3>
                    <p class="mb-0 text-white-50">Available</p>
                </div>
            </div>
        </div>

        <!-- Maintenance Vehicles -->
        <div class="col-md-3">
            <div class="card border-0 shadow-lg rounded-4 h-100 stats-card bg-gradient-warning text-dark">
                <div class="card-body text-center">
                    <div class="icon-circle bg-white text-warning mb-3 mx-auto">
                        <i class="fas fa-tools fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($stats['maintenance_vehicles'] ?? 0) }}</h3>
                    <p class="mb-0 text-dark">Maintenance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">All Vehicles</h5>
            <div id="bulkActions" style="display: none;">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')">Activate</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')">Deactivate</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('maintenance')">Set Maintenance</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="vehiclesTable" class="table align-middle table-hover mb-0 w-100">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Vehicle Info</th>
                            <th>Registration</th>
                            <th>Type</th>
                            <th>Brand & Model</th>
                            <th>Status</th>
                            <th>Availability</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this vehicle? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Vehicle</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#vehiclesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("admin.transport.vehicles.index") }}',
            type: 'GET'
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
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<input type="checkbox" class="vehicle-checkbox" value="' + data + '">';
                }
            },
            { 
                data: 'vehicle_info',
                render: function(data, type, row) {
                    return data; // HTML is already rendered from controller
                }
            },
            { data: 'registration' },
            { data: 'type' },
            { data: 'brand_model' },
            { 
                data: 'status',
                render: function(data, type, row) {
                    return data; // HTML is already rendered from controller
                }
            },
            { 
                data: 'availability',
                render: function(data, type, row) {
                    return data; // HTML is already rendered from controller
                }
            },
            { data: 'created' },
            {
                data: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return data; // HTML is already rendered from controller
                }
            }
        ]
    });

    // Select all functionality
    $('#selectAll').change(function() {
        $('.vehicle-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkActions();
    });

    // Update bulk actions visibility
    $(document).on('change', '.vehicle-checkbox', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        const checkedCount = $('.vehicle-checkbox:checked').length;
        if (checkedCount > 0) {
            $('#bulkActions').show();
        } else {
            $('#bulkActions').hide();
        }
    }

    // Handle delete button clicks
    $(document).on('click', '.delete-vehicle-btn', function() {
        const vehicleId = $(this).data('id');
        deleteVehicle(vehicleId);
    });

    // Handle toggle status button clicks
    $(document).on('click', '.toggle-status-btn', function() {
        const vehicleId = $(this).data('id');
        toggleStatus(vehicleId);
    });
});

function toggleStatus(vehicleId) {
    $.post(`/admin/transport/vehicles/${vehicleId}/toggle-status`, {
        _token: '{{ csrf_token() }}'
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            $('#vehiclesTable').DataTable().ajax.reload();
        } else {
            toastr.error(response.message);
        }
    })
    .fail(function() {
        toastr.error('Error updating vehicle status');
    });
}

function duplicateVehicle(vehicleId) {
    $.post(`/admin/transport/vehicles/${vehicleId}/duplicate`, {
        _token: '{{ csrf_token() }}'
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            $('#vehiclesTable').DataTable().ajax.reload();
        } else {
            toastr.error(response.message);
        }
    })
    .fail(function() {
        toastr.error('Error duplicating vehicle');
    });
}

function deleteVehicle(vehicleId) {
    if (confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) {
        $.ajax({
            url: `/admin/transport/vehicles/${vehicleId}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                toastr.success('Vehicle deleted successfully!');
                $('#vehiclesTable').DataTable().ajax.reload();
            },
            error: function() {
                toastr.error('Error deleting vehicle');
            }
        });
    }
}

function bulkAction(action) {
    const checkedVehicles = $('.vehicle-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (checkedVehicles.length === 0) {
        toastr.warning('Please select vehicles first');
        return;
    }

    if (action === 'delete' && !confirm('Are you sure you want to delete the selected vehicles? This action cannot be undone.')) {
        return;
    }

    $.post('/admin/transport/vehicles/bulk-action', {
        _token: '{{ csrf_token() }}',
        action: action,
        vehicle_ids: checkedVehicles
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            $('#vehiclesTable').DataTable().ajax.reload();
            $('#selectAll').prop('checked', false);
            updateBulkActions();
        } else {
            toastr.error(response.message);
        }
    })
    .fail(function() {
        toastr.error('Error performing bulk action');
    });
}
</script>
@endsection

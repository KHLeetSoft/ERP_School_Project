@extends('admin.layout.app')

@section('title', 'Transport Routes')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">🚍 Transport Routes</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Transport Routes</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.transport.tproutes.create') }}" class="btn btn-gradient-primary shadow-sm">
            <i class="fas fa-plus me-2"></i> Add New Route
        </a>
    </div>
<!-- Redesigned Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Routes -->
    <div class="col-md-3">
        <div class="card border-0 shadow-lg rounded-4 h-100 stats-card bg-gradient-primary text-white">
            <div class="card-body text-center">
                <div class="icon-circle bg-white text-primary mb-3 mx-auto">
                    <i class="fas fa-route fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($stats['total_routes'] ?? 0) }}</h3>
                <p class="mb-0 text-white-50">Total Routes</p>
            </div>
        </div>
    </div>

    <!-- Active Routes -->
    <div class="col-md-3">
        <div class="card border-0 shadow-lg rounded-4 h-100 stats-card bg-gradient-success text-white">
            <div class="card-body text-center">
                <div class="icon-circle bg-white text-success mb-3 mx-auto">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($stats['active_routes'] ?? 0) }}</h3>
                <p class="mb-0 text-white-50">Active</p>
            </div>
        </div>
    </div>

    <!-- Inactive Routes -->
    <div class="col-md-3">
        <div class="card border-0 shadow-lg rounded-4 h-100 stats-card bg-gradient-secondary text-white">
            <div class="card-body text-center">
                <div class="icon-circle bg-white text-secondary mb-3 mx-auto">
                    <i class="fas fa-pause-circle fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($stats['inactive_routes'] ?? 0) }}</h3>
                <p class="mb-0 text-white-50">Inactive</p>
            </div>
        </div>
    </div>

    <!-- Total Fare -->
    <div class="col-md-3">
        <div class="card border-0 shadow-lg rounded-4 h-100 stats-card bg-gradient-warning text-dark">
            <div class="card-body text-center">
                <div class="icon-circle bg-white text-warning mb-3 mx-auto">
                    <i class="fas fa-money-bill fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1">₹{{ number_format($stats['total_fare'] ?? 0) }}</h3>
                <p class="mb-0 text-dark">Total Fare</p>
            </div>
        </div>
    </div>
</div>


    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.transport.tproutes.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="🔍 Search route..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="route_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="regular" {{ request('route_type') === 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="express" {{ request('route_type') === 'express' ? 'selected' : '' }}>Express</option>
                            <option value="special" {{ request('route_type') === 'special' ? 'selected' : '' }}>Special</option>
                            <option value="school" {{ request('route_type') === 'school' ? 'selected' : '' }}>School</option>
                            <option value="college" {{ request('route_type') === 'college' ? 'selected' : '' }}>College</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort_by" class="form-select">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                            <option value="route_name" {{ request('sort_by') === 'route_name' ? 'selected' : '' }}>Name</option>
                            <option value="route_number" {{ request('sort_by') === 'route_number' ? 'selected' : '' }}>Route Number</option>
                            <option value="total_distance" {{ request('sort_by') === 'total_distance' ? 'selected' : '' }}>Distance</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('admin.transport.tproutes.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Routes Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">All Routes</h5>
            <div id="bulkActions" style="display: none;">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')">Activate</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')">Deactivate</a></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Route Info</th>
                            <th>Location</th>
                            <th>Distance & Duration</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routes as $route)
                        <tr>
                            <td><input type="checkbox" class="route-checkbox" value="{{ $route->id }}"></td>
                            <td>
                                <strong>{{ $route->route_name }}</strong><br>
                                <small class="text-muted">#{{ $route->route_number }}</small><br>
                                <span class="badge bg-secondary">{{ ucfirst($route->route_type) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($route->start_location,20) }}</span>
                                <span class="badge bg-danger"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($route->end_location,20) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $route->formatted_distance }}</span><br>
                                <small class="text-muted">{{ $route->formatted_duration }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $route->current_occupancy }}/{{ $route->vehicle_capacity }}</span><br>
                                <small class="text-muted">{{ $route->getAvailableCapacity() }} available</small>
                            </td>
                            <td><span class="badge {{ $route->status_badge_class }}">{{ ucfirst($route->status) }}</span></td>
                            <td>
                                <small>{{ $route->created_at->format('M d, Y') }}</small><br>
                                <span class="text-muted">{{ $route->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.transport.tproutes.show', $route->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.transport.tproutes.edit', $route->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="toggleStatus({{ $route->id }})"><i class="fas fa-toggle-on"></i> Toggle Status</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="duplicateRoute({{ $route->id }})"><i class="fas fa-copy"></i> Duplicate</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteRoute({{ $route->id }})"><i class="fas fa-trash"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center p-5">
                                <i class="fas fa-route fa-2x text-muted mb-3"></i>
                                <h6 class="text-muted">No routes found</h6>
                                <a href="{{ route('admin.transport.tproutes.create') }}" class="btn btn-sm btn-primary mt-2">+ Add Route</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($routes->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $routes->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#selectAll').change(function() {
        $('.route-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkActions();
    });

    $('.route-checkbox').change(function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        const checkedCount = $('.route-checkbox:checked').length;
        if (checkedCount > 0) {
            $('#bulkActions').show();
        } else {
            $('#bulkActions').hide();
        }
    }

    // Auto-submit form on filter change
    $('select[name="status"], select[name="sort_by"]').change(function() {
        $('#filterForm').submit();
    });
});

function toggleStatus(routeId) {
    $.post(`/admin/transport/tproutes/${routeId}/toggle-status`, {
        _token: '{{ csrf_token() }}'
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            toastr.error(response.message);
        }
    })
    .fail(function() {
        toastr.error('Error updating route status');
    });
}



function duplicateRoute(routeId) {
    if (confirm('Are you sure you want to duplicate this route?')) {
        $.post(`/admin/transport/routes/${routeId}/duplicate`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            toastr.success('Route duplicated successfully!');
            setTimeout(() => location.reload(), 1000);
        })
        .fail(function() {
            toastr.error('Error duplicating route');
        });
    }
}

function deleteRoute(routeId) {
    $('#deleteModal').modal('show');
    $('#confirmDelete').off('click').on('click', function() {
        $.ajax({
            url: `/admin/transport/tproutes/${routeId}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                toastr.success('Route deleted successfully!');
                $('#deleteModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            },
            error: function() {
                toastr.error('Error deleting route');
            }
        });
    });
}

function bulkAction(action) {
    const selectedIds = $('.route-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        toastr.warning('Please select routes to perform the action.');
        return;
    }

    if (action === 'delete' && !confirm('Are you sure you want to delete the selected routes?')) {
        return;
    }

    $.post('/admin/transport/tproutes/bulk-action', {
        _token: '{{ csrf_token() }}',
        action: action,
        route_ids: selectedIds
    })
    .done(function(response) {
        toastr.success('Bulk action completed successfully!');
        setTimeout(() => location.reload(), 1000);
    })
    .fail(function() {
        toastr.error('Error performing bulk action');
    });
}
</script>
@endsection

@section('styles')
<style>
       .stats-card {
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }
    .bg-gradient-success {
        background: linear-gradient(45deg, #1cc88a, #13855c);
    }
    .bg-gradient-secondary {
        background: linear-gradient(45deg, #858796, #4e555b);
    }
    .bg-gradient-warning {
        background: linear-gradient(45deg, #f6c23e, #dda20a);
    }
.stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.stat-card__content {
    display: flex;
    align-items: center;
}

.stat-card__icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 24px;
    color: white;
}

.stat-card__icon--primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card__icon--success { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card__icon--info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card__icon--warning { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

.stat-card__number {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
}

.stat-card__desc {
    margin: 0;
    color: #718096;
    font-size: 14px;
}

.route-info h6 {
    margin: 0;
    font-weight: 600;
}

.route-location .location-item {
    margin-bottom: 3px;
}

.route-location .location-item i {
    width: 16px;
}

.fare-info .badge {
    font-size: 14px;
    padding: 8px 12px;
}

.status-info .badge {
    font-size: 12px;
    padding: 6px 10px;
}

.created-info {
    font-size: 14px;
}

.btn-group .dropdown-menu {
    min-width: 200px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #4a5568;
}

.table td {
    vertical-align: middle;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0;
    padding: 20px;
}

.card-title {
    margin: 0;
    color: #2d3748;
    font-weight: 600;
}
</style>
@endsection


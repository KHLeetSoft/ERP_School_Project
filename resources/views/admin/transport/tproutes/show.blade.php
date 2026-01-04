@extends('admin.layout.app')

@section('title', 'Transport Route Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Transport Route Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.tproutes.index') }}">Transport Routes</a></li>
                    <li class="breadcrumb-item active">{{ $route->route_name }}</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <a href="{{ route('admin.transport.tproutes.edit', $route->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-1"></i> Edit Route
                    </a>
                    <a href="{{ route('admin.transport.tproutes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Routes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Route Information Card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-route mr-2"></i>
                        Route Information
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Route Name:</label>
                                <p class="mb-0">{{ $route->route_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Route Number:</label>
                                <p class="mb-0">
                                    <span class="badge badge-secondary">{{ $route->route_number }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Start Location:</label>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt text-success mr-2"></i>
                                    {{ $route->start_location }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">End Location:</label>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                    {{ $route->end_location }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Total Distance:</label>
                                <p class="mb-0">
                                    <span class="badge badge-info">{{ $route->formatted_distance }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Estimated Duration:</label>
                                <p class="mb-0">
                                    <span class="badge badge-info">{{ $route->formatted_duration }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Vehicle Capacity:</label>
                                <p class="mb-0">
                                    <span class="badge badge-primary">{{ $route->vehicle_capacity }} passengers</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Current Occupancy:</label>
                                <p class="mb-0">
                                    <span class="badge badge-{{ $route->isFull() ? 'danger' : 'success' }}">
                                        {{ $route->current_occupancy }} / {{ $route->vehicle_capacity }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Route Type:</label>
                                <p class="mb-0">
                                    <span class="badge badge-info">{{ ucfirst($route->route_type) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Status:</label>
                                <p class="mb-0">
                                    <span class="badge {{ $route->status_badge_class }}">
                                        {{ ucfirst($route->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($route->description)
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Description:</label>
                        <p class="mb-0">{{ $route->description }}</p>
                    </div>
                    @endif

                    @if($route->stops && is_array($route->stops) && count($route->stops) > 0)
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Stops:</label>
                        <ul class="list-unstyled mb-0">
                            @foreach($route->stops as $stop)
                            <li><i class="fas fa-circle text-primary mr-2"></i>{{ $stop }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Stops:</label>
                        <p class="mb-0 text-muted"><em>No stops defined for this route</em></p>
                    </div>
                    @endif

                    @if($route->schedule && is_array($route->schedule) && count($route->schedule) > 0)
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Schedule:</label>
                        <ul class="list-unstyled mb-0">
                            @foreach($route->schedule as $day => $times)
                            <li><i class="fas fa-clock text-info mr-2"></i><strong>{{ ucfirst($day) }}:</strong> {{ $times }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Schedule:</label>
                        <p class="mb-0 text-muted"><em>No schedule defined for this route</em></p>
                    </div>
                    @endif

                    @if($route->fare_structure && is_array($route->fare_structure) && count($route->fare_structure) > 0)
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Fare Structure:</label>
                        <ul class="list-unstyled mb-0">
                            @if(isset($route->fare_structure['base_fare']))
                            <li><i class="fas fa-money-bill text-success mr-2"></i><strong>Base Fare:</strong> ₹{{ number_format($route->fare_structure['base_fare'], 2) }}</li>
                            @endif
                            @if(isset($route->fare_structure['student_discount']))
                            <li><i class="fas fa-graduation-cap text-primary mr-2"></i><strong>Student Discount:</strong> {{ $route->fare_structure['student_discount'] }}%</li>
                            @endif
                            @if(isset($route->fare_structure['monthly_pass']))
                            <li><i class="fas fa-calendar-alt text-warning mr-2"></i><strong>Monthly Pass:</strong> ₹{{ number_format($route->fare_structure['monthly_pass'], 2) }}</li>
                            @endif
                        </ul>
                    </div>
                    @else
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Fare Structure:</label>
                        <p class="mb-0 text-muted"><em>No fare structure defined for this route</em></p>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Created By:</label>
                                <p class="mb-0">
                                    @if($route->created_by)
                                        <span class="badge badge-info">User ID: {{ $route->created_by }}</span>
                                    @else
                                        <span class="text-muted"><em>Not specified</em></span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Last Updated By:</label>
                                <p class="mb-0">
                                    @if($route->updated_by)
                                        <span class="badge badge-info">User ID: {{ $route->updated_by }}</span>
                                    @else
                                        <span class="text-muted"><em>Not specified</em></span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Featured Route:</label>
                                <p class="mb-0">
                                    @if($route->is_featured)
                                        <span class="badge badge-warning"><i class="fas fa-star"></i> Featured</span>
                                    @else
                                        <span class="badge badge-secondary">Not Featured</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Active Status:</label>
                                <p class="mb-0">
                                    @if($route->is_active)
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Active</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Created:</label>
                                <p class="mb-0">{{ $route->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Last Updated:</label>
                                <p class="mb-0">{{ $route->updated_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Route Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-cogs mr-2"></i>
                        Route Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-primary btn-block mb-2" onclick="toggleStatus({{ $route->id }})">
                                <i class="fas fa-toggle-on mr-1"></i>
                                Toggle Status
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-success btn-block mb-2" onclick="duplicateRoute({{ $route->id }})">
                                <i class="fas fa-copy mr-1"></i>
                                Duplicate Route
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-danger btn-block mb-2" onclick="deleteRoute({{ $route->id }})">
                                <i class="fas fa-trash mr-1"></i>
                                Delete Route
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Route Statistics Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Route Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-primary">{{ $route->created_at->diffForHumans() }}</div>
                        <div class="stat-label text-muted">Age</div>
                    </div>
                    
                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-success">{{ ucfirst($route->status) }}</div>
                        <div class="stat-label text-muted">Current Status</div>
                    </div>
                    
                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-info">{{ $route->formatted_distance }}</div>
                        <div class="stat-label text-muted">Total Distance</div>
                    </div>

                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-warning">{{ $route->formatted_duration }}</div>
                        <div class="stat-label text-muted">Estimated Duration</div>
                    </div>

                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-primary">{{ $route->vehicle_capacity }}</div>
                        <div class="stat-label text-muted">Total Capacity</div>
                    </div>

                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-{{ $route->current_occupancy > 0 ? 'warning' : 'secondary' }}">{{ $route->current_occupancy }}</div>
                        <div class="stat-label text-muted">Current Occupancy</div>
                    </div>
                    
                    <div class="stat-item text-center">
                        <div class="stat-number text-{{ $route->isFull() ? 'danger' : 'success' }}">
                            {{ $route->getAvailableCapacity() }}
                        </div>
                        <div class="stat-label text-muted">Available Seats</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt mr-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.transport.tproutes.edit', $route->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i> Edit Route
                        </a>
                        <button type="button" class="btn btn-success" onclick="duplicateRoute({{ $route->id }})">
                            <i class="fas fa-copy mr-1"></i> Duplicate
                        </button>
                        <button type="button" class="btn btn-warning" onclick="toggleStatus({{ $route->id }})">
                            <i class="fas fa-toggle-on mr-1"></i> Toggle Status
                        </button>
                        <button type="button" class="btn btn-danger" onclick="deleteRoute({{ $route->id }})">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </div>
                </div>
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
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the transport route "<strong>{{ $route->route_name }}</strong>"? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Route</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
        $.post(`/admin/transport/tproutes/${routeId}/duplicate`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
            setTimeout(() => window.location.href = '{{ route("admin.transport.tproutes.index") }}', 1000);
            } else {
                toastr.error(response.message);
            }
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
                setTimeout(() => window.location.href = '{{ route("admin.transport.tproutes.index") }}', 1000);
            },
            error: function() {
                toastr.error('Error deleting route');
            }
        });
    });
}
</script>
@endsection

@section('styles')
<style>
.info-item label {
    font-size: 14px;
    margin-bottom: 5px;
}

.info-item p {
    font-size: 16px;
    color: #2d3748;
    margin: 0;
}

.stat-item {
    padding: 15px;
    border-radius: 8px;
    background: #f8f9fa;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
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

.page-header {
    background: white;
    padding: 20px 0;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn-group .btn {
    margin-right: 5px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection

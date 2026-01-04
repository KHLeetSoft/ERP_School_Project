@extends('student.layout.app')

@section('title', 'Transport Routes')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-route me-2"></i>Transport Routes
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.transport.index') }}">Transport</a></li>
                        <li class="breadcrumb-item active">Routes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Route Info -->
    @if($studentRoute)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Your Current Route
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Route Name:</label>
                                <p class="mb-0 fs-5">{{ $studentRoute['name'] }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pickup Point:</label>
                                <p class="mb-0">{{ $studentRoute['pickup_point'] }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Drop Point:</label>
                                <p class="mb-0">{{ $studentRoute['drop_point'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Monthly Fee:</label>
                                <p class="mb-0 fs-5 text-success">${{ number_format($studentRoute['monthly_fee'], 2) }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <p class="mb-0">
                                    <span class="badge bg-success">{{ $studentRoute['status'] }}</span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <a href="{{ route('student.transport.profile') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-2"></i>Update Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Available Routes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bus me-2"></i>Available Routes
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($routes) > 0)
                        <div class="row">
                            @foreach($routes as $route)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 {{ $studentRoute && $studentRoute['id'] == $route['id'] ? 'border-success' : '' }}">
                                    <div class="card-header {{ $studentRoute && $studentRoute['id'] == $route['id'] ? 'bg-success text-white' : 'bg-light' }}">
                                        <h6 class="card-title mb-0">
                                            {{ $route['name'] }}
                                            @if($studentRoute && $studentRoute['id'] == $route['id'])
                                                <span class="badge bg-light text-success ms-2">Current</span>
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <h6 class="text-primary">Pickup Points:</h6>
                                            <ul class="list-unstyled mb-0">
                                                @foreach($route['pickup_points'] as $point)
                                                <li><i class="fas fa-map-marker-alt text-success me-2"></i>{{ $point }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6 class="text-primary">Drop Points:</h6>
                                            <ul class="list-unstyled mb-0">
                                                @foreach($route['drop_points'] as $point)
                                                <li><i class="fas fa-flag text-danger me-2"></i>{{ $point }}</li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">Distance:</small>
                                                <div class="fw-bold">{{ $route['distance'] }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Duration:</small>
                                                <div class="fw-bold">{{ $route['duration'] }}</div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">Capacity:</small>
                                                <div class="fw-bold">{{ $route['capacity'] }} seats</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Available:</small>
                                                <div class="fw-bold text-{{ $route['available_seats'] > 5 ? 'success' : 'warning' }}">
                                                    {{ $route['available_seats'] }} seats
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="text-success mb-0">${{ number_format($route['monthly_fee'], 2) }}</h5>
                                                <small class="text-muted">per month</small>
                                            </div>
                                            @if(!$studentRoute || $studentRoute['id'] != $route['id'])
                                            <button class="btn btn-outline-primary btn-sm" onclick="requestRoute({{ $route['id'] }})">
                                                <i class="fas fa-paper-plane me-1"></i>Request
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-route fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No routes available</h5>
                            <p class="text-muted">Please contact the transport office for route information.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Route Comparison -->
    @if(count($routes) > 1)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-balance-scale me-2"></i>Route Comparison
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Distance</th>
                                    <th>Duration</th>
                                    <th>Capacity</th>
                                    <th>Available Seats</th>
                                    <th>Monthly Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($routes as $route)
                                <tr class="{{ $studentRoute && $studentRoute['id'] == $route['id'] ? 'table-success' : '' }}">
                                    <td>
                                        {{ $route['name'] }}
                                        @if($studentRoute && $studentRoute['id'] == $route['id'])
                                            <span class="badge bg-success ms-2">Current</span>
                                        @endif
                                    </td>
                                    <td>{{ $route['distance'] }}</td>
                                    <td>{{ $route['duration'] }}</td>
                                    <td>{{ $route['capacity'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $route['available_seats'] > 5 ? 'success' : 'warning' }}">
                                            {{ $route['available_seats'] }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">${{ number_format($route['monthly_fee'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Request Route Modal -->
<div class="modal fade" id="requestRouteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Route Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="requestRouteForm">
                    <div class="mb-3">
                        <label class="form-label">Current Route</label>
                        <input type="text" class="form-control" value="{{ $studentRoute['name'] ?? 'Not Assigned' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Requested Route</label>
                        <input type="text" id="requestedRouteName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Change</label>
                        <textarea class="form-control" rows="3" placeholder="Please explain why you want to change routes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitRouteRequest()">Submit Request</button>
            </div>
        </div>
    </div>
</div>

<script>
function requestRoute(routeId) {
    // Find the route data
    const routes = @json($routes);
    const route = routes.find(r => r.id === routeId);
    
    if (route) {
        document.getElementById('requestedRouteName').value = route.name;
        const modal = new bootstrap.Modal(document.getElementById('requestRouteModal'));
        modal.show();
    }
}

function submitRouteRequest() {
    // Here you would typically send an AJAX request to submit the route change request
    alert('Route change request submitted successfully! You will be notified once it is processed.');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('requestRouteModal'));
    modal.hide();
}
</script>
@endsection

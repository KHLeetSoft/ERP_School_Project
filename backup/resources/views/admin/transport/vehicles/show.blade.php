@extends('admin.layout.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">🚗 Vehicle Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.vehicles.index') }}">Transport Vehicles</a></li>
                    <li class="breadcrumb-item active">{{ $vehicle->vehicle_display_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.transport.vehicles.edit', $vehicle->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i> Edit Vehicle
            </a>
            <a href="{{ route('admin.transport.vehicles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Vehicles
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Vehicle Information Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Vehicle Information
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Vehicle Number:</label>
                                <p class="mb-0">{{ $vehicle->vehicle_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Registration Number:</label>
                                <p class="mb-0">
                                    <span class="badge badge-secondary">{{ $vehicle->registration_number }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Vehicle Type:</label>
                                <p class="mb-0">
                                    <span class="badge badge-info">{{ ucfirst($vehicle->vehicle_type) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Brand:</label>
                                <p class="mb-0">{{ $vehicle->brand }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Model:</label>
                                <p class="mb-0">{{ $vehicle->model }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Year of Manufacture:</label>
                                <p class="mb-0">{{ $vehicle->year_of_manufacture }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Seating Capacity:</label>
                                <p class="mb-0">
                                    <span class="badge badge-primary">{{ $vehicle->seating_capacity }} seats</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Fuel Type:</label>
                                <p class="mb-0">
                                    <span class="badge badge-warning">{{ ucfirst($vehicle->fuel_type) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Fuel Efficiency:</label>
                                <p class="mb-0">
                                    @if($vehicle->fuel_efficiency)
                                        <span class="badge badge-success">{{ $vehicle->formatted_fuel_efficiency }}</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($vehicle->description)
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Description:</label>
                        <p class="mb-0">{{ $vehicle->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Documents & Certificates Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2 text-info"></i>
                        Documents & Certificates
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Insurance Number:</label>
                                <p class="mb-0">
                                    @if($vehicle->insurance_number)
                                        {{ $vehicle->insurance_number }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Insurance Expiry:</label>
                                <p class="mb-0">
                                    @if($vehicle->insurance_expiry)
                                        <span class="badge badge-{{ $vehicle->isInsuranceExpired() ? 'danger' : 'success' }}">
                                            {{ $vehicle->insurance_expiry->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Permit Number:</label>
                                <p class="mb-0">
                                    @if($vehicle->permit_number)
                                        {{ $vehicle->permit_number }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Permit Expiry:</label>
                                <p class="mb-0">
                                    @if($vehicle->permit_expiry)
                                        <span class="badge badge-{{ $vehicle->isPermitExpired() ? 'danger' : 'success' }}">
                                            {{ $vehicle->permit_expiry->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Fitness Certificate:</label>
                                <p class="mb-0">
                                    @if($vehicle->fitness_certificate_number)
                                        {{ $vehicle->fitness_certificate_number }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Fitness Expiry:</label>
                                <p class="mb-0">
                                    @if($vehicle->fitness_expiry)
                                        <span class="badge badge-{{ $vehicle->isFitnessExpired() ? 'danger' : 'success' }}">
                                            {{ $vehicle->fitness_expiry->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">PUC Certificate:</label>
                                <p class="mb-0">
                                    @if($vehicle->puc_certificate_number)
                                        {{ $vehicle->puc_certificate_number }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">PUC Expiry:</label>
                                <p class="mb-0">
                                    @if($vehicle->puc_expiry)
                                        <span class="badge badge-{{ $vehicle->isPucExpired() ? 'danger' : 'success' }}">
                                            {{ $vehicle->puc_expiry->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-{{ $vehicle->hasExpiredDocuments() ? 'danger' : 'success' }} mt-3">
                        <i class="fas fa-{{ $vehicle->hasExpiredDocuments() ? 'exclamation-triangle' : 'check-circle' }} me-2"></i>
                        <strong>{{ $vehicle->hasExpiredDocuments() ? 'Warning' : 'Good' }}:</strong>
                        {{ $vehicle->hasExpiredDocuments() ? 'Some documents have expired. Please update them soon.' : 'All documents are valid and up to date.' }}
                    </div>
                </div>
            </div>

            <!-- Staff & Route Assignment Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2 text-success"></i>
                        Staff & Route Assignment
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Driver:</label>
                                <p class="mb-0">
                                    @if($vehicle->driver)
                                        <span class="badge badge-primary">{{ $vehicle->driver->name }}</span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Conductor:</label>
                                <p class="mb-0">
                                    @if($vehicle->conductor)
                                        <span class="badge badge-info">{{ $vehicle->conductor->name }}</span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Assigned Route:</label>
                                <p class="mb-0">
                                    @if($vehicle->assignedRoute)
                                        <span class="badge badge-secondary">{{ $vehicle->assignedRoute->route_name }}</span>
                                    @else
                                        <span class="text-muted">No route assigned</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="font-weight-bold text-muted">Route Number:</label>
                                <p class="mb-0">
                                    @if($vehicle->assignedRoute)
                                        {{ $vehicle->assignedRoute->route_number }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Actions Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2 text-warning"></i>
                        Vehicle Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <button type="button" class="btn btn-outline-primary btn-block" onclick="toggleStatus({{ $vehicle->id }})">
                                <i class="fas fa-toggle-on me-1"></i>
                                Toggle Status
                            </button>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="button" class="btn btn-outline-success btn-block" onclick="duplicateVehicle({{ $vehicle->id }})">
                                <i class="fas fa-copy me-1"></i>
                                Duplicate Vehicle
                            </button>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="button" class="btn btn-outline-danger btn-block" onclick="deleteVehicle({{ $vehicle->id }})">
                                <i class="fas fa-trash me-1"></i>
                                Delete Vehicle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Vehicle Statistics Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Vehicle Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-primary">{{ $vehicle->created_at->diffForHumans() }}</div>
                        <div class="stat-label text-muted">Age</div>
                    </div>
                    
                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-success">{{ ucfirst($vehicle->status) }}</div>
                        <div class="stat-label text-muted">Current Status</div>
                    </div>
                    
                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-info">{{ $vehicle->seating_capacity }}</div>
                        <div class="stat-label text-muted">Total Capacity</div>
                    </div>

                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-{{ $vehicle->current_occupancy > 0 ? 'warning' : 'secondary' }}">{{ $vehicle->current_occupancy }}</div>
                        <div class="stat-label text-muted">Current Occupancy</div>
                    </div>

                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-{{ $vehicle->isFull ? 'danger' : 'success' }}">
                            {{ $vehicle->available_seats }}
                        </div>
                        <div class="stat-label text-muted">Available Seats</div>
                    </div>

                    <div class="stat-item text-center">
                        <div class="stat-number text-{{ $vehicle->is_available ? 'success' : 'danger' }}">
                            {{ $vehicle->is_available ? 'Available' : 'Unavailable' }}
                        </div>
                        <div class="stat-label text-muted">Availability</div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Info Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Maintenance Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Last Maintenance:</label>
                        <p class="mb-0">
                            @if($vehicle->last_maintenance_date)
                                {{ $vehicle->last_maintenance_date->format('M d, Y') }}
                            @else
                                <span class="text-muted">Not recorded</span>
                            @endif
                        </p>
                    </div>

                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Next Maintenance:</label>
                        <p class="mb-0">
                            @if($vehicle->next_maintenance_date)
                                <span class="badge badge-{{ $vehicle->maintenance_status_badge_class }}">
                                    {{ $vehicle->next_maintenance_date->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">Not scheduled</span>
                            @endif
                        </p>
                    </div>

                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Total Distance:</label>
                        <p class="mb-0">
                            <span class="badge badge-info">{{ $vehicle->formatted_total_distance }}</span>
                        </p>
                    </div>

                    <div class="info-item">
                        <label class="font-weight-bold text-muted">Average Speed:</label>
                        <p class="mb-0">
                            @if($vehicle->average_speed)
                                <span class="badge badge-success">{{ $vehicle->formatted_average_speed }}</span>
                            @else
                                <span class="text-muted">Not recorded</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.transport.vehicles.edit', $vehicle->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Vehicle
                        </a>
                        <button type="button" class="btn btn-success" onclick="duplicateVehicle({{ $vehicle->id }})">
                            <i class="fas fa-copy me-1"></i> Duplicate
                        </button>
                        <button type="button" class="btn btn-warning" onclick="toggleStatus({{ $vehicle->id }})">
                            <i class="fas fa-toggle-on me-1"></i> Toggle Status
                        </button>
                        <button type="button" class="btn btn-danger" onclick="deleteVehicle({{ $vehicle->id }})">
                            <i class="fas fa-trash me-1"></i> Delete
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
                <p>Are you sure you want to delete the vehicle "<strong>{{ $vehicle->vehicle_display_name }}</strong>"? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Vehicle</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleStatus(vehicleId) {
    $.post(`/admin/transport/vehicles/${vehicleId}/toggle-status`, {
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
            setTimeout(() => location.reload(), 1000);
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
                setTimeout(() => window.location.href = '{{ route("admin.transport.vehicles.index") }}', 1000);
            },
            error: function() {
                toastr.error('Error deleting vehicle');
            }
        });
    }
}
</script>
@endsection

@section('styles')
<style>
.card {
    border-radius: 12px;
    border: none;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.info-item label {
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.info-item p {
    font-size: 1rem;
    margin-bottom: 0;
}

.stat-item {
    padding: 1rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.alert {
    border-radius: 8px;
    border: none;
}

.modal-content {
    border-radius: 12px;
    border: none;
}

.modal-header {
    border-radius: 12px 12px 0 0;
}
</style>
@endsection

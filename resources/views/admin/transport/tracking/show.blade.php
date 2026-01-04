@extends('admin.layout.app')

@section('title', 'Tracking Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Tracking Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.tracking.index') }}">Transport Tracking</a></li>
                    <li class="breadcrumb-item active">Tracking Details</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transport.tracking.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('admin.transport.tracking.edit', $tracking->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.transport.tracking.duplicate', $tracking->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure you want to duplicate this tracking record?')">
                            <i class="fas fa-copy"></i> Duplicate
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.transport.tracking.destroy', $tracking->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this tracking record?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Tracking Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tracking Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tracking ID</label>
                                <p class="form-control-plaintext">#{{ $tracking->id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <span class="badge {{ $tracking->status_badge_class }}">
                                        <i class="fas fa-{{ $tracking->status === 'on_time' ? 'check-circle' : ($tracking->status === 'delayed' ? 'clock' : ($tracking->status === 'early' ? 'fast-forward' : ($tracking->status === 'stopped' ? 'pause' : 'play'))) }} me-1"></i>
                                        {{ $tracking->status_text }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-calendar me-2"></i>{{ $tracking->tracking_date->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Time</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-clock me-2"></i>{{ $tracking->tracking_time->format('H:i:s') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Speed</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-tachometer-alt me-2"></i>{{ $tracking->formatted_speed }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Location</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $tracking->formatted_location }}
                                    @if($tracking->google_maps_url)
                                        <a href="{{ $tracking->google_maps_url }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fas fa-map"></i> View on Map
                                        </a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($tracking->notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes</label>
                            <div class="form-control-plaintext bg-light p-3 rounded">
                                <i class="fas fa-sticky-note me-2"></i>{{ $tracking->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Information Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Vehicle Information</h5>
                </div>
                <div class="card-body">
                    @if($tracking->vehicle)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Vehicle Number</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-bus me-2"></i>{{ $tracking->vehicle->vehicle_number }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Model</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-car me-2"></i>{{ $tracking->vehicle->model ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Capacity</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-users me-2"></i>{{ $tracking->vehicle->capacity ?? 'N/A' }} passengers
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $tracking->vehicle->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($tracking->vehicle->status ?? 'Unknown') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-bus fa-2x mb-2"></i>
                            <p>No vehicle information available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Route Information Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Route Information</h5>
                </div>
                <div class="card-body">
                    @if($tracking->route)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Route Name</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-route me-2"></i>{{ $tracking->route->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Distance</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-road me-2"></i>{{ $tracking->route->distance ?? 'N/A' }} km
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Start Point</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-play-circle me-2"></i>{{ $tracking->route->start_point ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">End Point</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-stop-circle me-2"></i>{{ $tracking->route->end_point ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($tracking->route->description)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <div class="form-control-plaintext bg-light p-3 rounded">
                                    <i class="fas fa-info-circle me-2"></i>{{ $tracking->route->description }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-route fa-2x mb-2"></i>
                            <p>No route information available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Driver Information Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Driver Information</h5>
                </div>
                <div class="card-body">
                    @if($tracking->driver)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Driver Name</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-user me-2"></i>{{ $tracking->driver->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-phone me-2"></i>{{ $tracking->driver->phone ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">License Number</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-id-card me-2"></i>{{ $tracking->driver->license_number ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">License Type</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-certificate me-2"></i>{{ $tracking->driver->license_type ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Experience</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-briefcase me-2"></i>{{ $tracking->driver->experience_level ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $tracking->driver->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($tracking->driver->status ?? 'Unknown') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-user fa-2x mb-2"></i>
                            <p>No driver information available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.transport.tracking.edit', $tracking->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Tracking
                        </a>
                        <form method="POST" action="{{ route('admin.transport.tracking.duplicate', $tracking->id) }}" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure you want to duplicate this tracking record?')">
                                <i class="fas fa-copy"></i> Duplicate Record
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.transport.tracking.destroy', $tracking->id) }}" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this tracking record?')">
                                <i class="fas fa-trash"></i> Delete Record
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Location Map Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Location Map</h5>
                </div>
                <div class="card-body">
                    @if($tracking->latitude && $tracking->longitude)
                        <div class="text-center">
                            <div class="mb-3">
                                <strong>Coordinates:</strong><br>
                                <small class="text-muted">{{ $tracking->formatted_location }}</small>
                            </div>
                            <a href="{{ $tracking->google_maps_url }}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-map"></i> View on Google Maps
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-map fa-2x mb-2"></i>
                            <p>No location data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Record Information Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Record Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Created At</label>
                        <p class="form-control-plaintext">
                            <i class="fas fa-calendar-plus me-2"></i>{{ $tracking->created_at->format('M d, Y H:i:s') }}
                        </p>
                    </div>

                    @if($tracking->createdBy)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created By</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-user-plus me-2"></i>{{ $tracking->createdBy->name }}
                            </p>
                        </div>
                    @endif

                    @if($tracking->updated_at && $tracking->updated_at != $tracking->created_at)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar-edit me-2"></i>{{ $tracking->updated_at->format('M d, Y H:i:s') }}
                            </p>
                        </div>
                    @endif

                    @if($tracking->updatedBy)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Updated By</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-user-edit me-2"></i>{{ $tracking->updatedBy->name }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Information Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Status Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="badge {{ $tracking->status_badge_class }} fs-6 p-3">
                                <i class="fas fa-{{ $tracking->status === 'on_time' ? 'check-circle' : ($tracking->status === 'delayed' ? 'clock' : ($tracking->status === 'early' ? 'fast-forward' : ($tracking->status === 'stopped' ? 'pause' : 'play'))) }} me-2"></i>
                                {{ $tracking->status_text }}
                            </span>
                        </div>
                        
                        @if($tracking->status === 'on_time')
                            <p class="text-success">
                                <i class="fas fa-check-circle me-2"></i>Vehicle is running on schedule
                            </p>
                        @elseif($tracking->status === 'delayed')
                            <p class="text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>Vehicle is behind schedule
                            </p>
                        @elseif($tracking->status === 'early')
                            <p class="text-warning">
                                <i class="fas fa-fast-forward me-2"></i>Vehicle is ahead of schedule
                            </p>
                        @elseif($tracking->status === 'stopped')
                            <p class="text-secondary">
                                <i class="fas fa-pause me-2"></i>Vehicle is currently stopped
                            </p>
                        @elseif($tracking->status === 'moving')
                            <p class="text-primary">
                                <i class="fas fa-play me-2"></i>Vehicle is in motion
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add any additional JavaScript functionality here if needed
$(document).ready(function() {
    // You can add interactive features here
    console.log('Tracking details page loaded');
});
</script>
@endpush

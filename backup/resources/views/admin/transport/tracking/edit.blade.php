@extends('admin.layout.app')

@section('title', 'Edit Tracking Record')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Edit Tracking Record</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.tracking.index') }}">Transport Tracking</a></li>
                    <li class="breadcrumb-item active">Edit Tracking</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.transport.tracking.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tracking Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.transport.tracking.update', $tracking->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_id" class="form-label">Vehicle <span class="text-danger">*</span></label>
                                    <select class="form-select @error('vehicle_id') is-invalid @enderror" 
                                            name="vehicle_id" id="vehicle_id" required>
                                        <option value="">Select Vehicle</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" 
                                                    {{ (old('vehicle_id', $tracking->vehicle_id) == $vehicle->id) ? 'selected' : '' }}>
                                                {{ $vehicle->vehicle_number }} - {{ $vehicle->model ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="route_id" class="form-label">Route <span class="text-danger">*</span></label>
                                    <select class="form-select @error('route_id') is-invalid @enderror" 
                                            name="route_id" id="route_id" required>
                                        <option value="">Select Route</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}" 
                                                    {{ (old('route_id', $tracking->route_id) == $route->id) ? 'selected' : '' }}>
                                                {{ $route->name }} ({{ $route->start_point }} → {{ $route->end_point }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('route_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                                    <select class="form-select @error('driver_id') is-invalid @enderror" 
                                            name="driver_id" id="driver_id" required>
                                        <option value="">Select Driver</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" 
                                                    {{ (old('driver_id', $tracking->driver_id) == $driver->id) ? 'selected' : '' }}>
                                                {{ $driver->name }} ({{ $driver->phone ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            name="status" id="status" required>
                                        <option value="">Select Status</option>
                                        <option value="on_time" {{ (old('status', $tracking->status) == 'on_time') ? 'selected' : '' }}>On Time</option>
                                        <option value="delayed" {{ (old('status', $tracking->status) == 'delayed') ? 'selected' : '' }}>Delayed</option>
                                        <option value="early" {{ (old('status', $tracking->status) == 'early') ? 'selected' : '' }}>Early</option>
                                        <option value="stopped" {{ (old('status', $tracking->status) == 'stopped') ? 'selected' : '' }}>Stopped</option>
                                        <option value="moving" {{ (old('status', $tracking->status) == 'moving') ? 'selected' : '' }}>Moving</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tracking_date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('tracking_date') is-invalid @enderror" 
                                           name="tracking_date" 
                                           id="tracking_date" 
                                           value="{{ old('tracking_date', $tracking->tracking_date->format('Y-m-d')) }}" 
                                           required>
                                    @error('tracking_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tracking_time" class="form-label">Time <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           class="form-control @error('tracking_time') is-invalid @enderror" 
                                           name="tracking_time" 
                                           id="tracking_time" 
                                           value="{{ old('tracking_time', $tracking->tracking_time->format('H:i')) }}" 
                                           required>
                                    @error('tracking_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">Latitude <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           step="0.00000001" 
                                           class="form-control @error('latitude') is-invalid @enderror" 
                                           name="latitude" 
                                           id="latitude" 
                                           value="{{ old('latitude', $tracking->latitude) }}" 
                                           placeholder="e.g., 28.6139" 
                                           required>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitude" class="form-label">Longitude <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           step="0.00000001" 
                                           class="form-control @error('longitude') is-invalid @enderror" 
                                           name="longitude" 
                                           id="longitude" 
                                           value="{{ old('longitude', $tracking->longitude) }}" 
                                           placeholder="e.g., 77.2090" 
                                           required>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="speed" class="form-label">Speed (km/h)</label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           class="form-control @error('speed') is-invalid @enderror" 
                                           name="speed" 
                                           id="speed" 
                                           value="{{ old('speed', $tracking->speed) }}" 
                                           placeholder="e.g., 45.5">
                                    @error('speed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      name="notes" 
                                      id="notes" 
                                      rows="4" 
                                      placeholder="Enter any additional notes about this tracking record...">{{ old('notes', $tracking->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Tracking Record
                            </button>
                            <a href="{{ route('admin.transport.tracking.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="getCurrentLocation()">
                            <i class="fas fa-map-marker-alt"></i> Get Current Location
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="setCurrentTime()">
                            <i class="fas fa-clock"></i> Set Current Time
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="setCurrentDate()">
                            <i class="fas fa-calendar"></i> Set Current Date
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Location Preview</h5>
                </div>
                <div class="card-body">
                    <div id="locationPreview" class="text-center text-muted">
                        <i class="fas fa-map fa-2x mb-2"></i>
                        <p>Enter coordinates to preview location</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            updateLocationPreview();
        }, function(error) {
            alert('Error getting location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function setCurrentTime() {
    const now = new Date();
    const timeString = now.toTimeString().slice(0, 5);
    document.getElementById('tracking_time').value = timeString;
}

function setCurrentDate() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tracking_date').value = today;
}

function updateLocationPreview() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    
    if (lat && lng) {
        const preview = document.getElementById('locationPreview');
        preview.innerHTML = `
            <div class="fw-bold">Coordinates</div>
            <div class="text-muted">${lat}, ${lng}</div>
            <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                <i class="fas fa-map"></i> View on Map
            </a>
        `;
    }
}

// Update location preview when coordinates change
document.getElementById('latitude').addEventListener('input', updateLocationPreview);
document.getElementById('longitude').addEventListener('input', updateLocationPreview);

// Initialize location preview if coordinates are already filled
document.addEventListener('DOMContentLoaded', function() {
    updateLocationPreview();
});
</script>
@endpush

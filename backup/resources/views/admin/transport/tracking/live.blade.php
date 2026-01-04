@extends('admin.layout.app')

@section('title', 'Live Transport Tracking')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Live Transport Tracking</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.tracking.index') }}">Transport Tracking</a></li>
                    <li class="breadcrumb-item active">Live Tracking</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <button class="btn btn-success" id="startLiveTracking">
                        <i class="fas fa-play"></i> Start Live Tracking
                    </button>
                    <button class="btn btn-danger" id="stopLiveTracking" disabled>
                        <i class="fas fa-stop"></i> Stop Live Tracking
                    </button>
                    <a href="{{ route('admin.transport.tracking.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-primary">
                            <i class="fas fa-bus"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3 id="totalActiveVehicles">0</h3>
                            <h6 class="text-muted">Active Vehicles</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-success">
                            <i class="fas fa-play"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3 id="movingVehicles">0</h3>
                            <h6 class="text-muted">Moving</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-warning">
                            <i class="fas fa-pause"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3 id="stoppedVehicles">0</h3>
                            <h6 class="text-muted">Stopped</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3 id="delayedVehicles">0</h3>
                            <h6 class="text-muted">Delayed</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Tracking Controls -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Live Tracking Controls</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="selectedVehicle" class="form-label">Select Vehicle</label>
                                <select class="form-select" id="selectedVehicle">
                                    <option value="">Choose a vehicle</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" 
                                                data-route="{{ $vehicle->assignedRoute->route_name ?? 'No Route' }}"
                                                data-driver="{{ $vehicle->driver->name ?? 'No Driver' }}">
                                            {{ $vehicle->vehicle_number }} - {{ $vehicle->model ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="trackingStatus" class="form-label">Status</label>
                                <select class="form-select" id="trackingStatus">
                                    <option value="stopped">Stopped</option>
                                    <option value="moving">Moving</option>
                                    <option value="on_time">On Time</option>
                                    <option value="delayed">Delayed</option>
                                    <option value="early">Early</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="trackingSpeed" class="form-label">Speed (km/h)</label>
                                <input type="number" class="form-control" id="trackingSpeed" min="0" max="120" value="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trackingLatitude" class="form-label">Latitude</label>
                                <input type="number" step="0.00000001" class="form-control" id="trackingLatitude" placeholder="e.g., 28.6139">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trackingLongitude" class="form-label">Longitude</label>
                                <input type="number" step="0.00000001" class="form-control" id="trackingLongitude" placeholder="e.g., 77.2090">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="trackingNotes" class="form-label">Notes</label>
                                <textarea class="form-control" id="trackingNotes" rows="2" placeholder="Enter any notes about this tracking update..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" id="updateLocation">
                                        <i class="fas fa-map-marker-alt"></i> Update Location
                                    </button>
                                    <button class="btn btn-outline-primary" id="getCurrentLocation">
                                        <i class="fas fa-crosshairs"></i> Get Current Location
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Simulation Controls</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="simulationDuration" class="form-label">Simulation Duration (minutes)</label>
                        <select class="form-select" id="simulationDuration">
                            <option value="5">5 minutes</option>
                            <option value="10">10 minutes</option>
                            <option value="15">15 minutes</option>
                            <option value="30">30 minutes</option>
                            <option value="60">60 minutes</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-info" id="startSimulation">
                            <i class="fas fa-play-circle"></i> Start Simulation
                        </button>
                        <button class="btn btn-warning" id="stopSimulation">
                            <i class="fas fa-stop-circle"></i> Stop Simulation
                        </button>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                Simulation will create realistic tracking data for testing purposes.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Vehicle Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Live Vehicle Status</h5>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2" id="connectionStatus">
                            <i class="fas fa-circle"></i> Connected
                        </span>
                        <small class="text-muted" id="lastUpdate">Last updated: Never</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="liveVehiclesTable">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Route</th>
                                    <th>Status</th>
                                    <th>Speed</th>
                                    <th>Location</th>
                                    <th>Last Update</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="liveVehiclesTableBody">
                                <!-- Live data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tracking Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Tracking Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="recentActivityTable">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Vehicle</th>
                                    <th>Status</th>
                                    <th>Speed</th>
                                    <th>Location</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="recentActivityTableBody">
                                @forelse($recentTrackings as $tracking)
                                    <tr>
                                        <td>{{ $tracking->tracking_time->format('H:i:s') }}</td>
                                        <td>{{ $tracking->vehicle->vehicle_number ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $tracking->status_badge_class }}">
                                                {{ $tracking->status_text }}
                                            </span>
                                        </td>
                                        <td>{{ $tracking->formatted_speed }}</td>
                                        <td>{{ $tracking->formatted_location }}</td>
                                        <td>{{ $tracking->notes ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No recent tracking activity
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let liveTrackingInterval;
    let isLiveTracking = false;
    let connectionStatus = true;

    // Initialize live tracking
    loadLiveData();
    
    // Set up auto-refresh every 5 seconds
    liveTrackingInterval = setInterval(loadLiveData, 5000);

    // Start Live Tracking
    $('#startLiveTracking').on('click', function() {
        const vehicleId = $('#selectedVehicle').val();
        if (!vehicleId) {
            Swal.fire('Error', 'Please select a vehicle first', 'error');
            return;
        }

        $.ajax({
            url: '{{ route("admin.transport.tracking.live.start") }}',
            method: 'POST',
            data: {
                vehicle_id: vehicleId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    isLiveTracking = true;
                    $('#startLiveTracking').prop('disabled', true);
                    $('#stopLiveTracking').prop('disabled', false);
                    Swal.fire('Success', response.message, 'success');
                    loadLiveData();
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Failed to start live tracking';
                Swal.fire('Error', error, 'error');
            }
        });
    });

    // Stop Live Tracking
    $('#stopLiveTracking').on('click', function() {
        const vehicleId = $('#selectedVehicle').val();
        if (!vehicleId) {
            Swal.fire('Error', 'Please select a vehicle first', 'error');
            return;
        }

        $.ajax({
            url: '{{ route("admin.transport.tracking.live.stop") }}',
            method: 'POST',
            data: {
                vehicle_id: vehicleId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    isLiveTracking = false;
                    $('#startLiveTracking').prop('disabled', false);
                    $('#stopLiveTracking').prop('disabled', true);
                    Swal.fire('Success', response.message, 'success');
                    loadLiveData();
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Failed to stop live tracking';
                Swal.fire('Error', error, 'error');
            }
        });
    });

    // Update Location
    $('#updateLocation').on('click', function() {
        const vehicleId = $('#selectedVehicle').val();
        const latitude = $('#trackingLatitude').val();
        const longitude = $('#trackingLongitude').val();
        const speed = $('#trackingSpeed').val();
        const status = $('#trackingStatus').val();
        const notes = $('#trackingNotes').val();

        if (!vehicleId || !latitude || !longitude) {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
            return;
        }

        $.ajax({
            url: '{{ route("admin.transport.tracking.live.update-location") }}',
            method: 'POST',
            data: {
                vehicle_id: vehicleId,
                latitude: latitude,
                longitude: longitude,
                speed: speed,
                status: status,
                notes: notes,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    loadLiveData();
                    // Clear form
                    $('#trackingNotes').val('');
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Failed to update location';
                Swal.fire('Error', error, 'error');
            }
        });
    });

    // Get Current Location
    $('#getCurrentLocation').on('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#trackingLatitude').val(position.coords.latitude);
                $('#trackingLongitude').val(position.coords.longitude);
            }, function(error) {
                Swal.fire('Error', 'Error getting location: ' + error.message, 'error');
            });
        } else {
            Swal.fire('Error', 'Geolocation is not supported by this browser', 'error');
        }
    });

    // Start Simulation
    $('#startSimulation').on('click', function() {
        const vehicleId = $('#selectedVehicle').val();
        const duration = $('#simulationDuration').val();

        if (!vehicleId) {
            Swal.fire('Error', 'Please select a vehicle first', 'error');
            return;
        }

        $.ajax({
            url: '{{ route("admin.transport.tracking.live.simulate") }}',
            method: 'POST',
            data: {
                vehicle_id: vehicleId,
                duration: duration,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    loadLiveData();
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Failed to start simulation';
                Swal.fire('Error', error, 'error');
            }
        });
    });

    // Load Live Data
    function loadLiveData() {
        console.log('Loading live data...');
        $.ajax({
            url: '{{ route("admin.transport.tracking.live.data") }}',
            method: 'GET',
            success: function(response) {
                console.log('Live data response:', response);
                if (response.error) {
                    console.error('Error from server:', response.error);
                    updateConnectionStatus(false);
                    return;
                }
                updateStatistics(response.stats);
                updateLiveVehiclesTable(response.active_vehicles);
                updateRecentActivityTable(response.trackings);
                updateConnectionStatus(true);
                $('#lastUpdate').text('Last updated: ' + new Date().toLocaleTimeString());
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error, xhr.responseText);
                updateConnectionStatus(false);
            }
        });
    }

    // Update Statistics
    function updateStatistics(stats) {
        $('#totalActiveVehicles').text(stats.total_active_vehicles);
        $('#movingVehicles').text(stats.moving_vehicles);
        $('#stoppedVehicles').text(stats.stopped_vehicles);
        $('#delayedVehicles').text(stats.delayed_vehicles);
    }

    // Update Live Vehicles Table
    function updateLiveVehiclesTable(vehicles) {
        const tbody = $('#liveVehiclesTableBody');
        tbody.empty();

        vehicles.forEach(function(vehicle) {
            const tracking = vehicle.latest_tracking;
            const statusBadge = tracking ? getStatusBadge(tracking.status) : '<span class="badge bg-secondary">No Data</span>';
            const speed = tracking ? tracking.speed + ' km/h' : 'N/A';
            const location = tracking ? tracking.formatted_location : 'N/A';
            const lastUpdate = tracking ? tracking.tracking_time : 'Never';

            const row = `
                <tr>
                    <td>
                        <div class="fw-bold">${vehicle.vehicle_number}</div>
                        <small class="text-muted">${vehicle.model || 'N/A'}</small>
                    </td>
                    <td>${vehicle.driver ? vehicle.driver.name : 'No Driver'}</td>
                    <td>${vehicle.assigned_route ? vehicle.assigned_route.route_name : 'No Route'}</td>
                    <td>${statusBadge}</td>
                    <td>${speed}</td>
                    <td>${location}</td>
                    <td>${lastUpdate}</td>
                    <td>
                        ${tracking && tracking.google_maps_url ? 
                            `<a href="${tracking.google_maps_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-map"></i>
                            </a>` : 
                            '<span class="text-muted">N/A</span>'
                        }
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Update Recent Activity Table
    function updateRecentActivityTable(trackings) {
        const tbody = $('#recentActivityTableBody');
        tbody.empty();

        trackings.forEach(function(tracking) {
            const row = `
                <tr>
                    <td>${tracking.tracking_time}</td>
                    <td>${tracking.vehicle ? tracking.vehicle.vehicle_number : 'N/A'}</td>
                    <td>${getStatusBadge(tracking.status)}</td>
                    <td>${tracking.speed} km/h</td>
                    <td>${tracking.formatted_location}</td>
                    <td>${tracking.notes || '-'}</td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Get Status Badge HTML
    function getStatusBadge(status) {
        const statusConfig = {
            'on_time': { class: 'bg-success', text: 'On Time', icon: 'fas fa-check-circle' },
            'delayed': { class: 'bg-danger', text: 'Delayed', icon: 'fas fa-clock' },
            'early': { class: 'bg-warning', text: 'Early', icon: 'fas fa-fast-forward' },
            'stopped': { class: 'bg-secondary', text: 'Stopped', icon: 'fas fa-pause' },
            'moving': { class: 'bg-primary', text: 'Moving', icon: 'fas fa-play' }
        };
        
        const config = statusConfig[status] || { class: 'bg-secondary', text: 'Unknown', icon: 'fas fa-question' };
        return `<span class="badge ${config.class}"><i class="${config.icon} me-1"></i>${config.text}</span>`;
    }

    // Update Connection Status
    function updateConnectionStatus(connected) {
        const statusElement = $('#connectionStatus');
        if (connected) {
            statusElement.removeClass('bg-danger').addClass('bg-success');
            statusElement.html('<i class="fas fa-circle"></i> Connected');
        } else {
            statusElement.removeClass('bg-success').addClass('bg-danger');
            statusElement.html('<i class="fas fa-circle"></i> Disconnected');
        }
    }

    // Clean up on page unload
    $(window).on('beforeunload', function() {
        if (liveTrackingInterval) {
            clearInterval(liveTrackingInterval);
        }
    });
});
</script>
@endpush

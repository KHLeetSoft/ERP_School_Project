@extends('admin.layout.app')

@section('title', 'Transport Tracking Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Transport Tracking Dashboard</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.tracking.index') }}">Transport Tracking</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transport.tracking.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All
                    </a>
                    <a href="{{ route('admin.transport.tracking.live') }}" class="btn btn-success">
                        <i class="fas fa-satellite-dish"></i> Live Tracking
                    </a>
                    <a href="{{ route('admin.transport.tracking.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Tracking
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-primary">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{ $stats['total_trackings'] ?? 0 }}</h3>
                            <h6 class="text-muted">Total Trackings</h6>
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
                            <i class="fas fa-calendar-day"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{ $stats['today_trackings'] ?? 0 }}</h3>
                            <h6 class="text-muted">Today's Trackings</h6>
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
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{ $stats['on_time_percentage'] ?? 0 }}%</h3>
                            <h6 class="text-muted">On-Time Rate</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-info">
                            <i class="fas fa-bus"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{ $stats['active_vehicles'] ?? 0 }}</h3>
                            <h6 class="text-muted">Active Vehicles</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{ $stats['delayed_trackings'] ?? 0 }}</h3>
                            <h6 class="text-muted">Delayed Trackings</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-secondary">
                            <i class="fas fa-pause"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{ $stats['stopped_vehicles'] ?? 0 }}</h3>
                            <h6 class="text-muted">Stopped Vehicles</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-primary">
                            <i class="fas fa-play"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{ $stats['moving_vehicles'] ?? 0 }}</h3>
                            <h6 class="text-muted">Moving Vehicles</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-info">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{ $stats['monthly_trackings'] ?? 0 }}</h3>
                            <h6 class="text-muted">This Month</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Monthly Tracking Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Vehicle Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="vehicleStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Tracking and Active Vehicles -->
    <div class="row">
        <!-- Today's Tracking -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Today's Tracking Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Route</th>
                                    <th>Driver</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Speed</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayTrackings as $tracking)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->vehicle->vehicle_number ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $tracking->vehicle->model ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->route->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->driver->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->tracking_time->format('H:i:s') }}</div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $tracking->status_badge_class }}">
                                                {{ $tracking->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $tracking->formatted_speed }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->formatted_location }}</div>
                                            @if($tracking->google_maps_url)
                                                <a href="{{ $tracking->google_maps_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-map"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-day fa-2x mb-2"></i>
                                                <p>No tracking records for today</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Vehicles -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Active Vehicles</h5>
                </div>
                <div class="card-body">
                    @forelse($activeVehicles as $vehicle)
                        <div class="d-flex align-items-center mb-3 p-3 border rounded">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm bg-primary text-white rounded-circle">
                                    <i class="fas fa-bus"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $vehicle->vehicle_number }}</h6>
                                <p class="mb-1 text-muted">{{ $vehicle->model ?? 'N/A' }}</p>
                                @if($vehicle->driver)
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> {{ $vehicle->driver->name }}
                                    </small>
                                @endif
                                @if($vehicle->assignedRoute)
                                    <br><small class="text-muted">
                                        <i class="fas fa-route"></i> {{ $vehicle->assignedRoute->name }}
                                    </small>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-bus fa-2x mb-2"></i>
                                <p>No active vehicles</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tracking Records -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Tracking Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Route</th>
                                    <th>Driver</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Speed</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTrackings as $tracking)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->vehicle->vehicle_number ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $tracking->vehicle->model ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->route->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->driver->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->tracking_date->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $tracking->tracking_time->format('H:i:s') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $tracking->status_badge_class }}">
                                                {{ $tracking->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $tracking->formatted_speed }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $tracking->formatted_location }}</div>
                                            @if($tracking->google_maps_url)
                                                <a href="{{ $tracking->google_maps_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-map"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.transport.tracking.show', $tracking->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.transport.tracking.edit', $tracking->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                                <p>No recent tracking records</p>
                                            </div>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($stats['status_data'] ?? []);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(key => key.replace('_', ' ').toUpperCase()),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    '#28a745', // on_time - green
                    '#dc3545', // delayed - red
                    '#ffc107', // early - yellow
                    '#6c757d', // stopped - gray
                    '#007bff'  // moving - blue
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = [{{ implode(',', $stats['monthly_data'] ?? [0,0,0,0,0,0,0,0,0,0,0,0]) }}];
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Tracking Records',
                data: monthlyData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Vehicle Status Chart
    const vehicleStatusCtx = document.getElementById('vehicleStatusChart').getContext('2d');
    const vehicleStatusData = {
        'Moving': {{ $stats['moving_vehicles'] ?? 0 }},
        'Stopped': {{ $stats['stopped_vehicles'] ?? 0 }},
        'Delayed': {{ $stats['delayed_trackings'] ?? 0 }}
    };
    
    new Chart(vehicleStatusCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(vehicleStatusData),
            datasets: [{
                data: Object.values(vehicleStatusData),
                backgroundColor: [
                    '#007bff', // moving - blue
                    '#6c757d', // stopped - gray
                    '#dc3545'  // delayed - red
                ],
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endsection

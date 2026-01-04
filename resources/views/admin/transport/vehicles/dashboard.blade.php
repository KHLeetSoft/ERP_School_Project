@extends('admin.layout.app')

@section('title', 'Transport Vehicles Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">🚗 Transport Vehicles Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Transport Vehicles Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.transport.vehicles.index') }}" class="btn btn-primary">
                <i class="fas fa-list me-2"></i> View All Vehicles
            </a>
            <a href="{{ route('admin.transport.vehicles.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i> Add Vehicle
            </a>
        </div>
    </div>

    <!-- Main Statistics Cards -->
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
                    <small class="text-white-50">Fleet Size</small>
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
                    <small class="text-white-50">In Service</small>
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
                    <small class="text-white-50">Ready for Use</small>
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
                    <small class="text-dark">Under Service</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="row g-4 mb-4">
        <!-- Total Capacity -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-primary text-white mb-3 mx-auto">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <h4 class="fw-bold text-primary mb-1">{{ number_format($stats['total_capacity'] ?? 0) }}</h4>
                    <p class="mb-0 text-muted">Total Seating Capacity</p>
                </div>
            </div>
        </div>

        <!-- Average Fuel Efficiency -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-success text-white mb-3 mx-auto">
                        <i class="fas fa-gas-pump fa-lg"></i>
                    </div>
                    <h4 class="fw-bold text-success mb-1">{{ number_format($stats['avg_fuel_efficiency'] ?? 0, 1) }}</h4>
                    <p class="mb-0 text-muted">Avg Fuel Efficiency (km/l)</p>
                </div>
            </div>
        </div>

        <!-- Documents Expiring Soon -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-warning text-white mb-3 mx-auto">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <h4 class="fw-bold text-warning mb-1">{{ number_format($stats['expiring_documents'] ?? 0) }}</h4>
                    <p class="mb-0 text-muted">Documents Expiring Soon</p>
                </div>
            </div>
        </div>

        <!-- Fleet Utilization -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-info text-white mb-3 mx-auto">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <h4 class="fw-bold text-info mb-1">{{ number_format($stats['fleet_utilization'] ?? 0, 1) }}%</h4>
                    <p class="mb-0 text-muted">Fleet Utilization Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Row -->
    <div class="row g-4 mb-4">
        <!-- Vehicle Type Distribution -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Vehicle Type Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="vehicleTypeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Overview -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Vehicle Status Overview
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="row g-4 mb-4">
        <!-- Recent Vehicle Activities -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Vehicle Activities
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @forelse($recentActivities ?? [] as $activity)
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon me-3">
                                <i class="fas fa-{{ $activity['icon'] }} fa-lg text-{{ $activity['color'] }}"></i>
                            </div>
                            <div class="activity-content flex-grow-1">
                                <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                <p class="mb-0 text-muted">{{ $activity['description'] }}</p>
                                <small class="text-muted">{{ $activity['time'] }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No recent activities</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.transport.vehicles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Vehicle
                        </a>
                        <a href="{{ route('admin.transport.vehicles.index') }}" class="btn btn-info">
                            <i class="fas fa-list me-2"></i>View All Vehicles
                        </a>
                        <button class="btn btn-success" onclick="generateReport()">
                            <i class="fas fa-file-alt me-2"></i>Generate Report
                        </button>
                        <button class="btn btn-warning" onclick="scheduleMaintenance()">
                            <i class="fas fa-tools me-2"></i>Schedule Maintenance
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Performance Metrics -->
    <div class="row g-4 mb-4">
        <!-- Top Performing Vehicles -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Top Performing Vehicles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="performance-list">
                        @forelse($topVehicles ?? [] as $index => $vehicle)
                        <div class="performance-item d-flex align-items-center mb-3">
                            <div class="rank-badge me-3">
                                <span class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'dark') }}">
                                    #{{ $index + 1 }}
                                </span>
                            </div>
                            <div class="vehicle-info flex-grow-1">
                                <h6 class="mb-1">{{ $vehicle['vehicle_number'] }}</h6>
                                <p class="mb-0 text-muted">{{ $vehicle['brand'] }} {{ $vehicle['model'] }}</p>
                            </div>
                            <div class="performance-score text-end">
                                <span class="badge bg-success">{{ $vehicle['score'] }}%</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No performance data available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Alerts -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Maintenance Alerts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert-list">
                        @forelse($maintenanceAlerts ?? [] as $alert)
                        <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show mb-2">
                            <i class="fas fa-{{ $alert['icon'] }} me-2"></i>
                            <strong>{{ $alert['title'] }}</strong>
                            <p class="mb-1">{{ $alert['message'] }}</p>
                            <small>{{ $alert['due_date'] }}</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                            <p class="text-success">All vehicles are in good condition!</p>
                        </div>
                        @endforelse
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
    // Vehicle Type Distribution Chart
    const vehicleTypeCtx = document.getElementById('vehicleTypeChart').getContext('2d');
    new Chart(vehicleTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Bus', 'Minibus', 'Van', 'Car', 'Truck'],
            datasets: [{
                data: [{{ $stats['vehicle_type_counts']['bus'] ?? 0 }}, 
                       {{ $stats['vehicle_type_counts']['minibus'] ?? 0 }}, 
                       {{ $stats['vehicle_type_counts']['van'] ?? 0 }}, 
                       {{ $stats['vehicle_type_counts']['car'] ?? 0 }}, 
                       {{ $stats['vehicle_type_counts']['truck'] ?? 0 }}],
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#17a2b8', '#6c757d'
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

    // Status Overview Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Active', 'Inactive', 'Maintenance', 'Repair', 'Offline'],
            datasets: [{
                label: 'Vehicle Count',
                data: [{{ $stats['status_counts']['active'] ?? 0 }}, 
                       {{ $stats['status_counts']['inactive'] ?? 0 }}, 
                       {{ $stats['status_counts']['maintenance'] ?? 0 }}, 
                       {{ $stats['status_counts']['repair'] ?? 0 }}, 
                       {{ $stats['status_counts']['offline'] ?? 0 }}],
                backgroundColor: [
                    '#28a745', '#6c757d', '#ffc107', '#dc3545', '#6f42c1'
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

function generateReport() {
    toastr.info('Generating vehicle report...');
    // Add report generation logic here
}

function scheduleMaintenance() {
    toastr.info('Opening maintenance scheduler...');
    // Add maintenance scheduling logic here
}
</script>
@endsection

@section('styles')
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

.activity-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.performance-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.performance-item:last-child {
    border-bottom: none;
}

.rank-badge .badge {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.alert-list .alert {
    border-radius: 8px;
    border: none;
}

.card {
    border-radius: 12px;
    border: none;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>
@endsection

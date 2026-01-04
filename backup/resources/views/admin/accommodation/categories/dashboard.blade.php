@extends('admin.layout.app')

@section('title', 'Hostel Categories Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Hostel Categories Dashboard</h1>
            <p class="mb-0">Overview of hostel accommodation categories and performance</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.accommodation.categories.index') }}" class="btn btn-primary">
                <i class="bx bx-list-ul"></i> View All Categories
            </a>
            <a href="{{ route('admin.accommodation.categories.create') }}" class="btn btn-success">
                <i class="bx bx-plus"></i> Add Category
            </a>
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_categories'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-home text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_categories'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Capacity</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_capacity'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-group text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Occupancy Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['occupancy_rate'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-pie-chart text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Available Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_available_rooms'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-bed text-secondary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Occupied Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_occupied_rooms'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-user-check text-danger" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Avg Monthly Fee</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['average_monthly_fee'], 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-rupee text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['total_monthly_revenue'], 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-wallet text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Categories by Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Categories</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Capacity Overview</h6>
                </div>
                <div class="card-body">
                    <canvas id="capacityChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Categories -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Categories</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th>Monthly Fee</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCategories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $category->main_image }}" alt="{{ $category->name }}" class="rounded me-2" width="40" height="40">
                                            <div>
                                                <div class="fw-bold">{{ $category->name }}</div>
                                                <small class="text-muted">{{ Str::limit($category->description, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $category->formatted_monthly_fee }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $category->capacity }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $category->status_badge_class }}">{{ $category->status_text }}</span>
                                    </td>
                                    <td>{{ $category->created_at->format('M d, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No categories found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.accommodation.categories.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add New Category
                        </a>
                        <a href="{{ route('admin.accommodation.categories.index') }}" class="btn btn-outline-primary">
                            <i class="bx bx-list-ul"></i> View All Categories
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="bx bx-download"></i> Export Data
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="bx bx-bar-chart"></i> Generate Report
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Active</span>
                            <span class="fw-bold">{{ $categoriesByStatus['active'] ?? 0 }}</span>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-success" style="width: {{ $stats['total_categories'] > 0 ? (($categoriesByStatus['active'] ?? 0) / $stats['total_categories']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Inactive</span>
                            <span class="fw-bold">{{ $categoriesByStatus['inactive'] ?? 0 }}</span>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-secondary" style="width: {{ $stats['total_categories'] > 0 ? (($categoriesByStatus['inactive'] ?? 0) / $stats['total_categories']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Maintenance</span>
                            <span class="fw-bold">{{ $categoriesByStatus['maintenance'] ?? 0 }}</span>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-warning" style="width: {{ $stats['total_categories'] > 0 ? (($categoriesByStatus['maintenance'] ?? 0) / $stats['total_categories']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Status Chart
    var statusCtx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive', 'Maintenance'],
            datasets: [{
                data: [{{ $stats['active_categories'] }}, {{ $stats['inactive_categories'] }}, {{ $stats['maintenance_categories'] }}],
                backgroundColor: ['#28a745', '#6c757d', '#ffc107'],
                borderWidth: 2
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
    var monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    var monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Categories Created',
                data: [{{ implode(',', $stats['monthly_data']) }}],
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
            }
        }
    });

    // Capacity Chart
    var capacityCtx = document.getElementById('capacityChart').getContext('2d');
    var capacityChart = new Chart(capacityCtx, {
        type: 'bar',
        data: {
            labels: ['Total Capacity', 'Available', 'Occupied'],
            datasets: [{
                label: 'Rooms',
                data: [{{ $stats['total_capacity'] }}, {{ $stats['total_available_rooms'] }}, {{ $stats['total_occupied_rooms'] }}],
                backgroundColor: ['#007bff', '#28a745', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush

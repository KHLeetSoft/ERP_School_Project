@extends('admin.layout.app')

@section('title', 'Transport Tracking')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Transport Tracking</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.assign.index') }}">Transport</a></li>
                    <li class="breadcrumb-item active">Tracking</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transport.tracking.dashboard') }}" class="btn btn-info">
                        <i class="fas fa-chart-line"></i> Dashboard
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

    <!-- Statistics Cards -->
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
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Monthly Tracking Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tracking Records</h5>
                    <div class="d-flex gap-2">
                        <!-- Bulk Actions -->
                        <div class="bulk-actions d-none">
                            <select class="form-select form-select-sm" id="bulkAction">
                                <option value="">Bulk Actions</option>
                                <option value="delete">Delete Selected</option>
                                <option value="export">Export Selected</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-primary" id="applyBulkAction">Apply</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.transport.tracking.index') }}" class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="">All Status</option>
                                <option value="on_time" {{ request('status') == 'on_time' ? 'selected' : '' }}>On Time</option>
                                <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                                <option value="early" {{ request('status') == 'early' ? 'selected' : '' }}>Early</option>
                                <option value="stopped" {{ request('status') == 'stopped' ? 'selected' : '' }}>Stopped</option>
                                <option value="moving" {{ request('status') == 'moving' ? 'selected' : '' }}>Moving</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="vehicle_id" class="form-label">Vehicle</label>
                            <select class="form-select" name="vehicle_id" id="vehicle_id">
                                <option value="">All Vehicles</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->vehicle_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="route_id" class="form-label">Route</label>
                            <select class="form-select" name="route_id" id="route_id">
                                <option value="">All Routes</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                                        {{ $route->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="date_from" id="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" name="date_to" id="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('admin.transport.tracking.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Tracking Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="trackingTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Vehicle</th>
                                    <th>Route</th>
                                    <th>Driver</th>
                                    <th>Date & Time</th>
                                    <th>Location</th>
                                    <th>Speed</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
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

    // Initialize DataTables
    let trackingTable;
    $(document).ready(function() {
        trackingTable = $('#trackingTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.transport.tracking.index") }}',
                data: function(d) {
                    d.status = $('#statusFilter').val();
                    d.vehicle_id = $('#vehicleFilter').val();
                    d.route_id = $('#routeFilter').val();
                    d.date_from = $('#dateFrom').val();
                    d.date_to = $('#dateTo').val();
                }
            },
            dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +             // Show entries
                '<"col-md-6 text-end"f>' +    // Search
            '>' +
            '<"row mb-3"' +
                '<"col-12 text-end"B>' +      // Export buttons
            '>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            {
                extend: 'csv',
                className: 'btn btn-success btn-sm rounded-pill',
                text: '<i class="fas fa-file-csv me-1"></i> CSV'
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm rounded-pill',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF'
            },
            {
                extend: 'print',
                className: 'btn btn-warning btn-sm rounded-pill',
                text: '<i class="fas fa-print me-1"></i> Print'
            },
            {
                extend: 'copy',
                className: 'btn btn-info btn-sm rounded-pill',
                text: '<i class="fas fa-copy me-1"></i> Copy'
            }
        ],
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<input type="checkbox" class="form-check-input tracking-checkbox" value="' + row.id + '">';
                    }
                },
                {
                    data: 'vehicle',
                    name: 'vehicle',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'route',
                    name: 'route',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'driver',
                    name: 'driver',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'datetime',
                    name: 'datetime',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'location',
                    name: 'location',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'speed',
                    name: 'speed',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
        });
    });

    // Filter functionality
    $('#statusFilter, #vehicleFilter, #routeFilter, #dateFrom, #dateTo').on('change', function() {
        trackingTable.draw();
    });

    // Delete function
    window.deleteTracking = function(id) {
        if (confirm('Are you sure you want to delete this tracking record?')) {
            const form = $('<form>', {
                method: 'POST',
                action: '{{ route("admin.transport.tracking.index") }}/' + id
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: '{{ csrf_token() }}'
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: '_method',
                value: 'DELETE'
            }));

            $('body').append(form);
            form.submit();
        }
    };

    // Bulk Actions
    $('#selectAll').on('change', function() {
        $('.tracking-checkbox').prop('checked', this.checked);
        toggleBulkActions();
    });

    $(document).on('change', '.tracking-checkbox', function() {
        toggleBulkActions();
    });

    function toggleBulkActions() {
        const checkedCount = $('.tracking-checkbox:checked').length;
        if (checkedCount > 0) {
            $('.bulk-actions').removeClass('d-none');
        } else {
            $('.bulk-actions').addClass('d-none');
        }
    }

    $('#applyBulkAction').on('click', function() {
        const action = $('#bulkAction').val();
        const selectedIds = $('.tracking-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (!action || selectedIds.length === 0) {
            Swal.fire('Error', 'Please select an action and at least one record.', 'error');
            return;
        }

        Swal.fire({
            title: 'Confirm Action',
            text: `Are you sure you want to ${action} ${selectedIds.length} record(s)?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ route("admin.transport.tracking.bulk-action") }}'
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'action',
                    value: action
                }));

                selectedIds.forEach(id => {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tracking_ids[]',
                        value: id
                    }));
                });

                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endsection

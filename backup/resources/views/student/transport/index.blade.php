@extends('student.layout.app')

@section('title', 'Transport Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-bus me-2"></i>Transport Dashboard
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Transport</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Transport Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-route me-2"></i>Current Transport Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Route Name:</label>
                                <p class="mb-0">{{ $transportInfo['route_name'] ?? 'Not Assigned' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Vehicle Number:</label>
                                <p class="mb-0">{{ $transportInfo['vehicle_number'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pickup Point:</label>
                                <p class="mb-0">{{ $transportInfo['pickup_point'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Drop Point:</label>
                                <p class="mb-0">{{ $transportInfo['drop_point'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Driver Name:</label>
                                <p class="mb-0">{{ $transportInfo['driver_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Driver Phone:</label>
                                <p class="mb-0">
                                    <a href="tel:{{ $transportInfo['driver_phone'] ?? '' }}" class="text-decoration-none">
                                        {{ $transportInfo['driver_phone'] ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pickup Time:</label>
                                <p class="mb-0">{{ $transportInfo['pickup_time'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Drop Time:</label>
                                <p class="mb-0">{{ $transportInfo['drop_time'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Status:</strong> {{ $transportInfo['status'] ?? 'Unknown' }} | 
                                <strong>Monthly Fee:</strong> ${{ number_format($transportInfo['monthly_fee'] ?? 0, 2) }} | 
                                <strong>Next Payment Due:</strong> {{ $transportInfo['next_payment_due'] ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $transportStats['total_trips'] ?? 0 }}</h3>
                            <p class="mb-0">Total Trips</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-route fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $transportStats['this_month'] ?? 0 }}</h3>
                            <p class="mb-0">This Month</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ number_format($transportStats['attendance_rate'] ?? 0, 1) }}%</h3>
                            <p class="mb-0">Attendance Rate</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ number_format($transportStats['punctuality_rate'] ?? 0, 1) }}%</h3>
                            <p class="mb-0">Punctuality Rate</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming and Recent Trips -->
    <div class="row">
        <!-- Upcoming Trips -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Upcoming Trips
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($upcomingTrips) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Pickup</th>
                                        <th>Drop</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingTrips as $trip)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($trip['date'])->format('M d, Y') }}</td>
                                        <td>{{ $trip['pickup_time'] }}</td>
                                        <td>{{ $trip['drop_time'] }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $trip['status'] }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No upcoming trips scheduled</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Trips -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Trips
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($recentTrips) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Pickup</th>
                                        <th>Drop</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTrips as $trip)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($trip['date'])->format('M d, Y') }}</td>
                                        <td>{{ $trip['pickup_time'] }}</td>
                                        <td>{{ $trip['drop_time'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $trip['status'] === 'Completed' ? 'success' : 'warning' }}">
                                                {{ $trip['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent trips found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.transport.routes') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-route me-2"></i>View Routes
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.transport.schedule') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar-alt me-2"></i>View Schedule
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.transport.history') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-history me-2"></i>Trip History
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.transport.profile') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-user-cog me-2"></i>Transport Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

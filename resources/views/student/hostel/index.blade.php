@extends('student.layout.app')

@section('title', 'Hostel Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-home me-2"></i>Hostel Dashboard
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Hostel</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Hostel Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>Hostel Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hostel Name:</label>
                                <p class="mb-0">{{ $hostelInfo['hostel_name'] ?? 'Not Assigned' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hostel Type:</label>
                                <p class="mb-0">{{ $hostelInfo['hostel_type'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address:</label>
                                <p class="mb-0">{{ $hostelInfo['address'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Contact Phone:</label>
                                <p class="mb-0">
                                    <a href="tel:{{ $hostelInfo['contact_phone'] ?? '' }}" class="text-decoration-none">
                                        {{ $hostelInfo['contact_phone'] ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Warden Name:</label>
                                <p class="mb-0">{{ $hostelInfo['warden_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Warden Phone:</label>
                                <p class="mb-0">
                                    <a href="tel:{{ $hostelInfo['warden_phone'] ?? '' }}" class="text-decoration-none">
                                        {{ $hostelInfo['warden_phone'] ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Check-in Time:</label>
                                <p class="mb-0">{{ $hostelInfo['check_in_time'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Check-out Time:</label>
                                <p class="mb-0">{{ $hostelInfo['check_out_time'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Status:</strong> {{ $hostelInfo['status'] ?? 'Unknown' }} | 
                                <strong>Assistant Warden:</strong> {{ $hostelInfo['assistant_warden'] ?? 'N/A' }} | 
                                <strong>Contact:</strong> {{ $hostelInfo['assistant_phone'] ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room and Meal Information -->
    <div class="row mb-4">
        <!-- Room Information -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bed me-2"></i>Room Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Room Number:</label>
                        <p class="mb-0 fs-5 text-primary">{{ $roomInfo['room_number'] ?? 'Not Assigned' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Location:</label>
                        <p class="mb-0">{{ $roomInfo['floor'] ?? 'N/A' }}, {{ $roomInfo['block'] ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Room Type:</label>
                        <p class="mb-0">{{ $roomInfo['room_type'] ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Occupancy:</label>
                        <p class="mb-0">{{ $roomInfo['current_occupancy'] ?? 0 }}/{{ $roomInfo['capacity'] ?? 0 }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Condition:</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $roomInfo['room_condition'] === 'Good' ? 'success' : 'warning' }}">
                                {{ $roomInfo['room_condition'] ?? 'Unknown' }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Amenities:</label>
                        <div class="d-flex flex-wrap gap-1">
                            @if(isset($roomInfo['amenities']))
                                @foreach($roomInfo['amenities'] as $amenity)
                                    <span class="badge bg-info">{{ $amenity }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meal Information -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-utensils me-2"></i>Meal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meal Plan:</label>
                        <p class="mb-0 fs-5 text-primary">{{ $mealInfo['meal_plan'] ?? 'Not Assigned' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Dining Hall:</label>
                        <p class="mb-0">{{ $mealInfo['dining_hall'] ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Special Diet:</label>
                        <p class="mb-0">{{ $mealInfo['special_diet'] ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Monthly Fee:</label>
                        <p class="mb-0 fs-5 text-success">${{ number_format($mealInfo['monthly_meal_fee'] ?? 0, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meal Times:</label>
                        @if(isset($mealInfo['meal_times']))
                            <ul class="list-unstyled mb-0">
                                @foreach($mealInfo['meal_times'] as $meal => $time)
                                    <li><strong>{{ ucfirst($meal) }}:</strong> {{ $time }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">N/A</p>
                        @endif
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
                            <h3 class="mb-0">{{ $hostelStats['days_stayed'] ?? 0 }}</h3>
                            <p class="mb-0">Days Stayed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-day fa-2x"></i>
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
                            <h3 class="mb-0">{{ $hostelStats['visitors_this_month'] ?? 0 }}</h3>
                            <p class="mb-0">Visitors This Month</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
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
                            <h3 class="mb-0">{{ $hostelStats['complaints_submitted'] ?? 0 }}</h3>
                            <p class="mb-0">Complaints Submitted</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
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
                            <h3 class="mb-0">{{ number_format($hostelStats['meal_attendance_rate'] ?? 0, 1) }}%</h3>
                            <p class="mb-0">Meal Attendance</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Activities
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($recentActivities) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Activity</th>
                                        <th>Details</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $activity)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($activity['date'])->format('M d, Y') }}</td>
                                        <td>{{ $activity['time'] }}</td>
                                        <td>{{ $activity['activity'] }}</td>
                                        <td>{{ $activity['details'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $activity['type'] === 'visitor' ? 'success' : ($activity['type'] === 'complaint' ? 'warning' : 'info') }}">
                                                {{ ucfirst($activity['type']) }}
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
                            <p class="text-muted">No recent activities found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
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
                            <a href="{{ route('student.hostel.rooms') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-bed me-2"></i>View Room Details
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.hostel.meals') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-utensils me-2"></i>View Meal Plan
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.hostel.complaints') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-exclamation-triangle me-2"></i>Submit Complaint
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.hostel.profile') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-user-cog me-2"></i>Hostel Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

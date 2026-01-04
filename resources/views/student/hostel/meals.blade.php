@extends('student.layout.app')

@section('title', 'Meal Plan & Menu')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-utensils me-2"></i>Meal Plan & Menu
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.hostel.index') }}">Hostel</a></li>
                        <li class="breadcrumb-item active">Meals</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Meal Plan Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-utensils me-2"></i>Your Meal Plan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Plan Type:</label>
                                <p class="mb-0 fs-4 text-primary">{{ $mealPlan['plan_type'] ?? 'Not Assigned' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dining Hall:</label>
                                <p class="mb-0">{{ $mealPlan['dining_hall'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Special Diet:</label>
                                <p class="mb-0">{{ $mealPlan['special_diet'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Monthly Fee:</label>
                                <p class="mb-0 fs-4 text-success">${{ number_format($mealPlan['monthly_fee'] ?? 0, 2) }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Includes:</label>
                                @if(isset($mealPlan['includes']) && count($mealPlan['includes']) > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($mealPlan['includes'] as $meal)
                                            <li><i class="fas fa-check text-success me-2"></i>{{ $meal }}</li>
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
        </div>
    </div>

    <!-- Meal Times -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Meal Times
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($mealPlan['meal_times']) && count($mealPlan['meal_times']) > 0)
                        <div class="row">
                            @foreach($mealPlan['meal_times'] as $meal => $time)
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-{{ $meal === 'breakfast' ? 'sun' : ($meal === 'lunch' ? 'sun' : 'moon') }} fa-2x text-primary mb-3"></i>
                                        <h6 class="card-title text-capitalize">{{ $meal }}</h6>
                                        <p class="card-text fw-bold text-success">{{ $time }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No meal times available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Menu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-week me-2"></i>Weekly Menu
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($weeklyMenu) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Day</th>
                                        <th>Breakfast</th>
                                        <th>Lunch</th>
                                        <th>Dinner</th>
                                        <th>Special</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($weeklyMenu as $menu)
                                    <tr>
                                        <td class="fw-bold">{{ $menu['day'] }}</td>
                                        <td>{{ $menu['breakfast'] }}</td>
                                        <td>{{ $menu['lunch'] }}</td>
                                        <td>{{ $menu['dinner'] }}</td>
                                        <td>
                                            @if($menu['special'])
                                                <span class="badge bg-warning">{{ $menu['special'] }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-week fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No menu available</h5>
                            <p class="text-muted">Weekly menu will be updated soon.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Meal Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $mealStats['total_meals'] ?? 0 }}</h3>
                            <p class="mb-0">Total Meals</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-utensils fa-2x"></i>
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
                            <h3 class="mb-0">{{ $mealStats['attended_meals'] ?? 0 }}</h3>
                            <p class="mb-0">Attended</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
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
                            <h3 class="mb-0">{{ number_format($mealStats['attendance_rate'] ?? 0, 1) }}%</h3>
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
                            <h3 class="mb-0">{{ $mealStats['favorite_meal'] ?? 'N/A' }}</h3>
                            <p class="mb-0">Favorite Meal</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Meal History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Meal History
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($mealHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Breakfast</th>
                                        <th>Lunch</th>
                                        <th>Dinner</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mealHistory as $meal)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($meal['date'])->format('M d, Y') }}</td>
                                        <td class="fw-bold">{{ $meal['day'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $meal['breakfast'] === 'Present' ? 'success' : 'danger' }}">
                                                {{ $meal['breakfast'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $meal['lunch'] === 'Present' ? 'success' : 'danger' }}">
                                                {{ $meal['lunch'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $meal['dinner'] === 'Present' ? 'success' : 'danger' }}">
                                                {{ $meal['dinner'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No meal history found</h5>
                            <p class="text-muted">Your meal attendance will be tracked here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Meal Guidelines -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>Meal Guidelines
                </h6>
                <ul class="mb-0">
                    <li>Meals are served at fixed times - please arrive on time</li>
                    <li>Dress code applies in the dining hall (no shorts/slippers)</li>
                    <li>Food waste should be minimized - take only what you can eat</li>
                    <li>Special dietary requirements must be communicated in advance</li>
                    <li>Visitors can have meals by paying the daily rate</li>
                    <li>Report any food quality issues to the kitchen staff immediately</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

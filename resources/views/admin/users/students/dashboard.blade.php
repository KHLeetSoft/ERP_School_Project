@extends('admin.layout.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4>Welcome, {{ $student->name }}!</h4>
                    <p>{{ $currentSemester }} Semester | Academic Year {{ $currentYear }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Current GPA</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $academicSummary['gpa'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-bar-chart-alt-2 fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Attendance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $academicSummary['attendance'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-calendar-check fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Assignments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $academicSummary['assignments_pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-book-bookmark fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Courses Enrolled</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $academicSummary['courses_enrolled'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Activities -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentActivities as $activity)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $activity->description }}
                                    <span class="badge bg-primary rounded-pill">{{ $activity->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center">No recent activities found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Events</h6>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($upcomingEvents as $event)
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $event->title }}</h6>
                                        <small>{{ $event->date->format('M d') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $event->description }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center">No upcoming events found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
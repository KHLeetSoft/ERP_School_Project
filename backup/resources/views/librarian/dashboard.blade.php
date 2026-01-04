@extends('librarian.layout.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-primary me-3">
                    <i class="fas fa-book"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['total_books'] }}</h3>
                    <p class="text-muted mb-0">Total Books</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-success me-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['available_books'] }}</h3>
                    <p class="text-muted mb-0">Available</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-warning me-3">
                    <i class="fas fa-hand-holding"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['borrowed_books'] }}</h3>
                    <p class="text-muted mb-0">Borrowed</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-danger me-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['overdue_books'] }}</h3>
                    <p class="text-muted mb-0">Overdue</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-info me-3">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['total_members'] }}</h3>
                    <p class="text-muted mb-0">Total Members</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-secondary me-3">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
                    <p class="text-muted mb-0">Teachers</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-dark me-3">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['pending_requests'] }}</h3>
                    <p class="text-muted mb-0">Pending Requests</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12">
        <div class="modern-card p-4">
            <h5 class="mb-3">
                <i class="fas fa-history me-2"></i>Recent Activities
            </h5>
            @if(count($recent_activities) > 0)
                <div class="list-group list-group-flush">
                    @foreach($recent_activities as $activity)
                    <div class="list-group-item d-flex align-items-center border-0 px-0">
                        <div class="me-3">
                            <i class="fas {{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1">{{ $activity['message'] }}</p>
                            <small class="text-muted">{{ $activity['time'] ? $activity['time']->diffForHumans() : 'Just now' }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center py-3">No recent activities</p>
            @endif
        </div>
    </div>
</div>
@endsection

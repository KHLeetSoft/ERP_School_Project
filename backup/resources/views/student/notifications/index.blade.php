@extends('student.layout.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell me-2"></i>Notifications
            @if($unreadCount > 0)
                <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
            @endif
        </h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted">Welcome back, {{ isset($studentUser) ? $studentUser->name : 'Student' }}!</span>
        </div>
    </div>

    <!-- Notification Statistics -->
    <div class="row">
        <!-- Total Notifications Card -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Notifications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notificationStats['total_notifications'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unread Notifications Card -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unread</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notificationStats['unread_notifications'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Read Notifications Card -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Read</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notificationStats['read_notifications'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notifications Card -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Important</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notificationStats['important_notifications'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Week Card -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Week</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notificationStats['this_week'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month Card -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notificationStats['this_month'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.notifications.all') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-list me-2"></i>View All Notifications
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-success btn-block" onclick="markAllAsRead()">
                                <i class="fas fa-check-double me-2"></i>Mark All as Read
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.notifications.compose') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-edit me-2"></i>Compose Notification
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.notifications.settings') }}" class="btn btn-info btn-block">
                                <i class="fas fa-cog me-2"></i>Notification Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Notifications -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>Recent Notifications
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($recentNotifications) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentNotifications as $notification)
                            <div class="list-group-item {{ $notification['status'] === 'unread' ? 'bg-light' : '' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <i class="fas fa-{{ \App\Helpers\NotificationHelper::getNotificationIcon($notification['type']) }} fa-2x text-{{ \App\Helpers\NotificationHelper::getNotificationColor($notification['type']) }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    {{ $notification['title'] }}
                                                    @if($notification['status'] === 'unread')
                                                        <span class="badge bg-primary ms-2">New</span>
                                                    @endif
                                                </h6>
                                                <p class="mb-1">{{ $notification['message'] }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>{{ $notification['sender'] }} • 
                                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                                </small>
                                            </div>
                                            <div class="ms-3">
                                                <span class="badge bg-{{ \App\Helpers\NotificationHelper::getPriorityColor($notification['priority']) }}">
                                                    {{ ucfirst($notification['priority']) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <div class="btn-group-vertical btn-group-sm">
                                            <a href="{{ route('student.notifications.show', $notification['id']) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($notification['status'] === 'unread')
                                                <button class="btn btn-outline-success btn-sm" onclick="markAsRead('{{ $notification['id'] }}')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-outline-danger btn-sm" onclick="deleteNotification('{{ $notification['id'] }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No recent notifications</h5>
                            <p class="text-muted">Your recent notifications will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Important Notifications -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Important Notifications
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($importantNotifications) > 0)
                        @foreach($importantNotifications as $notification)
                        <div class="alert alert-{{ \App\Helpers\NotificationHelper::getPriorityAlertClass($notification['priority']) }} mb-3">
                            <h6 class="alert-heading">{{ $notification['title'] }}</h6>
                            <p class="mb-2">{{ $notification['message'] }}</p>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                            </small>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                            <p class="text-muted">No important notifications</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($upcomingEvents) > 0)
                        <div class="row">
                            @foreach($upcomingEvents as $event)
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <i class="fas fa-{{ \App\Helpers\NotificationHelper::getEventIcon($event['type']) }} fa-2x text-{{ \App\Helpers\NotificationHelper::getEventColor($event['type']) }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="card-title">{{ $event['title'] }}</h6>
                                                <p class="card-text text-muted mb-1">
                                                    <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y') }}
                                                </p>
                                                <p class="card-text text-muted mb-1">
                                                    <i class="fas fa-clock me-1"></i>{{ $event['time'] }}
                                                </p>
                                                <p class="card-text text-muted mb-0">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $event['location'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No upcoming events</h5>
                            <p class="text-muted">Your upcoming events will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`{{ route('student.notifications.read', '') }}/${notificationId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to mark notification as read.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while marking notification as read.');
    });
}

function markAllAsRead() {
    if (confirm('Are you sure you want to mark all notifications as read?')) {
        fetch('{{ route('student.notifications.mark-all-read') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to mark all notifications as read.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while marking all notifications as read.');
        });
    }
}

function deleteNotification(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        fetch(`{{ route('student.notifications.delete', '') }}/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete notification.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting notification.');
        });
    }
}
</script>
@endsection


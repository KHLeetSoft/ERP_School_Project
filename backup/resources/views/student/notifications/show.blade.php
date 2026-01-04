@extends('student.layout.app')

@section('title', 'Notification Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell me-2"></i>Notification Details
        </h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted">View notification information</span>
        </div>
    </div>

    <!-- Notification Details -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-{{ \App\Helpers\NotificationHelper::getNotificationIcon($notification['type']) }} me-2 text-{{ \App\Helpers\NotificationHelper::getNotificationColor($notification['type']) }}"></i>
                        {{ $notification['title'] }}
                    </h6>
                    <div>
                        <span class="badge bg-{{ \App\Helpers\NotificationHelper::getPriorityColor($notification['priority']) }} me-2">
                            {{ ucfirst($notification['priority']) }} Priority
                        </span>
                        <span class="badge bg-{{ $notification['status'] === 'read' ? 'success' : 'warning' }}">
                            {{ ucfirst($notification['status']) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted">Message</h6>
                        <p class="fs-5">{{ $notification['message'] }}</p>
                    </div>

                    @if(isset($notification['attachments']) && count($notification['attachments']) > 0)
                    <div class="mb-4">
                        <h6 class="text-muted">Attachments</h6>
                        <div class="list-group">
                            @foreach($notification['attachments'] as $attachment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-paperclip me-2"></i>
                                    {{ $attachment }}
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(isset($notification['action_required']) && $notification['action_required'])
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>Action Required
                        </h6>
                        <p class="mb-0">This notification requires your immediate attention.</p>
                        @if(isset($notification['action_url']))
                        <div class="mt-3">
                            <a href="{{ $notification['action_url'] }}" class="btn btn-warning">
                                <i class="fas fa-external-link-alt me-2"></i>Take Action
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if(isset($notification['expires_at']) && $notification['expires_at'])
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-clock me-2"></i>Expires
                        </h6>
                        <p class="mb-0">This notification expires on {{ \Carbon\Carbon::parse($notification['expires_at'])->format('M d, Y H:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notification Info -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Notification Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notification ID:</label>
                        <p class="mb-0">{{ $notification['id'] }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Type:</label>
                        <p class="mb-0">
                            <i class="fas fa-{{ \App\Helpers\NotificationHelper::getNotificationIcon($notification['type']) }} me-1 text-{{ \App\Helpers\NotificationHelper::getNotificationColor($notification['type']) }}"></i>
                            {{ ucfirst($notification['type']) }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Priority:</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ \App\Helpers\NotificationHelper::getPriorityColor($notification['priority']) }}">
                                {{ ucfirst($notification['priority']) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $notification['status'] === 'read' ? 'success' : 'warning' }}">
                                {{ ucfirst($notification['status']) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sender:</label>
                        <p class="mb-0">{{ $notification['sender'] }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sender Type:</label>
                        <p class="mb-0">{{ ucfirst($notification['sender_type']) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Created:</label>
                        <p class="mb-0">{{ \Carbon\Carbon::parse($notification['created_at'])->format('M d, Y H:i A') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Time Ago:</label>
                        <p class="mb-0">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($notification['status'] === 'unread')
                            <button class="btn btn-success" onclick="markAsRead('{{ $notification['id'] }}')">
                                <i class="fas fa-check me-2"></i>Mark as Read
                            </button>
                        @endif
                        <button class="btn btn-outline-primary" onclick="replyToNotification('{{ $notification['id'] }}')">
                            <i class="fas fa-reply me-2"></i>Reply
                        </button>
                        <button class="btn btn-outline-warning" onclick="forwardNotification('{{ $notification['id'] }}')">
                            <i class="fas fa-share me-2"></i>Forward
                        </button>
                        <button class="btn btn-outline-danger" onclick="deleteNotification('{{ $notification['id'] }}')">
                            <i class="fas fa-trash me-2"></i>Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Related Notifications -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-link me-2"></i>Related Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Similar Assignment Notice</h6>
                                <small>2 hours ago</small>
                            </div>
                            <p class="mb-1">Another assignment is due soon...</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Course Update</h6>
                                <small>1 day ago</small>
                            </div>
                            <p class="mb-1">Course materials have been updated...</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('student.notifications.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Notifications
                        </a>
                        <div>
                            <a href="{{ route('student.notifications.all') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>View All
                            </a>
                            <a href="{{ route('student.notifications.compose') }}" class="btn btn-outline-success">
                                <i class="fas fa-edit me-2"></i>Compose
                            </a>
                        </div>
                    </div>
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
                window.location.href = '{{ route('student.notifications.index') }}';
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

function replyToNotification(notificationId) {
    // Here you would typically open a reply modal or redirect to a reply page
    alert('Reply functionality would be implemented here.');
}

function forwardNotification(notificationId) {
    // Here you would typically open a forward modal or redirect to a forward page
    alert('Forward functionality would be implemented here.');
}
</script>
@endsection


@extends('student.layout.app')

@section('title', 'All Notifications')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-list me-2"></i>All Notifications
        </h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted">Manage your notifications</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter me-2"></i>Filter Notifications
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('student.notifications.all') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Type</label>
                                <select class="form-select" name="type">
                                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                                    @if(isset($notificationTypes))
                                        @foreach($notificationTypes as $key => $label)
                                            <option value="{{ $key }}" {{ $type === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="read" {{ $status === 'read' ? 'selected' : '' }}>Read</option>
                                    <option value="unread" {{ $status === 'unread' ? 'selected' : '' }}>Unread</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Priority</label>
                                <select class="form-select" name="priority">
                                    <option value="all" {{ $priority === 'all' ? 'selected' : '' }}>All Priorities</option>
                                    @if(isset($priorities))
                                        @foreach($priorities as $key => $label)
                                            <option value="{{ $key }}" {{ $priority === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Date From</label>
                                <input type="date" class="form-control" name="date_from" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date To</label>
                                <input type="date" class="form-control" name="date_to" value="{{ $dateTo }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 font-weight-bold text-gray-800">Bulk Actions</h6>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-success" onclick="markAllAsRead()">
                                <i class="fas fa-check-double me-2"></i>Mark All as Read
                            </button>
                            <button class="btn btn-danger" onclick="deleteSelected()">
                                <i class="fas fa-trash me-2"></i>Delete Selected
                            </button>
                            <button class="btn btn-primary" onclick="selectAll()">
                                <i class="fas fa-check-square me-2"></i>Select All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell me-2"></i>Notifications
                        @if($type !== 'all' || $status !== 'all' || $priority !== 'all')
                            <small class="text-muted">(Filtered Results)</small>
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($notifications) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Sender</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $notification)
                                    <tr class="{{ $notification['status'] === 'unread' ? 'table-light' : '' }}">
                                        <td>
                                            <input type="checkbox" class="notification-checkbox" value="{{ $notification['id'] }}">
                                        </td>
                                        <td>
                                            <i class="fas fa-{{ \App\Helpers\NotificationHelper::getNotificationIcon($notification['type']) }} text-{{ \App\Helpers\NotificationHelper::getNotificationColor($notification['type']) }}"></i>
                                            <span class="ms-1">{{ ucfirst($notification['type']) }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $notification['title'] }}</strong>
                                            @if($notification['status'] === 'unread')
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $notification['message'] }}">
                                                {{ $notification['message'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ \App\Helpers\NotificationHelper::getPriorityColor($notification['priority']) }}">
                                                {{ ucfirst($notification['priority']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $notification['status'] === 'read' ? 'success' : 'warning' }}">
                                                {{ ucfirst($notification['status']) }}
                                            </span>
                                        </td>
                                        <td>{{ $notification['sender'] }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($notification['created_at'])->format('M d, Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('student.notifications.show', $notification['id']) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($notification['status'] === 'unread')
                                                    <button class="btn btn-outline-success" onclick="markAsRead('{{ $notification['id'] }}')" title="Mark as Read">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-outline-danger" onclick="deleteNotification('{{ $notification['id'] }}')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications found</h5>
                            <p class="text-muted">
                                @if($type !== 'all' || $status !== 'all' || $priority !== 'all')
                                    Try adjusting your filter criteria.
                                @else
                                    You don't have any notifications yet.
                                @endif
                            </p>
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

function deleteSelected() {
    const selectedIds = Array.from(document.querySelectorAll('.notification-checkbox:checked')).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        alert('Please select at least one notification to delete.');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${selectedIds.length} selected notification(s)?`)) {
        // Here you would typically send an AJAX request to delete multiple notifications
        alert('Bulk delete functionality would be implemented here.');
    }
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.notification-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.notification-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}
</script>
@endsection


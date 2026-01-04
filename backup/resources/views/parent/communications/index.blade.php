@extends('parent.layout.app')

@section('title', 'Communications')

@section('content')
<div class="page-header">
    <h1 class="page-title">Communications</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Communications</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-comments me-2"></i>Messages & Notifications
                </h5>
            </div>
            <div class="card-body">
                @if($communications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($communications as $communication)
                            <div class="list-group-item px-0 {{ !$communication->is_read ? 'bg-light' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0 me-2">{{ $communication->subject }}</h6>
                                            @if(!$communication->is_read)
                                                <span class="badge badge-primary">New</span>
                                            @endif
                                        </div>
                                        <p class="text-muted mb-2">{{ Str::limit($communication->message, 150) }}</p>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted me-3">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $communication->sender->name ?? 'School Administration' }}
                                            </small>
                                            <small class="text-muted me-3">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $communication->created_at->format('M d, Y H:i') }}
                                            </small>
                                            @if($communication->student)
                                                <small class="text-muted">
                                                    <i class="fas fa-child me-1"></i>
                                                    {{ $communication->student->first_name }} {{ $communication->student->last_name }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewMessage({{ $communication->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($communications->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $communications->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No Communications Yet</h4>
                        <p class="text-muted">You haven't received any messages from teachers or school administration yet.</p>
                        <p class="text-muted">Communications will appear here when teachers send updates about your children.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <!-- Communication Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar me-2"></i>Communication Stats
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="stats-number">{{ $communications->total() }}</div>
                            <div class="stats-label">Total Messages</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-envelope-open"></i>
                            </div>
                            <div class="stats-number">{{ $communications->where('is_read', false)->count() }}</div>
                            <div class="stats-label">Unread</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-2"></i>Mark All as Read
                    </button>
                    <button class="btn btn-outline-info" onclick="composeMessage()">
                        <i class="fas fa-edit me-2"></i>Compose Message
                    </button>
                    <button class="btn btn-outline-secondary" onclick="refreshMessages()">
                        <i class="fas fa-sync me-2"></i>Refresh Messages
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Message Categories -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-tags me-2"></i>Message Categories
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>Academic Updates</span>
                        <span class="badge badge-primary">5</span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>Attendance Alerts</span>
                        <span class="badge badge-warning">2</span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>Event Notifications</span>
                        <span class="badge badge-info">3</span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>General Announcements</span>
                        <span class="badge badge-secondary">1</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="messageContent">
                <!-- Message content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="replyToMessage()">Reply</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewMessage(messageId) {
        // Load message content via AJAX
        fetch(`/parent/communications/${messageId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('messageContent').innerHTML = `
                    <div class="mb-3">
                        <h6>Subject: ${data.subject}</h6>
                        <p class="text-muted">
                            <i class="fas fa-user me-1"></i>From: ${data.sender_name}
                            <span class="ms-3">
                                <i class="fas fa-calendar me-1"></i>Date: ${data.created_at}
                            </span>
                        </p>
                    </div>
                    <div class="border-top pt-3">
                        <p>${data.message}</p>
                    </div>
                `;
                
                // Mark as read
                markAsRead(messageId);
                
                // Show modal
                new bootstrap.Modal(document.getElementById('messageModal')).show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading message. Please try again.');
            });
    }
    
    function markAsRead(messageId) {
        fetch(`/parent/communications/${messageId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update UI to show message as read
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            if (messageElement) {
                messageElement.classList.remove('bg-light');
                const newBadge = messageElement.querySelector('.badge');
                if (newBadge) {
                    newBadge.remove();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function markAllAsRead() {
        if (confirm('Are you sure you want to mark all messages as read?')) {
            fetch('/parent/communications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error marking messages as read. Please try again.');
            });
        }
    }
    
    function composeMessage() {
        alert('Compose message functionality will be implemented here.');
    }
    
    function replyToMessage() {
        alert('Reply functionality will be implemented here.');
    }
    
    function refreshMessages() {
        location.reload();
    }
</script>
@endpush

@extends('admin.layout.app')

@section('title', 'Messages Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Messages Dashboard</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.index') }}">Communications</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.messages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Compose Message
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-primary-light">
                            <i class="fas fa-inbox fa-2x text-primary"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="mb-0">{{ $stats['inbox'] }}</h4>
                            <span class="text-muted">Unread Messages</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-success-light">
                            <i class="fas fa-paper-plane fa-2x text-success"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="mb-0">{{ $stats['sent'] }}</h4>
                            <span class="text-muted">Sent Messages</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-warning-light">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="mb-0">{{ $stats['starred'] }}</h4>
                            <span class="text-muted">Starred Messages</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-danger-light">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="mb-0">{{ $stats['unread_urgent'] }}</h4>
                            <span class="text-muted">Urgent Unread</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Breakdown Chart -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Message Priority Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.messages.create') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Compose New Message
                        </a>
                        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-inbox"></i> View Inbox
                        </a>
                        <a href="{{ route('admin.messages.sent') }}" class="btn btn-outline-success">
                            <i class="fas fa-paper-plane"></i> Sent Messages
                        </a>
                        <a href="{{ route('admin.messages.drafts') }}" class="btn btn-outline-warning">
                            <i class="fas fa-save"></i> Drafts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Messages</h5>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                                            @if($recentMessages && $recentMessages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>Subject</th>
                                        <th>Priority</th>
                                        <th>Received</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMessages as $message)
                                    <tr class="{{ $message->is_unread ? 'table-warning' : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <img src="{{ $message->sender->avatar ?? asset('assets/img/default-avatar.png') }}" alt="Avatar">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $message->sender->name }}</h6>
                                                    <small class="text-muted">{{ $message->sender->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.messages.show', $message->id) }}" class="text-dark">
                                                {{ $message->subject }}
                                                @if($message->has_attachments)
                                                    <i class="fas fa-paperclip text-muted ml-1"></i>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $message->priority_color }}">
                                                <i class="{{ $message->priority_icon }}"></i>
                                                {{ ucfirst($message->priority) }}
                                            </span>
                                        </td>
                                        <td>{{ $message->time_ago }}</td>
                                        <td>
                                            @if($message->is_unread)
                                                <span class="badge badge-warning">Unread</span>
                                            @else
                                                <span class="badge badge-success">Read</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.messages.show', $message->id) }}">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('admin.messages.create', ['reply_to' => $message->id]) }}">
                                                        <i class="fas fa-reply"></i> Reply
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('admin.messages.create', ['forward_from' => $message->id]) }}">
                                                        <i class="fas fa-share"></i> Forward
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <button class="dropdown-item toggle-star" data-id="{{ $message->id }}">
                                                        <i class="fas fa-star{{ $message->is_starred ? ' text-warning' : '' }}"></i>
                                                        {{ $message->is_starred ? 'Unstar' : 'Star' }}
                                                    </button>
                                                    <button class="dropdown-item toggle-important" data-id="{{ $message->id }}">
                                                        <i class="fas fa-exclamation{{ $message->is_important ? ' text-danger' : '' }}"></i>
                                                        {{ $message->is_important ? 'Remove Important' : 'Mark Important' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No messages yet</h5>
                            <p class="text-muted">Your inbox is empty. Start by composing a new message!</p>
                            <a href="{{ route('admin.messages.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Compose Message
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Folders and Labels -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">My Folders</h5>
                </div>
                <div class="card-body">
                                            @if($folders && $folders->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($folders as $folder)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $folder->icon ?? 'fas fa-folder' }} mr-2" style="color: {{ $folder->color }}"></i>
                                    <span>{{ $folder->name }}</span>
                                </div>
                                <span class="badge badge-primary badge-pill">{{ $folder->message_count }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No custom folders created yet.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Labels</h5>
                </div>
                <div class="card-body">
                                            @if($labels && $labels->count() > 0)
                        <div class="d-flex flex-wrap">
                            @foreach($labels as $label)
                                                            <span class="badge badge-pill mr-2 mb-2" style="background-color: {{ $label->color }}; color: {{ $label->text_color }};">
                                {{ $label->name }}
                                <span class="badge badge-light text-dark ml-1">{{ $label->message_count }}</span>
                            </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No labels available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Priority Chart
    const ctx = document.getElementById('priorityChart').getContext('2d');
    const priorityData = @json($priorityStats);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(priorityData).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
            datasets: [{
                data: Object.values(priorityData),
                backgroundColor: [
                    '#28a745', // Low - Green
                    '#007bff', // Normal - Blue
                    '#ffc107', // High - Yellow
                    '#dc3545'  // Urgent - Red
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

    // Toggle star functionality
    document.querySelectorAll('.toggle-star').forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.dataset.id;
            fetch(`/admin/messages/${messageId}/toggle-star`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });

    // Toggle important functionality
    document.querySelectorAll('.toggle-important').forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.dataset.id;
            fetch(`/admin/messages/${messageId}/toggle-important`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });
});
</script>
@endsection

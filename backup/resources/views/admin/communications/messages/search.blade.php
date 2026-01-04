@extends('admin.layout.app')

@section('title', 'Search Results')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Search Results</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.index') }}">Communications</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.messages.index') }}">Messages</a></li>
                    <li class="breadcrumb-item active">Search</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.messages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Compose
                </a>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                                         <form action="{{ route('admin.communications.messages.search') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="q" placeholder="Search messages..." value="{{ request('q') }}" required>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="priority">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                <option value="direct" {{ request('type') == 'direct' ? 'selected' : '' }}>Direct</option>
                                <option value="broadcast" {{ request('type') == 'broadcast' ? 'selected' : '' }}>Broadcast</option>
                                <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                <option value="starred" {{ request('status') == 'starred' ? 'selected' : '' }}>Starred</option>
                                <option value="important" {{ request('status') == 'important' ? 'selected' : '' }}>Important</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Search Results
                        @if(request('q'))
                            for "{{ request('q') }}"
                        @endif
                    </h5>
                                    @if($messages && $messages->count() > 0)
                    <span class="badge bg-primary">{{ $messages->total() }} result(s) found</span>
                @endif
                </div>
                <div class="card-body">
                    @if($messages && $messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Sender/Recipient</th>
                                        <th>Subject</th>
                                        <th>Priority</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr class="{{ $message->is_unread ? 'table-warning' : '' }}">
                                            <td>
                                                @if($message->sender_id == auth()->id())
                                                    <!-- Sent message -->
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2">
                                                            <span class="avatar-text rounded-circle bg-success">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">To: 
                                                                @if($message->recipient)
                                                                    {{ $message->recipient->name }}
                                                                @else
                                                                    <span class="text-muted">No recipient</span>
                                                                @endif
                                                            </div>
                                                            <small class="text-muted">Sent by you</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Received message -->
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2">
                                                            <span class="avatar-text rounded-circle bg-primary">
                                                                {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $message->sender->name }}</div>
                                                            <small class="text-muted">{{ $message->sender->email }}</small>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                                                                 <a href="{{ route('admin.communications.messages.show', $message->id) }}" class="text-dark">
                                                    {{ $message->subject }}
                                                    @if($message->attachments)
                                                        <i class="fas fa-paperclip text-muted ms-1"></i>
                                                    @endif
                                                </a>
                                                <div class="text-muted small">
                                                    {{ Str::limit(strip_tags($message->body), 100) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $message->priority == 'urgent' ? 'danger' : ($message->priority == 'high' ? 'warning' : ($message->priority == 'normal' ? 'primary' : 'success')) }}">
                                                    {{ ucfirst($message->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($message->type) }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $message->sent_at ? $message->sent_at->format('M d, Y H:i') : 'Not sent' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($message->is_starred)
                                                    <i class="fas fa-star text-warning me-1"></i>
                                                @endif
                                                @if($message->is_important)
                                                    <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                                @endif
                                                @if($message->read_at)
                                                    <span class="badge bg-success">Read</span>
                                                @else
                                                    <span class="badge bg-warning">Unread</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                                                                                         <a class="dropdown-item" href="{{ route('admin.communications.messages.show', $message->id) }}">
                                                                <i class="fas fa-eye me-2"></i> View
                                                            </a>
                                                        </li>
                                                        @if($message->sender_id == auth()->id())
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.communications.messages.create', ['reply_to' => $message->id]) }}">
                                                                    <i class="fas fa-reply me-2"></i> Reply
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.communications.messages.create', ['reply_to' => $message->id]) }}">
                                                                    <i class="fas fa-reply me-2"></i> Reply
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.communications.messages.create', ['forward_from' => $message->id]) }}">
                                                                    <i class="fas fa-share me-2"></i> Forward
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.communications.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                                                    <i class="fas fa-trash me-2"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $messages->links() }}
                        </div>
                    @elseif(request('q'))
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No results found</h5>
                            <p class="text-muted">No messages match your search criteria.</p>
                            <div class="mt-3">
                                <a href="{{ route('admin.communications.messages.index') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-inbox"></i> View All Messages
                                </a>
                                <a href="{{ route('admin.communications.messages.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Compose New Message
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Search Messages</h5>
                            <p class="text-muted">Use the search form above to find specific messages.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on search input
    document.querySelector('input[name="q"]').focus();
    
    // Highlight search terms in results
    const searchTerm = '{{ request("q") }}';
    if (searchTerm) {
        const textElements = document.querySelectorAll('td');
        textElements.forEach(element => {
            const text = element.textContent;
            if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                const highlightedText = text.replace(
                    new RegExp(searchTerm, 'gi'),
                    match => `<mark class="bg-warning">${match}</mark>`
                );
                element.innerHTML = highlightedText;
            }
        });
    }
});
</script>
@endpush

@extends('admin.layout.app')

@section('title', 'Inbox')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('styles')
<style>
.pagination-wrapper .pagination {
    margin-bottom: 0;
}

.pagination-wrapper .page-link {
    color: #495057;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0.6rem 0.85rem;
    margin: 0 3px;
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
    font-weight: 500;
    min-width: 40px;
    text-align: center;
}

.pagination-wrapper .page-link:hover {
    color: #0056b3;
    background-color: #f8f9fa;
    border-color: #007bff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,123,255,0.15);
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-color: #007bff;
    color: white;
    box-shadow: 0 2px 8px rgba(0,123,255,0.3);
}

.pagination-wrapper .page-item.disabled .page-link {
    color: #adb5bd;
    pointer-events: none;
    background-color: #f8f9fa;
    border-color: #e9ecef;
    opacity: 0.6;
}

.pagination-wrapper {
    background: white;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Action buttons styling */
.btn-group-sm .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
    margin: 0 1px;
    transition: all 0.2s ease-in-out;
}

.btn-group-sm .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

.btn-group-sm .btn:active {
    transform: translateY(0);
}

/* Button colors for different actions */
.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

/* Toggle button states */
.toggle-star.active {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.toggle-important.active {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.toggle-flag.active {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

/* Loading state */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Button group improvements */
.btn-group-sm .btn {
    position: relative;
    overflow: hidden;
}

.btn-group-sm .btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    z-index: 2;
}

.btn-outline-success:hover {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-outline-info:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.pagination-wrapper .page-item:first-child .page-link,
.pagination-wrapper .page-item:last-child .page-link {
    border-radius: 0.25rem;
}

.pagination-wrapper .pagination-info {
    font-size: 0.9rem;
    color: #6c757d;
    background: #f8f9fa;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.pagination-wrapper .pagination-info strong {
    color: #495057;
    font-weight: 600;
}

@media (max-width: 768px) {
    .pagination-wrapper .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .pagination-wrapper .pagination {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .pagination-wrapper .page-link {
        padding: 0.5rem 0.7rem;
        margin: 0 2px;
        min-width: 35px;
    }
    
    .pagination-wrapper .pagination-info {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Inbox</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.index') }}">Communications</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.messages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Compose
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.communications.messages.index') }}" method="GET" id="filterForm">
                        <!-- Search -->
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Search messages...">
                        </div>

                        <!-- Priority Filter -->
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select class="form-control" id="priority" name="priority">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="direct" {{ request('type') == 'direct' ? 'selected' : '' }}>Direct</option>
                                <option value="broadcast" {{ request('type') == 'broadcast' ? 'selected' : '' }}>Broadcast</option>
                                <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                <option value="starred" {{ request('status') == 'starred' ? 'selected' : '' }}>Starred</option>
                                <option value="important" {{ request('status') == 'important' ? 'selected' : '' }}>Important</option>
                                <option value="flagged" {{ request('status') == 'flagged' ? 'selected' : '' }}>Flagged</option>
                            </select>
                        </div>

                        <!-- Label Filter -->
                        @if($labels && $labels->count() > 0)
                        <div class="form-group">
                            <label for="label">Label</label>
                            <select class="form-control" id="label" name="label">
                                <option value="">All Labels</option>
                                @foreach($labels as $label)
                                <option value="{{ $label->slug }}" {{ request('label') == $label->slug ? 'selected' : '' }}>
                                    {{ $label->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Folder Filter -->
                        @if($folders && $folders->count() > 0)
                        <div class="form-group">
                            <label for="folder">Folder</label>
                            <select class="form-control" id="folder" name="folder">
                                <option value="">All Folders</option>
                                @foreach($folders as $folder)
                                <option value="{{ $folder->slug }}" {{ request('folder') == $folder->slug ? 'selected' : '' }}>
                                    {{ $folder->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Date Range -->
                        <div class="form-group">
                            <label for="date_from">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>

                        <div class="form-group">
                            <label for="date_to">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>

                        <!-- Sort -->
                        <div class="form-group">
                            <label for="sort">Sort By</label>
                            <select class="form-control" id="sort" name="sort">
                                <option value="sent_at" {{ request('sort') == 'sent_at' ? 'selected' : '' }}>Date</option>
                                <option value="subject" {{ request('sort') == 'subject' ? 'selected' : '' }}>Subject</option>
                                <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Priority</option>
                                <option value="type" {{ request('sort') == 'type' ? 'selected' : '' }}>Type</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="direction">Direction</label>
                            <select class="form-control" id="direction" name="direction">
                                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                        <a href="{{ route('admin.communications.messages.index') }}" class="btn btn-outline-secondary btn-block">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title">
                                Messages
                                @if(request()->hasAny(['search','priority','type','status','label','folder','date_from','date_to']))
                                    <small class="text-muted">(Filtered)</small>
                                @endif
                            </h5>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" id="selectAll">
                                    <i class="fas fa-check-square"></i> Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="deselectAll" style="display:none;">
                                    <i class="fas fa-square"></i> Deselect All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions Bar -->
                <div class="card-body border-bottom" id="bulkActionsBar" style="display:none;">
                    <div class="row align-items-center">
                        <div class="col">
                            <span class="text-muted">
                                <span id="selectedCount">0</span> message(s) selected
                            </span>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-success" data-action="mark_read">
                                    <i class="fas fa-check"></i> Mark Read
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-action="mark_unread">
                                    <i class="fas fa-times"></i> Mark Unread
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-action="star">
                                    <i class="fas fa-star"></i> Star
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-action="unstar">
                                    <i class="fas fa-star-o"></i> Unstar
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Table -->
                <div class="card-body">
                    @if($messages && $messages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="20"><input type="checkbox" id="selectAllCheckbox"></th>
                                    <th width="50"></th>
                                    <th>From</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Type</th>
                                    <th>Received</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                <tr class="{{ $message->is_unread ? 'table-warning' : '' }}">
                                    <td><input type="checkbox" class="message-checkbox" value="{{ $message->id }}"></td>
                                    <td>
                                        <div class="d-flex flex-column align-items-center">
                                            @if($message->is_starred)
                                                <i class="fas fa-star text-warning mb-1" title="Starred"></i>
                                            @endif
                                            @if($message->is_important)
                                                <i class="fas fa-exclamation text-danger mb-1" title="Important"></i>
                                            @endif
                                            @if($message->is_flagged)
                                                <i class="fas fa-flag text-info" title="Flagged"></i>
                                            @endif
                                        </div>
                                    </td>
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
                                        <a href="{{ route('admin.communications.messages.show', $message->id) }}" class="text-dark">
                                            {{ $message->subject }}
                                            @if($message->has_attachments)
                                                <i class="fas fa-paperclip text-muted ml-1"></i>
                                            @endif
                                            @if($message->requires_acknowledgment && !$message->acknowledged_at)
                                                <i class="fas fa-handshake text-warning ml-1" title="Requires Acknowledgment"></i>
                                            @endif
                                        </a>
                                        @if($message->labels && $message->labels->count() > 0)
                                            <div class="mt-1">
                                                @foreach($message->labels as $label)
                                                    <span class="badge badge-sm" style="background-color: {{ $label->color }}; color: {{ $label->text_color }};">
                                                        {{ $label->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $message->priority_color }}">
                                            <i class="{{ $message->priority_icon }}"></i>
                                            {{ ucfirst($message->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            <i class="{{ $message->type_icon }}"></i>
                                            {{ ucfirst($message->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $message->time_ago }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.communications.messages.show', $message->id) }}" 
                                               class="btn btn-outline-primary" 
                                               title="View Message">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.communications.messages.create', ['reply_to' => $message->id]) }}" 
                                               class="btn btn-outline-success" 
                                               title="Reply">
                                                <i class="fas fa-reply"></i>
                                            </a>
                                            <a href="{{ route('admin.communications.messages.create', ['forward_from' => $message->id]) }}" 
                                               class="btn btn-outline-info" 
                                               title="Forward">
                                                <i class="fas fa-share"></i>
                                            </a>
                                            <button class="btn btn-outline-warning toggle-star {{ $message->is_starred ? 'active' : '' }}" 
                                                    data-id="{{ $message->id }}" 
                                                    title="{{ $message->is_starred ? 'Unstar' : 'Star' }}">
                                                <i class="fas fa-star{{ $message->is_starred ? ' text-warning' : '' }}"></i>
                                            </button>
                                            <button class="btn btn-outline-danger toggle-important {{ $message->is_important ? 'active' : '' }}" 
                                                    data-id="{{ $message->id }}" 
                                                    title="{{ $message->is_important ? 'Remove Important' : 'Mark Important' }}">
                                                <i class="fas fa-exclamation{{ $message->is_important ? ' text-danger' : '' }}"></i>
                                            </button>
                                            @if($message->requires_acknowledgment && !$message->acknowledged_at)
                                                <button class="btn btn-outline-secondary acknowledge-message" 
                                                        data-id="{{ $message->id }}" 
                                                        title="Acknowledge">
                                                    <i class="fas fa-handshake"></i>
                                                </button>
                                            @endif
                                            <form action="{{ route('admin.communications.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this message?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="pagination-info">
                            <i class="fas fa-info-circle me-1"></i>
                            Showing <strong>{{ $messages->firstItem() ?? 0 }}</strong> to <strong>{{ $messages->lastItem() ?? 0 }}</strong> of <strong>{{ $messages->total() }}</strong> results
                        </div>
                        <div class="pagination-wrapper">
                            {{ $messages->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No messages found</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['search','priority','type','status','label','folder','date_from','date_to']))
                                Try adjusting your filters or search terms.
                            @else
                                Your inbox is empty. Start by composing a new message!
                            @endif
                        </p>
                        @if(!request()->hasAny(['search','priority','type','status','label','folder','date_from','date_to']))
                            <a href="{{ route('admin.communications.messages.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Compose Message
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden CSRF token input as fallback -->
<input type="hidden" id="csrf-token-input" value="{{ csrf_token() }}">

@endsection

@section('scripts')
<script>
// CSRF token fallback
window.csrfToken = '{{ csrf_token() }}';

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing message functionality...');
    
    // Check if CSRF token is available
    let csrfToken = window.csrfToken; // Try fallback first
    
    // If fallback not available, try meta tag
    if (!csrfToken) {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfTokenMeta) {
            csrfToken = csrfTokenMeta.getAttribute('content');
        }
    }
    
    // If still no token, show error
    if (!csrfToken) {
        console.error('CSRF token not found. Please ensure the token is properly set.');
        return;
    }
    
    console.log('CSRF token found and validated');
    console.log('CSRF token length:', csrfToken.length);
    console.log('Meta tags found:', document.querySelectorAll('meta').length);
    console.log('All meta tags:', Array.from(document.querySelectorAll('meta')).map(m => ({ name: m.name, content: m.content })));
    
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const messageCheckboxes = document.querySelectorAll('.message-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        messageCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActionsBar();
    });

    // Individual checkbox change
    messageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });

    // Select all button
    selectAllBtn.addEventListener('click', function() {
        messageCheckboxes.forEach(checkbox => checkbox.checked = true);
        selectAllCheckbox.checked = true;
        updateBulkActionsBar();
    });

    // Deselect all button
    deselectAllBtn.addEventListener('click', function() {
        messageCheckboxes.forEach(checkbox => checkbox.checked = false);
        selectAllCheckbox.checked = false;
        updateBulkActionsBar();
    });

    function updateBulkActionsBar() {
        const checkedCount = document.querySelectorAll('.message-checkbox:checked').length;
        selectedCount.textContent = checkedCount;
        
        if (checkedCount > 0) {
            bulkActionsBar.style.display = 'block';
            selectAllBtn.style.display = 'none';
            deselectAllBtn.style.display = 'inline-block';
        } else {
            bulkActionsBar.style.display = 'none';
            selectAllBtn.style.display = 'inline-block';
            deselectAllBtn.style.display = 'none';
        }
    }

    // Bulk actions
    document.querySelectorAll('[data-action]').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const selectedIds = Array.from(document.querySelectorAll('.message-checkbox:checked'))
                .map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) return;

            if (action === 'delete') {
                if (!confirm(`Are you sure you want to delete ${selectedIds.length} message(s)?`)) return;
            }

            // Perform bulk action
             fetch('{{ route("admin.communications.messages.bulk-action") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action: action,
                    message_ids: selectedIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while performing the bulk action.');
            });
        });
    });

    // Toggle star functionality
    document.querySelectorAll('.toggle-star').forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.dataset.id;
            fetch(`/admin/communications/messages/${messageId}/toggle-star`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    console.error('Error toggling star:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while toggling star.');
            });
        });
    });

    // Toggle important functionality
    document.querySelectorAll('.toggle-important').forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.dataset.id;
            fetch(`/admin/communications/messages/${messageId}/toggle-important`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    console.error('Error toggling important:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while toggling important.');
            });
        });
    });

    // Acknowledge message functionality
    document.querySelectorAll('.acknowledge-message').forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.dataset.id;
            fetch(`/admin/communications/messages/${messageId}/acknowledge`, {
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
                } else {
                    console.error('Error acknowledging message:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while acknowledging message.');
            });
        });
    });

    // Add loading states to buttons
    function addLoadingState(button) {
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        return originalText;
    }

    function removeLoadingState(button, originalText) {
        button.disabled = false;
        button.innerHTML = originalText;
    }

    // Improve all toggle buttons with loading states
    document.querySelectorAll('.toggle-star, .toggle-important, .acknowledge-message').forEach(button => {
        button.addEventListener('click', function() {
            const originalText = addLoadingState(this);
            
            // Re-enable after a timeout in case of errors
            setTimeout(() => {
                removeLoadingState(this, originalText);
            }, 5000);
        });
    });

    // Debug: Log all buttons found
    console.log('Toggle star buttons found:', document.querySelectorAll('.toggle-star').length);
    console.log('Toggle important buttons found:', document.querySelectorAll('.toggle-important').length);
    console.log('Acknowledge buttons found:', document.querySelectorAll('.acknowledge-message').length);
});
</script>
@endsection

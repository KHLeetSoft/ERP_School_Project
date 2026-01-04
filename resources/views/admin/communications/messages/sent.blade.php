@extends('admin.layout.app')

@section('title', 'Sent Messages')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Sent Messages</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.index') }}">Communications</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.messages.index') }}">Messages</a></li>
                    <li class="breadcrumb-item active">Sent</li>
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
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Sent Messages</h5>
                </div>
                <div class="card-body">
                    @if($messages && $messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all" class="form-check-input">
                                        </th>
                                        <th>Recipient</th>
                                        <th>Subject</th>
                                        <th>Priority</th>
                                        <th>Type</th>
                                        <th>Sent</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr class="{{ $message->is_unread ? 'table-warning' : '' }}">
                                            <td>
                                                <input type="checkbox" name="message_ids[]" value="{{ $message->id }}" class="form-check-input message-checkbox">
                                            </td>
                                            <td>
                                                @if($message->recipient)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2">
                                                            <span class="avatar-text rounded-circle bg-primary">
                                                                {{ strtoupper(substr($message->recipient->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $message->recipient->name }}</div>
                                                            <small class="text-muted">{{ $message->recipient->email }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No recipient</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.communications.messages.show', $message->id) }}" class="text-dark">
                                                    {{ $message->subject }}
                                                    @if($message->attachments)
                                                        <i class="fas fa-paperclip text-muted ms-1"></i>
                                                    @endif
                                                </a>
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
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No sent messages</h5>
                            <p class="text-muted">You haven't sent any messages yet.</p>
                                                            <a href="{{ route('admin.communications.messages.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Compose Your First Message
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionsForm">
                    <div class="mb-3">
                        <label for="bulkAction" class="form-label">Select Action</label>
                        <select class="form-select" id="bulkAction" name="action" required>
                            <option value="">Choose an action...</option>
                            <option value="delete">Delete Selected</option>
                            <option value="mark_read">Mark as Read</option>
                            <option value="mark_unread">Mark as Unread</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted">
                            <strong id="selectedCount">0</strong> message(s) selected
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="bulkActionsForm" class="btn btn-primary">Apply Action</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const messageCheckboxes = document.querySelectorAll('.message-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActionsForm = document.getElementById('bulkActionsForm');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        messageCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Update selected count
    messageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.message-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        
        if (checkedCount > 0) {
            // Show bulk actions button or enable form
        }
    }

    // Bulk actions form submission
    bulkActionsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
        const messageIds = Array.from(checkedBoxes).map(cb => cb.value);
        const action = document.getElementById('bulkAction').value;

        if (messageIds.length === 0 || !action) {
            alert('Please select messages and an action.');
            return;
        }

        // Send bulk action request
                 fetch('{{ route("admin.communications.messages.bulk-action") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                action: action,
                message_ids: messageIds
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
            alert('An error occurred while processing the bulk action.');
        });
    });
});
</script>
@endpush

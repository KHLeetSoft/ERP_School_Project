@extends('admin.layout.app')

@section('title', $message->subject)

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">{{ $message->subject }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.messages.index') }}">Messages</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.communications.messages.create', ['reply_to' => $message->id]) }}" class="btn btn-primary">
                        <i class="fas fa-reply"></i> Reply
                    </a>
                    <a href="{{ route('admin.communications.messages.create', ['forward_from' => $message->id]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-share"></i> Forward
                    </a>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            More
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item toggle-star" data-id="{{ $message->id }}">
                                <i class="fas fa-star{{ $message->is_starred ? ' text-warning' : '' }}"></i>
                                {{ $message->is_starred ? 'Unstar' : 'Star' }}
                            </button>
                            <button class="dropdown-item toggle-important" data-id="{{ $message->id }}">
                                <i class="fas fa-exclamation{{ $message->is_important ? ' text-danger' : '' }}"></i>
                                {{ $message->is_important ? 'Remove Important' : 'Mark Important' }}
                            </button>
                            <button class="dropdown-item toggle-flag" data-id="{{ $message->id }}">
                                <i class="fas fa-flag{{ $message->is_flagged ? ' text-info' : '' }}"></i>
                                {{ $message->is_flagged ? 'Unflag' : 'Flag' }}
                            </button>
                            @if($message->requires_acknowledgment && !$message->acknowledged_at)
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item acknowledge-message" data-id="{{ $message->id }}">
                                    <i class="fas fa-handshake"></i> Acknowledge
                                </button>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('admin.communications.messages.edit', $message->id) }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.communications.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Message Content -->
        <div class="col-lg-8">
            <!-- Message Header -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-1">{{ $message->subject }}</h5>
                            <div class="d-flex align-items-center">
                                @if($message->is_starred)
                                    <i class="fas fa-star text-warning mr-2" title="Starred"></i>
                                @endif
                                @if($message->is_important)
                                    <i class="fas fa-exclamation text-danger mr-2" title="Important"></i>
                                @endif
                                @if($message->is_flagged)
                                    <i class="fas fa-flag text-info mr-2" title="Flagged"></i>
                                @endif
                                @if($message->requires_acknowledgment && !$message->acknowledged_at)
                                    <i class="fas fa-handshake text-warning mr-2" title="Requires Acknowledgment"></i>
                                @endif
                                @if($message->is_encrypted)
                                    <i class="fas fa-lock text-success mr-2" title="Encrypted"></i>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $message->priority_color }} mb-2">
                                <i class="{{ $message->priority_icon }}"></i>
                                {{ ucfirst($message->priority) }}
                            </span>
                            <br>
                            <span class="badge badge-secondary">
                                <i class="{{ $message->type_icon }}"></i>
                                {{ ucfirst($message->type) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Message Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>From:</strong>
                            <div class="d-flex align-items-center mt-1">
                                <div class="avatar avatar-sm mr-2">
                                    <img src="{{ $message->sender->avatar ?? asset('assets/img/default-avatar.png') }}" alt="Avatar">
                                </div>
                                <div>
                                    <div>{{ $message->sender->name }}</div>
                                    <small class="text-muted">{{ $message->sender->email }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Sent:</strong>
                            <div class="mt-1">{{ $message->sent_at->format('F d, Y \a\t g:i A') }}</div>
                            <small class="text-muted">{{ $message->time_ago }}</small>
                        </div>
                    </div>

                    @if($message->recipients && $message->recipients->count() > 0)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Recipients:</strong>
                            <div class="mt-2">
                                @foreach($message->recipients->groupBy('recipient_type') as $type => $recipients)
                                    <div class="mb-2">
                                        <span class="badge badge-light mr-2">{{ strtoupper($type) }}</span>
                                        @foreach($recipients as $recipient)
                                            <span class="badge badge-outline-primary mr-1">
                                                {{ $recipient->user->name }}
                                                @if($recipient->read_at)
                                                    <i class="fas fa-check text-success ml-1"></i>
                                                @endif
                                                @if($recipient->acknowledged_at)
                                                    <i class="fas fa-handshake text-warning ml-1"></i>
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($message->department)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Department:</strong>
                            <span class="badge badge-info ml-2">{{ $message->department->name }}</span>
                        </div>
                    </div>
                    @endif

                                            @if($message->labels && $message->labels->count() > 0)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Labels:</strong>
                            <div class="mt-1">
                                @foreach($message->labels as $label)
                                    <span class="badge badge-pill mr-1" style="background-color: {{ $label->color }}; color: {{ $label->text_color }};">
                                        {{ $label->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($message->expires_at)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Expires:</strong>
                            <span class="text-muted ml-2">{{ $message->expires_at->format('F d, Y \a\t g:i A') }}</span>
                            @if($message->is_expired)
                                <span class="badge badge-danger ml-2">Expired</span>
                            @else
                                <span class="badge badge-warning ml-2">{{ $message->days_until_expiry }} days left</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Message Body -->
                    <div class="border-top pt-3">
                        <div class="message-body">
                            {!! $message->body !!}
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($message->has_attachments)
                    <div class="border-top pt-3 mt-3">
                        <h6><i class="fas fa-paperclip"></i> Attachments ({{ $message->attachment_count }})</h6>
                        <div class="row">
                            @foreach($message->attachments as $index => $attachment)
                            <div class="col-md-6 mb-2">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file mr-2 text-muted"></i>
                                            <div class="flex-grow-1">
                                                <div class="font-weight-bold">{{ $attachment['name'] }}</div>
                                                <small class="text-muted">{{ number_format($attachment['size'] / 1024, 2) }} KB</small>
                                            </div>
                                            <a href="{{ route('admin.communications.messages.download-attachment', [$message->id, $index]) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Thread Messages -->
                                @if($threadMessages && $threadMessages->count() > 1)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-comments"></i> 
                        Thread ({{ $threadMessages ? $threadMessages->count() : 0 }} messages)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($threadMessages as $threadMessage)
                        <div class="list-group-item {{ $threadMessage->id === $message->id ? 'bg-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="avatar avatar-sm mr-2">
                                            <img src="{{ $threadMessage->sender->avatar ?? asset('assets/img/default-avatar.png') }}" alt="Avatar">
                                        </div>
                                        <div>
                                            <strong>{{ $threadMessage->sender->name }}</strong>
                                            <small class="text-muted ml-2">{{ $threadMessage->sent_at->format('M d, Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <div class="mb-1">
                                        <strong>{{ $threadMessage->subject }}</strong>
                                    </div>
                                    <div class="text-muted">
                                        {!! Str::limit(strip_tags($threadMessage->body), 150) !!}
                                    </div>
                                </div>
                                <div class="ml-3">
                                    @if($threadMessage->id !== $message->id)
                                                                                    <a href="{{ route('admin.communications.messages.show', $threadMessage->id) }}" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    @else
                                        <span class="badge badge-primary">Current</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                                                    <a href="{{ route('admin.communications.messages.create', ['reply_to' => $message->id]) }}" class="btn btn-primary">
                            <i class="fas fa-reply"></i> Reply
                        </a>
                                                    <a href="{{ route('admin.communications.messages.create', ['forward_from' => $message->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-share"></i> Forward
                        </a>
                        @if($message->status === 'draft' && $message->sender_id === auth()->id())
                            <a href="{{ route('admin.communications.messages.edit', $message->id) }}" class="btn btn-outline-warning">
                                <i class="fas fa-edit"></i> Edit Draft
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Message Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Message Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $message->is_unread ? 'warning' : 'success' }} ml-2">
                            {{ $message->is_unread ? 'Unread' : 'Read' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Unique ID:</strong>
                        <div class="text-muted font-monospace small">{{ $message->unique_identifier }}</div>
                    </div>

                    @if($message->requires_acknowledgment)
                    <div class="mb-3">
                        <strong>Acknowledgment:</strong>
                        @if($message->acknowledged_at)
                            <span class="badge badge-success ml-2">Acknowledged</span>
                            <div class="text-muted small mt-1">{{ $message->acknowledged_at->format('M d, Y H:i') }}</div>
                        @else
                            <span class="badge badge-warning ml-2">Pending</span>
                        @endif
                    </div>
                    @endif

                    @if($message->read_at)
                    <div class="mb-3">
                        <strong>Read At:</strong>
                        <div class="text-muted">{{ $message->read_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif

                    @if($message->reply_count > 0)
                    <div class="mb-3">
                        <strong>Replies:</strong>
                        <span class="badge badge-info ml-2">{{ $message->reply_count }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Move to Folder -->
                                @if($folders && $folders->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Move to Folder</h6>
                </div>
                <div class="card-body">
                                            <form action="{{ route('admin.communications.messages.bulk-action') }}" method="POST" id="moveToFolderForm">
                        @csrf
                        <input type="hidden" name="action" value="move_to_folder">
                        <input type="hidden" name="message_ids[]" value="{{ $message->id }}">
                        <div class="form-group">
                            <select class="form-control" name="folder_id" id="folderSelect">
                                <option value="">Select Folder</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm" disabled>
                            Move
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Add/Remove Labels -->
                            @if($labels && $labels->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Labels</h6>
                </div>
                <div class="card-body">
                                            <form action="{{ route('admin.communications.messages.bulk-action') }}" method="POST" id="labelForm">
                        @csrf
                        <input type="hidden" name="action" value="add_label">
                        <input type="hidden" name="message_ids[]" value="{{ $message->id }}">
                        <div class="form-group">
                            <select class="form-control" name="label_id" id="labelSelect">
                                <option value="">Select Label</option>
                                @foreach($labels as $label)
                                    <option value="{{ $label->id }}">{{ $label->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-outline-success btn-sm" disabled>
                                <i class="fas fa-plus"></i> Add
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="removeLabelBtn" disabled>
                                <i class="fas fa-minus"></i> Remove
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // CSRF
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error('CSRF token not found. Please ensure the meta tag is present.');
        return;
    }
    const CSRF = csrfMeta.getAttribute('content');

    // Helper: selected message IDs (checkboxes in table)
    const getSelectedMessageIds = () =>
        Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);

    // ===== Folder selection handler =====
    const folderSelect = document.getElementById('folderSelect');
    const moveToFolderForm = document.getElementById('moveToFolderForm');
    const moveButton = moveToFolderForm ? moveToFolderForm.querySelector('button[type="submit"]') : null;

    if (folderSelect && moveButton) {
        moveButton.disabled = !folderSelect.value;
        folderSelect.addEventListener('change', function () {
            moveButton.disabled = !this.value;
        });
    }

    // ===== Label selection handler =====
    const labelSelect = document.getElementById('labelSelect');
    const labelForm = document.getElementById('labelForm');
    const addLabelBtn = labelForm ? labelForm.querySelector('button[type="submit"]') : null;
    const removeLabelBtn = document.getElementById('removeLabelBtn');

    if (labelSelect && addLabelBtn && removeLabelBtn) {
        const syncLabelBtns = () => {
            const hasValue = !!labelSelect.value;
            addLabelBtn.disabled = !hasValue;
            removeLabelBtn.disabled = !hasValue;
        };
        syncLabelBtns();
        labelSelect.addEventListener('change', syncLabelBtns);
    }

    // ===== Remove label (bulk) =====
    if (removeLabelBtn && labelSelect) {
        removeLabelBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const labelId = labelSelect.value;
            if (!labelId) return;

            const ids = getSelectedMessageIds();
            if (!ids.length) {
                alert('Please select at least one message.');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.communications.messages.bulk-action") }}';

            const addHidden = (name, value) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                form.appendChild(input);
            };

            addHidden('_token', '{{ csrf_token() }}');
            addHidden('action', 'remove_label');
            addHidden('label_id', labelId);
            ids.forEach(id => addHidden('message_ids[]', id));

            document.body.appendChild(form);
            form.submit();
        });
    }

    // ===== Toggle star =====
    document.querySelectorAll('.toggle-star').forEach(function (button) {
        button.addEventListener('click', function () {
            const messageId = this.dataset.id;
            fetch(`/admin/communications/messages/${messageId}/toggle-star`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => { if (data.success) location.reload(); })
            .catch(error => console.error('Error:', error));
        });
    });

    // ===== Toggle important =====
    document.querySelectorAll('.toggle-important').forEach(function (button) {
        button.addEventListener('click', function () {
            const messageId = this.dataset.id;
            fetch(`/admin/communications/messages/${messageId}/toggle-important`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => { if (data.success) location.reload(); })
            .catch(error => console.error('Error:', error));
        });
    });

    // ===== Toggle flag =====
    document.querySelectorAll('.toggle-flag').forEach(function (button) {
        button.addEventListener('click', function () {
            const messageId = this.dataset.id;
            fetch(`/admin/communications/messages/${messageId}/toggle-flag`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => { if (data.success) location.reload(); })
            .catch(error => console.error('Error:', error));
        });
    });

    // ===== Acknowledge message =====
    document.querySelectorAll('.acknowledge-message').forEach(function (button) {
        button.addEventListener('click', function () {
            const messageId = this.dataset.id;
            fetch(`/admin/communications/messages/${messageId}/acknowledge`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => { if (data.success) location.reload(); })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endsection

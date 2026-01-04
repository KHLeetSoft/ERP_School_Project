@extends('admin.layout.app')

@section('title', 'Edit Message')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Edit Message</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.index') }}">Communications</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.messages.index') }}">Messages</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.messages.drafts') }}">Drafts</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Draft Message</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.messages.update', $message->id) }}" method="POST" enctype="multipart/form-data" id="messageForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Recipients Section -->
                                <div class="mb-3">
                                    <label for="recipients_to" class="form-label">To <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="recipients_to" name="recipients_to[]" multiple required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, $recipientsTo) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('recipients_to')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="recipients_cc" class="form-label">CC</label>
                                            <select class="form-select select2" id="recipients_cc" name="recipients_cc[]" multiple>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ in_array($user->id, $recipientsCc) ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="recipients_bcc" class="form-label">BCC</label>
                                            <select class="form-select select2" id="recipients_bcc" name="recipients_bcc[]" multiple>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ in_array($user->id, $recipientsBcc) ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Department -->
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select class="form-select" id="department_id" name="department_id">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ $message->department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Subject -->
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $message->subject) }}" required>
                                    @error('subject')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Message Body -->
                                <div class="mb-3">
                                    <label for="body" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="body" name="body" rows="12" required>{{ old('body', $message->body) }}</textarea>
                                    @error('body')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Attachments -->
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">Attachments</label>
                                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                                    <small class="text-muted">You can select multiple files. Max size: 10MB per file.</small>
                                    
                                    @if($message->attachments)
                                        <div class="mt-2">
                                            <label class="form-label">Current Attachments:</label>
                                            <div class="list-group">
                                                @foreach($message->attachments as $index => $attachment)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-paperclip me-2"></i>
                                                            {{ $attachment['name'] }}
                                                            <small class="text-muted ms-2">({{ number_format($attachment['size'] / 1024, 2) }} KB)</small>
                                                        </div>
                                                        <a href="{{ route('admin.messages.download-attachment', [$message->id, $index]) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Message Options -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Message Options</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Priority -->
                                        <div class="mb-3">
                                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                            <select class="form-select" id="priority" name="priority" required>
                                                <option value="low" {{ $message->priority == 'low' ? 'selected' : '' }}>Low</option>
                                                <option value="normal" {{ $message->priority == 'normal' ? 'selected' : '' }}>Normal</option>
                                                <option value="high" {{ $message->priority == 'high' ? 'selected' : '' }}>High</option>
                                                <option value="urgent" {{ $message->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                            </select>
                                        </div>

                                        <!-- Type -->
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                            <select class="form-select" id="type" name="type" required>
                                                <option value="direct" {{ $message->type == 'direct' ? 'selected' : '' }}>Direct</option>
                                                <option value="broadcast" {{ $message->type == 'broadcast' ? 'selected' : '' }}>Broadcast</option>
                                                <option value="announcement" {{ $message->type == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                                <option value="system" {{ $message->type == 'system' ? 'selected' : '' }}>System</option>
                                            </select>
                                        </div>

                                        <!-- Flags -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_important" name="is_important" value="1" {{ $message->is_important ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_important">
                                                    Mark as Important
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_encrypted" name="is_encrypted" value="1" {{ $message->is_encrypted ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_encrypted">
                                                    Encrypt Message
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="requires_acknowledgment" name="requires_acknowledgment" value="1" {{ $message->requires_acknowledgment ? 'checked' : '' }}>
                                                <label class="form-check-label" for="requires_acknowledgment">
                                                    Require Acknowledgment
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Expiration -->
                                        <div class="mb-3">
                                            <label for="expires_at" class="form-label">Expires At</label>
                                            <input type="datetime-local" class="form-control" id="expires_at" name="expires_at" value="{{ $message->expires_at ? $message->expires_at->format('Y-m-d\TH:i') : '' }}">
                                        </div>

                                        <!-- Labels -->
                                        @if($labels && $labels->count() > 0)
                                            <div class="mb-3">
                                                <label class="form-label">Labels</label>
                                                @foreach($labels as $label)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="labels[]" value="{{ $label->id }}" id="label_{{ $label->id }}"
                                                               {{ in_array($label->id, $message->labels->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="label_{{ $label->id }}">
                                                            <span class="badge" style="background-color: {{ $label->color }}; color: white;">
                                                                {{ $label->name }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <button type="submit" name="save_as_draft" value="1" class="btn btn-outline-secondary">
                                        <i class="fas fa-save"></i> Save as Draft
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Send Message
                                    </button>
                                    <a href="{{ route('admin.messages.drafts') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for recipient fields
    $('.select2').select2({
        placeholder: 'Select recipients...',
        allowClear: true
    });

    // Initialize CKEditor for message body
    ClassicEditor
        .create(document.querySelector('#body'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'undo', 'redo'],
            placeholder: 'Type your message here...'
        })
        .catch(error => {
            console.error(error);
        });

    // Form validation
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        const recipientsTo = document.getElementById('recipients_to').value;
        const subject = document.getElementById('subject').value.trim();
        const body = document.getElementById('body').value.trim();

        if (!recipientsTo || recipientsTo.length === 0) {
            alert('Please select at least one recipient.');
            e.preventDefault();
            return false;
        }

        if (!subject) {
            alert('Please enter a subject.');
            e.preventDefault();
            return false;
        }

        if (!body) {
            alert('Please enter a message.');
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush

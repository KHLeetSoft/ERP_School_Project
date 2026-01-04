@extends('admin.layout.app')

@section('title', 'Compose Message')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    @if($replyTo)
                        Reply to Message
                    @elseif($forwardFrom)
                        Forward Message
                    @else
                        Compose Message
                    @endif
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.messages.dashboard') }}">Dashboard</a></li>
         
                    <li class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">Messages</a></li>
                    <li class="breadcrumb-item active">Compose</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Inbox
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        @if($replyTo)
                            Reply to: {{ $replyTo->subject }}
                        @elseif($forwardFrom)
                            Forward: {{ $forwardFrom->subject }}
                        @else
                            New Message
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data" id="messageForm">
                        @csrf
                        
                        @if($replyTo)
                            <input type="hidden" name="parent_id" value="{{ $replyTo->id }}">
                            <input type="hidden" name="thread_id" value="{{ $replyTo->thread_id ?? $replyTo->id }}">
                        @elseif($forwardFrom)
                            <input type="hidden" name="forward_from_id" value="{{ $forwardFrom->id }}">
                        @endif

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-8">
                                <!-- Subject -->
                                <div class="form-group">
                                    <label for="subject">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject', $replyTo ? 'Re: ' . $replyTo->subject : '') }}" 
                                           placeholder="Enter message subject" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Message Body -->
                                <div class="form-group">
                                    <label for="body">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('body') is-invalid @enderror" 
                                              id="body" name="body" rows="12" 
                                              placeholder="Type your message here..." required>{{ old('body') }}</textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Attachments -->
                                <div class="form-group">
                                    <label for="attachments">Attachments</label>
                                    <input type="file" class="form-control-file @error('attachments.*') is-invalid @enderror" 
                                           id="attachments" name="attachments[]" multiple>
                                    <small class="form-text text-muted">
                                        You can select multiple files. Maximum file size: 10MB per file.
                                    </small>
                                    @error('attachments.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Forwarded Message Preview -->
                                @if($forwardFrom)
                                <div class="form-group">
                                    <label>Forwarded Message</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <strong>From: {{ $forwardFrom->sender->name }}</strong>
                                                <small class="text-muted">{{ $forwardFrom->sent_at->format('M d, Y H:i') }}</small>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Subject:</strong> {{ $forwardFrom->subject }}
                                            </div>
                                            <div class="border-top pt-2">
                                                <strong>Message:</strong>
                                                <div class="mt-1">
                                                    {!! Str::limit(strip_tags($forwardFrom->body), 200) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <!-- Recipients -->
                                <div class="form-group">
                                    <label for="recipients_to">To <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('recipients_to') is-invalid @enderror" 
                                            id="recipients_to" name="recipients_to[]" multiple required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ in_array($user->id, old('recipients_to', [])) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('recipients_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- CC Recipients -->
                                <div class="form-group">
                                    <label for="recipients_cc">CC</label>
                                    <select class="form-control select2" id="recipients_cc" name="recipients_cc[]" multiple>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ in_array($user->id, old('recipients_cc', [])) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- BCC Recipients -->
                                <div class="form-group">
                                    <label for="recipients_bcc">BCC</label>
                                    <select class="form-control select2" id="recipients_bcc" name="recipients_bcc[]" multiple>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ in_array($user->id, old('recipients_bcc', [])) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Department -->
                                <div class="form-group">
                                    <label for="department_id">Department</label>
                                    <select class="form-control @error('department_id') is-invalid @enderror" 
                                            id="department_id" name="department_id">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" 
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Message Type -->
                                <div class="form-group">
                                    <label for="type">Message Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="direct" {{ old('type') == 'direct' ? 'selected' : '' }}>Direct Message</option>
                                        <option value="broadcast" {{ old('type') == 'broadcast' ? 'selected' : '' }}>Broadcast</option>
                                        <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                        <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>System Message</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Priority -->
                                <div class="form-group">
                                    <label for="priority">Priority <span class="text-danger">*</span></label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Expiration Date -->
                                <div class="form-group">
                                    <label for="expires_at">Expires At</label>
                                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                                    <small class="form-text text-muted">Leave empty if message should not expire</small>
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Labels -->
                                @if($labels && $labels->count() > 0)
                                <div class="form-group">
                                    <label for="labels">Labels</label>
                                    <select class="form-control select2" id="labels" name="labels[]" multiple>
                                        @foreach($labels as $label)
                                            <option value="{{ $label->id }}" 
                                                {{ in_array($label->id, old('labels', [])) ? 'selected' : '' }}>
                                                {{ $label->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Options -->
                                <div class="form-group">
                                    <label>Options</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_important" name="is_important" value="1" {{ old('is_important') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_important">Mark as Important</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_encrypted" name="is_encrypted" value="1" {{ old('is_encrypted') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_encrypted">Encrypt Message</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="requires_acknowledgment" name="requires_acknowledgment" value="1" {{ old('requires_acknowledgment') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="requires_acknowledgment">Require Acknowledgment</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="submit" name="save_as_draft" value="1" class="btn btn-secondary">
                                            <i class="fas fa-save"></i> Save as Draft
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary mr-2">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> 
                                            @if($replyTo)
                                                Send Reply
                                            @elseif($forwardFrom)
                                                Forward Message
                                            @else
                                                Send Message
                                            @endif
                                        </button>
                                    </div>
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

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: 1px solid #0056b3;
    color: white;
    border-radius: 0.2rem;
    padding: 0.25rem 0.5rem;
    margin: 0.125rem;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
    margin-right: 0.5rem;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Select options...',
        allowClear: true,
        width: '100%'
    });

    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#body'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'undo', 'redo'],
            placeholder: 'Type your message here...'
        })
        .catch(error => {
            console.error(error);
        });

    // Form validation
    const form = document.getElementById('messageForm');
    form.addEventListener('submit', function(e) {
        const recipients = document.getElementById('recipients_to').value;
        if (!recipients || recipients.length === 0) {
            e.preventDefault();
            alert('Please select at least one recipient.');
            return false;
        }
    });

    // Auto-fill subject for replies
    @if($replyTo)
        document.getElementById('subject').focus();
    @endif

    // Priority change handler
    document.getElementById('priority').addEventListener('change', function() {
        const priority = this.value;
        const importantCheckbox = document.getElementById('is_important');
        
        if (priority === 'urgent') {
            importantCheckbox.checked = true;
            importantCheckbox.disabled = true;
        } else {
            importantCheckbox.disabled = false;
        }
    });

    // Type change handler
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        const departmentField = document.getElementById('department_id');
        
        if (type === 'broadcast' || type === 'announcement') {
            departmentField.required = true;
        } else {
            departmentField.required = false;
        }
    });
});
</script>
@endsection

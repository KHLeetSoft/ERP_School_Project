@extends('admin.layout.app')

@section('title', 'Edit SMS Message')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.communications.sms.index') }}">SMS Messages</a></li>
                        <li class="breadcrumb-item active">Edit Message</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit SMS Message</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Edit Message #{{ $smsMessage->id }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.communications.sms.update', $smsMessage->id) }}" method="POST" id="editSmsForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="4" 
                                              placeholder="Enter your message content here...">{{ old('message', $smsMessage->message) }}</textarea>
                                    <div class="form-text">
                                        <span id="charCount">0</span> characters | 
                                        <span id="smsCount">0</span> SMS
                                    </div>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="template_id" class="form-label">Template</label>
                                            <select class="form-select @error('template_id') is-invalid @enderror" 
                                                    id="template_id" name="template_id">
                                                <option value="">Select Template</option>
                                                @foreach($templates as $template)
                                                    <option value="{{ $template->id }}" 
                                                            {{ old('template_id', $smsMessage->template_id) == $template->id ? 'selected' : '' }}>
                                                        {{ $template->name }} ({{ $template->category }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('template_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category</label>
                                            <select class="form-select @error('category') is-invalid @enderror" 
                                                    id="category" name="category">
                                                <option value="">Select Category</option>
                                                <option value="notification" {{ old('category', $smsMessage->category) == 'notification' ? 'selected' : '' }}>Notification</option>
                                                <option value="reminder" {{ old('category', $smsMessage->category) == 'reminder' ? 'selected' : '' }}>Reminder</option>
                                                <option value="alert" {{ old('category', $smsMessage->category) == 'alert' ? 'selected' : '' }}>Alert</option>
                                                <option value="welcome" {{ old('category', $smsMessage->category) == 'welcome' ? 'selected' : '' }}>Welcome</option>
                                                <option value="birthday" {{ old('category', $smsMessage->category) == 'birthday' ? 'selected' : '' }}>Birthday</option>
                                                <option value="custom" {{ old('category', $smsMessage->category) == 'custom' ? 'selected' : '' }}>Custom</option>
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="priority" class="form-label">Priority</label>
                                            <select class="form-select @error('priority') is-invalid @enderror" 
                                                    id="priority" name="priority">
                                                <option value="low" {{ old('priority', $smsMessage->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                                <option value="medium" {{ old('priority', $smsMessage->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                                <option value="high" {{ old('priority', $smsMessage->priority) == 'high' ? 'selected' : '' }}>High</option>
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gateway_id" class="form-label">SMS Gateway</label>
                                            <select class="form-select @error('gateway_id') is-invalid @enderror" 
                                                    id="gateway_id" name="gateway_id">
                                                <option value="">Select Gateway</option>
                                                @foreach($gateways as $gateway)
                                                    <option value="{{ $gateway->id }}" 
                                                            {{ old('gateway_id', $smsMessage->gateway_id) == $gateway->id ? 'selected' : '' }}>
                                                        {{ $gateway->name }} ({{ $gateway->provider }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('gateway_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="scheduled_at" class="form-label">Schedule Date & Time</label>
                                            <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                                   id="scheduled_at" name="scheduled_at" 
                                                   value="{{ old('scheduled_at', $smsMessage->scheduled_at ? $smsMessage->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                            <div class="form-text">Leave empty to send immediately</div>
                                            @error('scheduled_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expires_at" class="form-label">Expires At</label>
                                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                                   id="expires_at" name="expires_at" 
                                                   value="{{ old('expires_at', $smsMessage->expires_at ? $smsMessage->expires_at->format('Y-m-d\TH:i') : '') }}">
                                            <div class="form-text">Leave empty for no expiration</div>
                                            @error('expires_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" 
                                                       id="requires_confirmation" name="requires_confirmation" value="1"
                                                       {{ old('requires_confirmation', $smsMessage->requires_confirmation) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="requires_confirmation">
                                                    Require Delivery Confirmation
                                                </label>
                                            </div>
                                            <div class="form-text">Recipients will need to confirm receipt</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_retries" class="form-label">Max Retries</label>
                                            <input type="number" class="form-control @error('max_retries') is-invalid @enderror" 
                                                   id="max_retries" name="max_retries" min="0" max="5" 
                                                   value="{{ old('max_retries', $smsMessage->max_retries ?? 3) }}">
                                            @error('max_retries')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Recipients</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="recipient_type" class="form-label">Recipient Type</label>
                                            <select class="form-select @error('recipient_type') is-invalid @enderror" 
                                                    id="recipient_type" name="recipient_type">
                                                <option value="">Select Type</option>
                                                <option value="students" {{ old('recipient_type', $smsMessage->recipient_type) == 'students' ? 'selected' : '' }}>Students</option>
                                                <option value="parents" {{ old('recipient_type', $smsMessage->recipient_type) == 'parents' ? 'selected' : '' }}>Parents</option>
                                                <option value="staff" {{ old('recipient_type', $smsMessage->recipient_type) == 'staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="custom" {{ old('recipient_type', $smsMessage->recipient_type) == 'custom' ? 'selected' : '' }}>Custom Numbers</option>
                                            </select>
                                            @error('recipient_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3" id="recipient_ids_container">
                                            <label for="recipient_ids" class="form-label">Select Recipients</label>
                                            <select class="form-select @error('recipient_ids') is-invalid @enderror" 
                                                    id="recipient_ids" name="recipient_ids[]" multiple>
                                                <!-- Options will be populated via AJAX -->
                                            </select>
                                            @error('recipient_ids')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3" id="custom_numbers_container" style="display: none;">
                                            <label for="custom_numbers" class="form-label">Custom Phone Numbers</label>
                                            <textarea class="form-control @error('custom_numbers') is-invalid @enderror" 
                                                      id="custom_numbers" name="custom_numbers" rows="3" 
                                                      placeholder="Enter phone numbers, one per line (e.g., +1234567890)">{{ old('custom_numbers') }}</textarea>
                                            <div class="form-text">One phone number per line</div>
                                            @error('custom_numbers')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="mdi mdi-information"></i>
                                            <strong>Current Recipients:</strong> {{ $smsMessage->recipients->count() }} {{ Str::plural('recipient', $smsMessage->recipients->count()) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Message Preview</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="border rounded p-2 bg-light">
                                            <small id="messagePreview">{{ $smsMessage->message }}</small>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <span id="previewCharCount">0</span> characters | 
                                                <span id="previewSmsCount">0</span> SMS
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.communications.sms.show', $smsMessage->id) }}" 
                                       class="btn btn-secondary">
                                        <i class="mdi mdi-arrow-left"></i> Back to Details
                                    </a>
                                    <div>
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="mdi mdi-content-save"></i> Update Message
                                        </button>
                                        <a href="{{ route('admin.communications.sms.index') }}" 
                                           class="btn btn-light">
                                            Cancel
                                        </a>
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

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize character and SMS count
    updateCharCount();
    
    // Handle template selection
    $('#template_id').change(function() {
        if ($(this).val()) {
            loadTemplateContent($(this).val());
        }
    });

    // Handle recipient type change
    $('#recipient_type').change(function() {
        const type = $(this).val();
        if (type === 'custom') {
            $('#custom_numbers_container').show();
            $('#recipient_ids_container').hide();
        } else {
            $('#custom_numbers_container').hide();
            $('#recipient_ids_container').show();
            if (type) {
                loadRecipients(type);
            }
        }
    });

    // Handle message content change
    $('#message').on('input', function() {
        updateCharCount();
        updateMessagePreview();
    });

    // Load initial recipients if type is set
    if ($('#recipient_type').val() && $('#recipient_type').val() !== 'custom') {
        loadRecipients($('#recipient_type').val());
    }
});

function updateCharCount() {
    const message = $('#message').val();
    const charCount = message.length;
    const smsCount = Math.ceil(charCount / 160);
    
    $('#charCount').text(charCount);
    $('#smsCount').text(smsCount);
}

function updateMessagePreview() {
    const message = $('#message').val();
    $('#messagePreview').text(message);
    
    const charCount = message.length;
    const smsCount = Math.ceil(charCount / 160);
    
    $('#previewCharCount').text(charCount);
    $('#previewSmsCount').text(smsCount);
}

function loadTemplateContent(templateId) {
    fetch(`/admin/communications/sms/templates/${templateId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#message').val(data.template.content);
                updateCharCount();
                updateMessagePreview();
            }
        })
        .catch(error => {
            console.error('Error loading template:', error);
        });
}

function loadRecipients(type) {
    fetch(`/admin/communications/sms/recipient-suggestions?type=${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = $('#recipient_ids');
                select.empty();
                
                data.recipients.forEach(recipient => {
                    const option = new Option(recipient.name, recipient.id);
                    option.setAttribute('data-phone', recipient.phone);
                    select.append(option);
                });
                
                // Select current recipients if editing
                const currentRecipients = @json($smsMessage->recipient_ids ?? []);
                select.val(currentRecipients);
            }
        })
        .catch(error => {
            console.error('Error loading recipients:', error);
        });
}

// Form validation
$('#editSmsForm').submit(function(e) {
    const message = $('#message').val().trim();
    const recipientType = $('#recipient_type').val();
    
    if (!message) {
        e.preventDefault();
        alert('Please enter a message.');
        $('#message').focus();
        return false;
    }
    
    if (!recipientType) {
        e.preventDefault();
        alert('Please select a recipient type.');
        $('#recipient_type').focus();
        return false;
    }
    
    if (recipientType === 'custom') {
        const customNumbers = $('#custom_numbers').val().trim();
        if (!customNumbers) {
            e.preventDefault();
            alert('Please enter custom phone numbers.');
            $('#custom_numbers').focus();
            return false;
        }
    } else {
        const recipientIds = $('#recipient_ids').val();
        if (!recipientIds || recipientIds.length === 0) {
            e.preventDefault();
            alert('Please select at least one recipient.');
            $('#recipient_ids').focus();
            return false;
        }
    }
    
    return true;
});
</script>
@endsection

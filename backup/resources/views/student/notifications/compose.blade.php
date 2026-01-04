@extends('student.layout.app')

@section('title', 'Compose Notification')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Compose Notification
        </h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted">Create and send notifications</span>
        </div>
    </div>

    <!-- Compose Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Compose New Notification
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.notifications.send') }}" id="composeForm">
                        @csrf
                        
                        <!-- Recipients -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Recipients <span class="text-danger">*</span></label>
                            <div class="row">
                                @if(isset($recipients))
                                    @foreach($recipients as $key => $label)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="recipients[]" value="{{ $key }}" 
                                                   {{ old('recipients') && in_array($key, old('recipients')) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                <i class="fas fa-users me-2"></i>{{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="subject" 
                                   value="{{ old('subject') }}" 
                                   placeholder="Enter notification subject..." required>
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="message" rows="6" 
                                      placeholder="Enter your notification message..." required>{{ old('message') }}</textarea>
                            <div class="form-text">Maximum 1000 characters</div>
                        </div>

                        <!-- Type and Priority -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="type" required>
                                    <option value="">Select Type</option>
                                    @if(isset($notificationTypes))
                                        @foreach($notificationTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Priority <span class="text-danger">*</span></label>
                                <select class="form-select" name="priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Schedule</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Send Date</label>
                                    <input type="date" class="form-control" name="schedule_date" 
                                           value="{{ old('schedule_date') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Send Time</label>
                                    <input type="time" class="form-control" name="schedule_time" 
                                           value="{{ old('schedule_time') }}">
                                </div>
                            </div>
                            <div class="form-text">Leave empty to send immediately</div>
                        </div>

                        <!-- Templates -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Templates</label>
                            <select class="form-select" id="templateSelect" onchange="loadTemplate()">
                                <option value="">Select a template</option>
                                @if(isset($templates))
                                    @foreach($templates as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Attachments</label>
                            <input type="file" class="form-control" name="attachments[]" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.png">
                            <div class="form-text">Maximum file size: 10MB per file</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Send Notification
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="saveDraft()">
                                    <i class="fas fa-save me-2"></i>Save Draft
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <a href="{{ route('student.notifications.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Compose Help -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Compose Tips
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Writing Tips</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>Keep subject lines clear and concise</li>
                        <li><i class="fas fa-check text-success me-2"></i>Use bullet points for important information</li>
                        <li><i class="fas fa-check text-success me-2"></i>Include relevant dates and times</li>
                        <li><i class="fas fa-check text-success me-2"></i>Be specific about actions required</li>
                    </ul>

                    <h6 class="text-primary mt-3">Priority Guidelines</h6>
                    <ul class="list-unstyled small">
                        <li><span class="badge bg-secondary me-2">Low</span>General announcements</li>
                        <li><span class="badge bg-info me-2">Medium</span>Important updates</li>
                        <li><span class="badge bg-warning me-2">High</span>Urgent matters</li>
                        <li><span class="badge bg-danger me-2">Urgent</span>Critical alerts</li>
                    </ul>
                </div>
            </div>

            <!-- Character Count -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-text-width me-2"></i>Character Count
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Characters:</span>
                        <span id="charCount">0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Words:</span>
                        <span id="wordCount">0</span>
                    </div>
                    <div class="progress mt-2">
                        <div class="progress-bar" id="charProgress" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div id="previewContent">
                        <p class="text-muted">Start typing to see preview...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character count and preview
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.querySelector('textarea[name="message"]');
    const charCount = document.getElementById('charCount');
    const wordCount = document.getElementById('wordCount');
    const charProgress = document.getElementById('charProgress');
    const previewContent = document.getElementById('previewContent');

    messageTextarea.addEventListener('input', function() {
        const text = this.value;
        const charLength = text.length;
        const wordLength = text.trim().split(/\s+/).filter(word => word.length > 0).length;
        
        charCount.textContent = charLength;
        wordCount.textContent = wordLength;
        
        // Update progress bar
        const progress = Math.min((charLength / 1000) * 100, 100);
        charProgress.style.width = progress + '%';
        charProgress.className = 'progress-bar ' + (progress > 90 ? 'bg-danger' : progress > 70 ? 'bg-warning' : 'bg-success');
        
        // Update preview
        if (text.trim()) {
            previewContent.innerHTML = '<p>' + text.replace(/\n/g, '<br>') + '</p>';
        } else {
            previewContent.innerHTML = '<p class="text-muted">Start typing to see preview...</p>';
        }
    });
});

function loadTemplate() {
    const templateSelect = document.getElementById('templateSelect');
    const templates = {
        'assignment_reminder': {
            subject: 'Assignment Reminder',
            message: 'This is a reminder that your assignment is due soon. Please ensure you submit it on time to avoid any penalties.\n\nAssignment Details:\n- Due Date: [DATE]\n- Submission: [LOCATION]\n- Requirements: [REQUIREMENTS]'
        },
        'exam_notice': {
            subject: 'Exam Notice',
            message: 'Important exam information:\n\n- Date: [DATE]\n- Time: [TIME]\n- Location: [LOCATION]\n- Duration: [DURATION]\n- Materials: [MATERIALS]\n\nPlease arrive 15 minutes early and bring your student ID.'
        },
        'general_announcement': {
            subject: 'General Announcement',
            message: 'This is a general announcement for all students.\n\n[ANNOUNCEMENT DETAILS]\n\nIf you have any questions, please contact the relevant department.'
        },
        'urgent_alert': {
            subject: 'URGENT ALERT',
            message: 'URGENT: This is an important alert that requires immediate attention.\n\n[ALERT DETAILS]\n\nPlease take necessary action as soon as possible.'
        }
    };

    const selectedTemplate = templates[templateSelect.value];
    if (selectedTemplate) {
        document.querySelector('input[name="subject"]').value = selectedTemplate.subject;
        document.querySelector('textarea[name="message"]').value = selectedTemplate.message;
        
        // Trigger input event to update character count
        document.querySelector('textarea[name="message"]').dispatchEvent(new Event('input'));
    }
}

function saveDraft() {
    // Here you would typically save the draft to the database
    alert('Draft saved successfully!');
}

function resetForm() {
    if (confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
        document.getElementById('composeForm').reset();
        document.getElementById('charCount').textContent = '0';
        document.getElementById('wordCount').textContent = '0';
        document.getElementById('charProgress').style.width = '0%';
        document.getElementById('charProgress').className = 'progress-bar';
        document.getElementById('previewContent').innerHTML = '<p class="text-muted">Start typing to see preview...</p>';
    }
}

// Form validation
document.getElementById('composeForm').addEventListener('submit', function(e) {
    const recipients = document.querySelectorAll('input[name="recipients[]"]:checked');
    const subject = document.querySelector('input[name="subject"]').value.trim();
    const message = document.querySelector('textarea[name="message"]').value.trim();
    const type = document.querySelector('select[name="type"]').value;
    const priority = document.querySelector('select[name="priority"]').value;

    if (recipients.length === 0) {
        e.preventDefault();
        alert('Please select at least one recipient.');
        return;
    }

    if (!subject) {
        e.preventDefault();
        alert('Please enter a subject.');
        return;
    }

    if (!message) {
        e.preventDefault();
        alert('Please enter a message.');
        return;
    }

    if (!type) {
        e.preventDefault();
        alert('Please select a notification type.');
        return;
    }

    if (!priority) {
        e.preventDefault();
        alert('Please select a priority level.');
        return;
    }

    if (message.length > 1000) {
        e.preventDefault();
        alert('Message is too long. Maximum 1000 characters allowed.');
        return;
    }
});
</script>
@endsection

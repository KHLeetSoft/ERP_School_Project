@extends('admin.layout.app')

@section('title', 'Create Parent Communication')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Parent Communication</h4>
                    <a href="{{ route('admin.parents.communication.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.parents.communication.store') }}" method="POST" id="communication-form">
                        @csrf
                        
                        <div class="row">
                            <!-- Parent Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="parent_detail_id" class="form-label">Parent <span class="text-danger">*</span></label>
                                <select name="parent_detail_id" id="parent_detail_id" class="form-select @error('parent_detail_id') is-invalid @enderror" required>
                                    <option value="">Select Parent</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_detail_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->primary_contact_name ?? $parent->user->name ?? 'Parent #' . $parent->id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_detail_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Student Selection (Optional) -->
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student (Optional)</label>
                                <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror">
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->student_id ? 'selected' : '' }}>
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Communication Type -->
                            <div class="col-md-6 mb-3">
                                <label for="communication_type" class="form-label">Communication Type <span class="text-danger">*</span></label>
                                <select name="communication_type" id="communication_type" class="form-select @error('communication_type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    @foreach($communicationTypes as $type)
                                        <option value="{{ $type }}" {{ old('communication_type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('communication_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="">Select Priority</option>
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority }}" {{ old('priority') == $priority ? 'selected' : '' }}>
                                            {{ ucfirst($priority) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select @error('category') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Admin (Optional) -->
                            <div class="col-md-6 mb-3">
                                <label for="admin_id" class="form-label">Admin (Optional)</label>
                                <select name="admin_id" id="admin_id" class="form-select @error('admin_id') is-invalid @enderror">
                                    <option value="">Select Admin</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('admin_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div class="col-md-12 mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" 
                                       value="{{ old('subject') }}" placeholder="Enter subject (optional)">
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div class="col-md-12 mb-3">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" rows="6" class="form-control @error('message') is-invalid @enderror" 
                                          placeholder="Enter your message here..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Communication Channel -->
                            <div class="col-md-6 mb-3">
                                <label for="communication_channel" class="form-label">Communication Channel</label>
                                <input type="text" name="communication_channel" id="communication_channel" class="form-control @error('communication_channel') is-invalid @enderror" 
                                       value="{{ old('communication_channel') }}" placeholder="e.g., Gmail, Twilio, etc.">
                                @error('communication_channel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cost -->
                            <div class="col-md-6 mb-3">
                                <label for="cost" class="form-label">Cost (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="cost" id="cost" class="form-control @error('cost') is-invalid @enderror" 
                                           value="{{ old('cost') }}" step="0.01" min="0" placeholder="0.00">
                                </div>
                                @error('cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">For SMS, phone calls, or other paid services</small>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Additional notes or instructions...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dynamic Fields Based on Communication Type -->
                        <div id="dynamic-fields" class="row" style="display: none;">
                            <!-- Fields will be populated via JavaScript -->
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-send"></i> Send Communication
                                </button>
                                <a href="{{ route('admin.parents.communication.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
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
    // Auto-populate student dropdown when parent is selected
    $('#parent_detail_id').on('change', function() {
        const parentId = $(this).val();
        if (parentId) {
            loadParentStudents(parentId);
        } else {
            $('#student_id').html('<option value="">Select Student</option>');
        }
    });

    // Show/hide dynamic fields based on communication type
    $('#communication_type').on('change', function() {
        const type = $(this).val();
        showDynamicFields(type);
    });

    // Form validation
    $('#communication-form').on('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });

    // Initialize dynamic fields if type is pre-selected
    const selectedType = $('#communication_type').val();
    if (selectedType) {
        showDynamicFields(selectedType);
    }
});

function loadParentStudents(parentId) {
    $.ajax({
        url: '{{ route("admin.parents.communication.get-parent-students", ":id") }}'.replace(':id', parentId),
        method: 'GET',
        success: function(students) {
            let options = '<option value="">Select Student</option>';
            students.forEach(function(student) {
                options += `<option value="${student.id}">${student.name}</option>`;
            });
            $('#student_id').html(options);
        },
        error: function() {
            $('#student_id').html('<option value="">Error loading students</option>');
        }
    });
}

function showDynamicFields(type) {
    const dynamicFields = $('#dynamic-fields');
    let fieldsHtml = '';

    switch(type) {
        case 'email':
            fieldsHtml = `
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Template</label>
                    <select class="form-select" id="email_template">
                        <option value="">Select Template</option>
                        <option value="academic_progress">Academic Progress</option>
                        <option value="behavior_notice">Behavior Notice</option>
                        <option value="attendance_alert">Attendance Alert</option>
                        <option value="fee_reminder">Fee Reminder</option>
                        <option value="general_announcement">General Announcement</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Send Copy to</label>
                    <input type="email" class="form-control" placeholder="Additional email addresses">
                </div>
            `;
            break;

        case 'sms':
            fieldsHtml = `
                <div class="col-md-6 mb-3">
                    <label class="form-label">SMS Template</label>
                    <select class="form-select" id="sms_template">
                        <option value="">Select Template</option>
                        <option value="urgent_alert">Urgent Alert</option>
                        <option value="reminder">Reminder</option>
                        <option value="confirmation">Confirmation</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Character Count</label>
                    <span class="form-control-plaintext" id="char-count">0</span>
                </div>
            `;
            break;

        case 'phone':
            fieldsHtml = `
                <div class="col-md-6 mb-3">
                    <label class="form-label">Call Duration</label>
                    <input type="number" class="form-control" placeholder="Minutes" min="1">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Call Purpose</label>
                    <input type="text" class="form-control" placeholder="Brief purpose of call">
                </div>
            `;
            break;

        case 'meeting':
            fieldsHtml = `
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meeting Date</label>
                    <input type="datetime-local" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meeting Location</label>
                    <input type="text" class="form-control" placeholder="Room number or location">
                </div>
            `;
            break;

        case 'letter':
            fieldsHtml = `
                <div class="col-md-6 mb-3">
                    <label class="form-label">Letter Type</label>
                    <select class="form-select">
                        <option value="">Select Type</option>
                        <option value="formal">Formal Letter</option>
                        <option value="informal">Informal Letter</option>
                        <option value="official">Official Notice</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Delivery Method</label>
                    <select class="form-select">
                        <option value="">Select Method</option>
                        <option value="postal">Postal Mail</option>
                        <option value="hand_delivered">Hand Delivered</option>
                        <option value="courier">Courier</option>
                    </select>
                </div>
            `;
            break;
    }

    if (fieldsHtml) {
        dynamicFields.html(fieldsHtml).show();
    } else {
        dynamicFields.hide();
    }
}

function validateForm() {
    let isValid = true;
    
    // Check required fields
    const requiredFields = ['parent_detail_id', 'communication_type', 'message', 'priority'];
    requiredFields.forEach(function(field) {
        const value = $('#' + field).val();
        if (!value) {
            $('#' + field).addClass('is-invalid');
            isValid = false;
        } else {
            $('#' + field).removeClass('is-invalid');
        }
    });

    // Validate message length for SMS
    const type = $('#communication_type').val();
    if (type === 'sms') {
        const message = $('#message').val();
        if (message.length > 160) {
            $('#message').addClass('is-invalid');
            $('<div class="invalid-feedback">SMS message cannot exceed 160 characters</div>').insertAfter('#message');
            isValid = false;
        }
    }

    return isValid;
}

// Character count for SMS
$(document).on('input', '#message', function() {
    const count = $(this).val().length;
    $('#char-count').text(count);
    
    if (count > 160) {
        $(this).addClass('is-invalid');
    } else {
        $(this).removeClass('is-invalid');
    }
});

// Template selection
$(document).on('change', '#email_template, #sms_template', function() {
    const template = $(this).val();
    if (template) {
        loadTemplate(template, $(this).attr('id'));
    }
});

function loadTemplate(template, type) {
    const templates = {
        'academic_progress': 'Dear Parent, I hope this message finds you well. I wanted to share some updates about your child\'s academic progress...',
        'behavior_notice': 'Dear Parent, I need to bring to your attention a behavioral incident that occurred today...',
        'attendance_alert': 'Dear Parent, I noticed that your child has been absent for the past few days...',
        'fee_reminder': 'Dear Parent, This is a friendly reminder about the upcoming fee payment deadline...',
        'general_announcement': 'Dear Parent, I hope this message finds you well. I wanted to share some important information...',
        'urgent_alert': 'URGENT: Please contact the school immediately regarding an important matter.',
        'reminder': 'Reminder: Parent-teacher meeting tomorrow at 3 PM.',
        'confirmation': 'Confirmed: Your appointment has been scheduled for tomorrow at 2 PM.'
    };

    if (templates[template]) {
        $('#message').val(templates[template]);
        if (type === 'sms_template') {
            $('#char-count').text(templates[template].length);
        }
    }
}
</script>
@endsection

@extends('admin.layout.app')

@section('title', 'Send New SMS')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('styles')
<style>
.compose-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.recipient-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.recipient-count {
    background: #e9ecef;
    color: #495057;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 1rem;
}

.template-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.template-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.template-item:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.15);
}

.template-item.selected {
    border-color: #007bff;
    background-color: #f8f9ff;
}

.character-count {
    position: absolute;
    bottom: 0.5rem;
    right: 1rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.message-input-wrapper {
    position: relative;
}

.message-input-wrapper textarea {
    padding-bottom: 2rem;
}

.recipient-type-tabs {
    border-bottom: 2px solid #dee2e6;
    margin-bottom: 1rem;
}

.recipient-type-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1rem;
}

.recipient-type-tabs .nav-link.active {
    color: #007bff;
    border-bottom-color: #007bff;
    background: none;
}

.recipient-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.5rem;
}

.recipient-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-bottom: 1px solid #f1f1f1;
    transition: background-color 0.3s ease;
}

.recipient-item:hover {
    background-color: #f8f9fa;
}

.recipient-item:last-child {
    border-bottom: none;
}

.recipient-checkbox {
    margin-right: 0.75rem;
}

.recipient-info {
    flex-grow: 1;
}

.recipient-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.recipient-details {
    font-size: 0.8rem;
    color: #6c757d;
}

.recipient-phone {
    font-family: monospace;
    background: #e9ecef;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.communications.index') }}">Communications</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.communications.sms.index') }}">SMS</a></li>
                        <li class="breadcrumb-item active">Send New SMS</li>
                    </ol>
                </div>
                <h4 class="page-title">Send New SMS</h4>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="compose-card">
                <form id="smsForm">
                    <!-- Recipient Selection -->
                    <div class="recipient-section">
                        <h5 class="mb-3">Select Recipients</h5>
                        
                        <div class="recipient-type-tabs">
                            <ul class="nav nav-tabs" id="recipientTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="individual-tab" data-toggle="tab" href="#individual" role="tab">
                                        <i class="fas fa-user mr-1"></i> Individual
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="class-tab" data-toggle="tab" href="#class" role="tab">
                                        <i class="fas fa-users mr-1"></i> Class/Section
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bulk-tab" data-toggle="tab" href="#bulk" role="tab">
                                        <i class="fas fa-bullhorn mr-1"></i> Bulk
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="recipientTabContent">
                            <!-- Individual Selection -->
                            <div class="tab-pane fade show active" id="individual" role="tabpanel" style="display: block;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Recipient Type</label>
                                        <select class="form-control" name="recipient_type" id="individualRecipientType">
                                            <option value="">Select Type</option>
                                            <option value="student">Students</option>
                                            <option value="parent">Parents</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label>Search Recipients</label>
                                        <input type="text" class="form-control" id="recipientSearch" placeholder="Search by name, ID, or phone number...">
                                    </div>
                                </div>
                                <div class="recipient-list" id="recipientList">
                                    <div class="text-center py-4">
                                        <i class="fas fa-search fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">Select recipient type and search to find recipients</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Class/Section Selection -->
                            <div class="tab-pane fade" id="class" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Recipient Type</label>
                                        <select class="form-control" name="class_recipient_type" id="classRecipientType">
                                            <option value="">Select Type</option>
                                            <option value="class">By Class</option>
                                            <option value="section">By Section</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Class</label>
                                        <select class="form-control" name="class_id" id="classSelect">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label>Section</label>
                                        <select class="form-control" name="section_id" id="sectionSelect">
                                            <option value="">Select Section</option>
                                            @foreach($sections as $section)
                                            <option value="{{ $section->name }}">{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <div class="mt-2">
                                            <span class="recipient-count" id="classRecipientCount">0 recipients</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bulk Selection -->
                            <div class="tab-pane fade" id="bulk" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Recipient Type</label>
                                        <select class="form-control" name="bulk_recipient_type" id="bulkRecipientType">
                                            <option value="">Select Type</option>
                                            <option value="all">All Users</option>
                                            <option value="students">Students Only</option>
                                            <option value="parents">Parents Only</option>
                                            <option value="staff">Staff Only</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <div class="mt-2">
                                            <span class="recipient-count" id="bulkRecipientCount">0 recipients</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Composition -->
                    <div class="form-group">
                        <label>Message</label>
                        <div class="message-input-wrapper">
                            <textarea class="form-control" name="message" id="messageInput" rows="6" 
                                placeholder="Type your message here..." maxlength="1600" required></textarea>
                            <div class="character-count">
                                <span id="charCount">0</span>/1600 characters
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Standard SMS: 160 characters, Long SMS: 1600 characters
                        </small>
                    </div>

                    <!-- Template Selection -->
                    @if($templates->count() > 0)
                    <div class="template-section">
                        <h6 class="mb-3">Use Template</h6>
                        <div class="row">
                            @foreach($templates as $template)
                            <div class="col-md-6">
                                <div class="template-item" data-template-id="{{ $template->id }}" onclick="selectTemplate({{ $template->id }})">
                                    <h6 class="mb-2">{{ $template->name }}</h6>
                                    <p class="mb-2 text-muted">{{ Str::limit($template->content, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $template->category_badge }}">{{ ucfirst($template->category) }}</span>
                                        <small class="text-muted">{{ Str::limit($template->variables_list, 50) }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Message Settings -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Priority</label>
                                <select class="form-control" name="priority" required>
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="category" required>
                                    <option value="notification">Notification</option>
                                    <option value="reminder">Reminder</option>
                                    <option value="alert">Alert</option>
                                    <option value="marketing">Marketing</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gateway</label>
                                <select class="form-control" name="gateway_id">
                                    <option value="">Use Default Gateway</option>
                                    @foreach($gateways as $gateway)
                                    <option value="{{ $gateway->id }}">{{ $gateway->name }} ({{ $gateway->provider }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Schedule (Optional)</label>
                                <input type="datetime-local" class="form-control" name="scheduled_at" min="{{ now()->format('Y-m-d\TH:i') }}">
                                <small class="text-muted">Leave empty to send immediately</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="requiresConfirmation" name="requires_confirmation">
                                    <label class="custom-control-label" for="requiresConfirmation">
                                        Require Confirmation
                                    </label>
                                </div>
                                <small class="text-muted">Recipients must confirm receipt</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Expiry (Optional)</label>
                                <input type="datetime-local" class="form-control" name="expires_at" min="{{ now()->format('Y-m-d\TH:i') }}">
                                <small class="text-muted">Message expires after this time</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary btn-lg mr-3" onclick="saveAsDraft()">
                            <i class="fas fa-save mr-1"></i> Save as Draft
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane mr-1"></i> Send SMS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* Ensure tabs are visible and working */
.nav-tabs .nav-link {
    cursor: pointer;
    color: #495057;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

.tab-pane {
    display: none;
}

.tab-pane.show {
    display: block;
}

.tab-pane.active {
    display: block;
}
</style>
<script>
$(document).ready(function() {
    // Initialize Bootstrap tabs
    $('#recipientTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    // Debug: Log tab elements
    console.log('Tab elements found:', $('#recipientTabs a').length);
    console.log('Tab content elements found:', $('.tab-pane').length);
    
    // Test tab switching on page load
    setTimeout(function() {
        console.log('Testing tab switch...');
        $('#class-tab').click();
    }, 1000);
    
    // Ensure first tab is active on page load
    $('#individual-tab').addClass('active');
    $('#individual').show().addClass('show active');
    
    // Character count
    $('#messageInput').on('input', function() {
        const count = $(this).val().length;
        $('#charCount').text(count);
        
        if (count > 160) {
            $('#charCount').addClass('text-warning');
        } else {
            $('#charCount').removeClass('text-warning');
        }
    });

    // Recipient type change
    $('#individualRecipientType').change(function() {
        $('#recipientSearch').val('');
        $('#recipientList').html('<div class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2"></i><p class="text-muted">Search for recipients</p></div>');
    });

    // Recipient search
    let searchTimeout;
    $('#recipientSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        const type = $('#individualRecipientType').val();
        
        if (query.length < 2 || !type) return;
        
        searchTimeout = setTimeout(() => {
            searchRecipients(type, query);
        }, 300);
    });

    // Class/Section change
    $('#classSelect, #sectionSelect, #classRecipientType').change(function() {
        updateClassRecipientCount();
    });

    // Bulk recipient type change
    $('#bulkRecipientType').change(function() {
        updateBulkRecipientCount();
    });

    // Form submission
    $('#smsForm').submit(function(e) {
        e.preventDefault();
        sendSms();
    });
    
    // Manual tab switching for better compatibility
    $('.nav-link').on('click', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        
        console.log('Tab clicked:', target); // Debug log
        
        // Hide all tab content
        $('.tab-pane').hide().removeClass('show active');
        
        // Remove active class from all tabs
        $('.nav-link').removeClass('active');
        
        // Add active class to clicked tab
        $(this).addClass('active');
        
        // Show target content
        $(target).show().addClass('show active');
        
        console.log('Tab content shown:', target); // Debug log
    });
});

function searchRecipients(type, query) {
    $.ajax({
        url: '{{ route("admin.communications.sms.recipient-suggestions") }}',
        type: 'GET',
        data: { type: type, search: query },
        success: function(response) {
            displayRecipients(response);
        },
        error: function() {
            $('#recipientList').html('<div class="text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i><p class="text-danger">Failed to search recipients</p></div>');
        }
    });
}

function displayRecipients(recipients) {
    if (recipients.length === 0) {
        $('#recipientList').html('<div class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2"></i><p class="text-muted">No recipients found</p></div>');
        return;
    }

    let html = '';
    recipients.forEach(recipient => {
        html += `
            <div class="recipient-item">
                <div class="recipient-checkbox">
                    <input type="checkbox" class="form-check-input" name="recipient_ids[]" value="${recipient.id}" data-type="${recipient.type}">
                </div>
                <div class="recipient-info">
                    <div class="recipient-name">${recipient.text}</div>
                    <div class="recipient-details">
                        <span class="recipient-phone">${recipient.phone}</span>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#recipientList').html(html);
}

function selectTemplate(templateId) {
    $('.template-item').removeClass('selected');
    $(`.template-item[data-template-id="${templateId}"]`).addClass('selected');
    
    // Load template content
    $.ajax({
        url: `/admin/communications/sms/templates/${templateId}`,
        type: 'GET',
        success: function(response) {
            $('#messageInput').val(response.content);
            $('#charCount').text(response.content.length);
        }
    });
}

function updateClassRecipientCount() {
    const classRecipientType = $('#classRecipientType').val();
    const classId = $('#classSelect').val();
    const sectionId = $('#sectionSelect').val();
    
    if (!classRecipientType) {
        $('#classRecipientCount').text('0 recipients');
        return;
    }
    
    if (classRecipientType === 'class' && classId) {
        // Get student count for the selected class
        $.ajax({
            url: '{{ route("admin.communications.sms.recipient-suggestions") }}',
            type: 'GET',
            data: { type: 'class', search: classId },
            success: function(response) {
                const count = response.length;
                $('#classRecipientCount').text(`${count} recipients`);
            },
            error: function() {
                $('#classRecipientCount').text('Error loading count');
            }
        });
    } else if (classRecipientType === 'section' && sectionId) {
        // Get student count for the selected section
        $.ajax({
            url: '{{ route("admin.communications.sms.recipient-suggestions") }}',
            type: 'GET',
            data: { type: 'section', search: sectionId },
            success: function(response) {
                const count = response.length;
                $('#classRecipientCount').text(`${count} recipients`);
            },
            error: function() {
                $('#classRecipientCount').text('Error loading count');
            }
        });
    } else {
        $('#classRecipientCount').text('0 recipients');
    }
}

function updateBulkRecipientCount() {
    const type = $('#bulkRecipientType').val();
    const counts = @json($recipientCounts);
    
    if (type && counts[type + 's']) {
        $('#bulkRecipientCount').text(`${counts[type + 's']} recipients`);
    } else {
        $('#bulkRecipientCount').text('0 recipients');
    }
}

function sendSms() {
    const formData = new FormData($('#smsForm')[0]);
    
    // Validate recipients
    const recipients = getSelectedRecipients();
    if (recipients.length === 0) {
        toastr.error('Please select at least one recipient');
        return;
    }
    
    // Determine recipient type and IDs
    let recipientType = '';
    let recipientIds = [];
    
    if (recipients.length === 1) {
        recipientType = recipients[0].type;
        recipientIds = [recipients[0].id];
    } else {
        // Multiple recipients - determine the type
        const types = [...new Set(recipients.map(r => r.type))];
        if (types.length === 1) {
            recipientType = types[0];
            recipientIds = recipients.map(r => r.id);
        } else {
            toastr.error('Cannot mix different recipient types');
            return;
        }
    }
    
    // Add recipient data to form
    formData.append('recipient_type', recipientType);
    formData.append('recipient_ids', JSON.stringify(recipientIds));
    
    $.ajax({
        url: '{{ route("admin.communications.sms.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('SMS sent successfully');
                window.location.href = '{{ route("admin.communications.sms.index") }}';
            } else {
                toastr.error(response.message || 'Failed to send SMS');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.keys(response.errors).forEach(key => {
                    toastr.error(response.errors[key][0]);
                });
            } else {
                toastr.error('Failed to send SMS');
            }
        }
    });
}

function saveAsDraft() {
    const formData = new FormData($('#smsForm')[0]);
    formData.append('save_as_draft', '1');
    
    // Validate recipients
    const recipients = getSelectedRecipients();
    if (recipients.length === 0) {
        toastr.error('Please select at least one recipient');
        return;
    }
    
    // Determine recipient type and IDs
    let recipientType = '';
    let recipientIds = [];
    
    if (recipients.length === 1) {
        recipientType = recipients[0].type;
        recipientIds = [recipients[0].id];
    } else {
        // Multiple recipients - determine the type
        const types = [...new Set(recipients.map(r => r.type))];
        if (types.length === 1) {
            recipientType = types[0];
            recipientIds = recipients.map(r => r.id);
        } else {
            toastr.error('Cannot mix different recipient types');
            return;
        }
    }
    
    // Add recipient data to form
    formData.append('recipient_type', recipientType);
    formData.append('recipient_ids', JSON.stringify(recipientIds));
    
    $.ajax({
        url: '{{ route("admin.communications.sms.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('SMS saved as draft');
                window.location.href = '{{ route("admin.communications.sms.index") }}';
            } else {
                toastr.error(response.message || 'Failed to save draft');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.keys(response.errors).forEach(key => {
                    toastr.error(response.errors[key][0]);
                });
            } else {
                toastr.error('Failed to save draft');
            }
        }
    });
}

function getSelectedRecipients() {
    const recipients = [];
    
    // Get individual recipients
    $('input[name="recipient_ids[]"]:checked').each(function() {
        recipients.push({
            id: $(this).val(),
            type: $(this).data('type')
        });
    });
    
    // Get class/section recipients
    const classRecipientType = $('#classRecipientType').val();
    const classId = $('#classSelect').val();
    const sectionId = $('#sectionSelect').val();
    
    if (classRecipientType === 'class' && classId) {
        recipients.push({ type: 'class', id: classId });
    } else if (classRecipientType === 'section' && sectionId) {
        recipients.push({ type: 'section', id: sectionId });
    }
    
    // Get bulk recipients
    const bulkType = $('#bulkRecipientType').val();
    if (bulkType) {
        recipients.push({ type: bulkType, id: 'all' });
    }
    
    return recipients;
}
</script>
@endsection

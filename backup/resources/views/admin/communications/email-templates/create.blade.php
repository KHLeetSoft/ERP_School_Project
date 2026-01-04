@extends('admin.layout.app')

@section('title', 'Create Email Template')

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
.variables-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.variable-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.variable-item:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.15);
}
.variable-code {
    font-family: monospace;
    background: #e9ecef;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9rem;
}
.variable-description {
    color: #6c757d;
    font-size: 0.9rem;
}
.category-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
.preview-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.preview-content {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    min-height: 200px;
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.communications.email-templates.index') }}">Email Templates</a></li>
                        <li class="breadcrumb-item active">Create Template</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Email Template</h4>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="compose-card">
                <form id="templateForm">
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Template Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required 
                                       placeholder="Enter template name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category">Category <span class="text-danger">*</span></label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Email Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject" name="subject" required 
                               placeholder="Enter email subject">
                    </div>

                    <!-- Variables Section -->
                    <div class="variables-section">
                        <h6 class="mb-3">
                            <i class="fas fa-code mr-2"></i>Available Variables
                            <small class="text-muted">Click to insert into content</small>
                        </h6>
                        
                        @foreach($variables as $group => $groupVariables)
                        <div class="mb-3">
                            <h6 class="text-primary">{{ ucfirst($group) }} Variables</h6>
                            <div class="row">
                                @foreach($groupVariables as $code => $description)
                                <div class="col-md-6">
                                    <div class="variable-item" onclick="insertVariable('{{ $code }}')">
                                        <div>
                                            <div class="variable-code">{{ $code }}</div>
                                            <div class="variable-description">{{ $description }}</div>
                                        </div>
                                        <i class="fas fa-plus text-primary"></i>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Content Editor -->
                    <div class="form-group">
                        <label for="content">Email Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="12" required 
                                  placeholder="Enter email content. Use variables like {{student_name}} to personalize your message."></textarea>
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            You can use HTML tags for formatting. Variables will be automatically replaced when sending emails.
                        </small>
                    </div>

                    <!-- Template Settings -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                                    <label class="custom-control-label" for="is_active">
                                        Template is active
                                    </label>
                                </div>
                                <small class="text-muted">Active templates can be used for sending emails</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="variables">Custom Variables (Optional)</label>
                                <input type="text" class="form-control" id="variables" name="variables" 
                                       placeholder="variable1,variable2,variable3">
                                <small class="text-muted">Comma-separated list of custom variables</small>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="preview-section">
                        <h6 class="mb-3">
                            <i class="fas fa-eye mr-2"></i>Live Preview
                            <button type="button" class="btn btn-sm btn-outline-primary ml-2" onclick="updatePreview()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </h6>
                        <div class="preview-content" id="previewContent">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-eye fa-2x mb-2"></i>
                                <p>Preview will appear here as you type</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary btn-lg mr-3" onclick="saveAsDraft()">
                            <i class="fas fa-save mr-1"></i> Save as Draft
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save mr-1"></i> Create Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>
let editor;

$(document).ready(function() {
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'blockQuote',
                    'insertTable',
                    'undo',
                    'redo'
                ]
            },
            placeholder: 'Enter email content. Use variables like {{student_name}} to personalize your message.'
        })
        .then(newEditor => {
            editor = newEditor;
            
            // Update preview when content changes
            editor.model.document.on('change:data', () => {
                updatePreview();
            });
        })
        .catch(error => {
            console.error(error);
        });

    // Form submission
    $('#templateForm').submit(function(e) {
        e.preventDefault();
        createTemplate();
    });

    // Auto-save draft every 30 seconds
    setInterval(function() {
        if ($('#name').val() || $('#content').val()) {
            autoSaveDraft();
        }
    }, 30000);
});

function insertVariable(variable) {
    if (editor) {
        const insertPosition = editor.model.document.selection.getFirstPosition();
        editor.model.insertContent(editor.model.createText(variable), insertPosition);
    } else {
        const textarea = document.getElementById('content');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const before = text.substring(0, start);
        const after = text.substring(end);
        
        textarea.value = before + variable + after;
        textarea.selectionStart = textarea.selectionEnd = start + variable.length;
        textarea.focus();
    }
    
    updatePreview();
}

function updatePreview() {
    let content = '';
    
    if (editor) {
        content = editor.getData();
    } else {
        content = $('#content').val();
    }
    
    if (!content) {
        $('#previewContent').html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-eye fa-2x mb-2"></i>
                <p>Preview will appear here as you type</p>
            </div>
        `);
        return;
    }
    
    // Replace variables with sample data
    const sampleData = {
        '{{student_name}}': 'John Doe',
        '{{student_id}}': 'STU001',
        '{{student_class}}': 'Class 10',
        '{{student_section}}': 'A',
        '{{student_roll_no}}': '25',
        '{{parent_name}}': 'Jane Doe',
        '{{parent_phone}}': '+1234567890',
        '{{parent_email}}': 'jane.doe@example.com',
        '{{staff_name}}': 'Dr. Smith',
        '{{staff_designation}}': 'Class Teacher',
        '{{staff_department}}': 'Mathematics',
        '{{school_name}}': '{{ Auth::user()->school->name ?? "School Name" }}',
        '{{school_address}}': '123 School Street, City',
        '{{school_phone}}': '+1234567890',
        '{{school_email}}': 'info@school.com',
        '{{current_date}}': new Date().toLocaleDateString(),
        '{{current_time}}': new Date().toLocaleTimeString(),
        '{{admin_name}}': '{{ Auth::user()->name }}',
        '{{year}}': new Date().getFullYear()
    };
    
    let previewContent = content;
    Object.keys(sampleData).forEach(variable => {
        previewContent = previewContent.replace(new RegExp(variable, 'g'), sampleData[variable]);
    });
    
    $('#previewContent').html(previewContent);
}

function createTemplate() {
    const formData = new FormData($('#templateForm')[0]);
    
    // Get content from editor
    if (editor) {
        formData.set('content', editor.getData());
    }
    
    // Process custom variables
    const customVariables = $('#variables').val();
    if (customVariables) {
        const variables = customVariables.split(',').map(v => v.trim()).filter(v => v);
        formData.set('variables', JSON.stringify(variables));
    }
    
    $.ajax({
        url: '{{ route("admin.communications.email-templates.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Email template created successfully');
                window.location.href = '{{ route("admin.communications.email-templates.index") }}';
            } else {
                toastr.error(response.message || 'Failed to create template');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.keys(response.errors).forEach(key => {
                    toastr.error(response.errors[key][0]);
                });
            } else {
                toastr.error('Failed to create template');
            }
        }
    });
}

function saveAsDraft() {
    const formData = new FormData($('#templateForm')[0]);
    formData.append('is_active', '0'); // Save as inactive draft
    
    // Get content from editor
    if (editor) {
        formData.set('content', editor.getData());
    }
    
    // Process custom variables
    const customVariables = $('#variables').val();
    if (customVariables) {
        const variables = customVariables.split(',').map(v => v.trim()).filter(v => v);
        formData.set('variables', JSON.stringify(variables));
    }
    
    $.ajax({
        url: '{{ route("admin.communications.email-templates.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Template saved as draft');
                window.location.href = '{{ route("admin.communications.email-templates.index") }}';
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

function autoSaveDraft() {
    // Auto-save functionality for drafts
    const formData = new FormData($('#templateForm')[0]);
    formData.append('is_active', '0');
    formData.append('auto_save', '1');
    
    if (editor) {
        formData.set('content', editor.getData());
    }
    
    // Save to localStorage as backup
    const draftData = {
        name: $('#name').val(),
        subject: $('#subject').val(),
        content: editor ? editor.getData() : $('#content').val(),
        category: $('#category').val(),
        variables: $('#variables').val(),
        timestamp: new Date().toISOString()
    };
    
    localStorage.setItem('emailTemplateDraft', JSON.stringify(draftData));
}
</script>
@endpush

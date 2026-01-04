@extends('admin.layout.app')

@section('title', 'Create Notice')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-plus text-primary me-2"></i>
                    Create New Notice
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.noticeboard.index') }}">Noticeboard</a></li>
                    <li class="breadcrumb-item active">Create Notice</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.noticeboard.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Notice Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.communications.noticeboard.store') }}" method="POST" enctype="multipart/form-data" id="createNoticeForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" 
                                           placeholder="Enter notice title" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                    <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        @foreach(['draft', 'published', 'archived'] as $status)
                                            <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty if no end date</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="target_audience" class="form-label">Target Audience <span class="text-danger">*</span></label>
                                    <select class="form-select @error('target_audience') is-invalid @enderror" id="target_audience" name="target_audience" required>
                                        <option value="">Select Audience</option>
                                        @foreach($audiences as $audience)
                                            <option value="{{ $audience }}" {{ old('target_audience') == $audience ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $audience)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_audience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" 
                                      rows="8" placeholder="Enter notice content..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <select class="form-select" id="tags" name="tags[]" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple tags</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" 
                                   id="attachments" name="attachments[]" multiple 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                            <small class="form-text text-muted">
                                Allowed file types: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, GIF<br>
                                Maximum file size: 10MB per file
                            </small>
                            @error('attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning me-2"></i>Featured Notice
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_pinned">
                                        <i class="fas fa-thumbtack text-primary me-2"></i>Pin Notice
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_public">
                                        <i class="fas fa-globe text-info me-2"></i>Public Notice
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="expires_at" class="form-label">Expiry Date</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty if notice should not expire</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Notice
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="saveAsDraft()">
                                <i class="fas fa-edit me-2"></i>Save as Draft
                            </button>
                            <a href="{{ route('admin.communications.noticeboard.index') }}" class="btn btn-light">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Preview Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Live Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div id="noticePreview" class="notice-preview">
                        <div class="preview-placeholder">
                            <i class="fas fa-eye-slash fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Start typing to see a live preview of your notice</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Tips & Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Title:</strong> Keep it clear and concise
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Content:</strong> Use proper formatting and structure
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Priority:</strong> Set appropriate urgency level
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Tags:</strong> Add relevant tags for better organization
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Attachments:</strong> Include relevant documents
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .notice-preview {
        min-height: 200px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        background: #f8f9fa;
    }
    
    .preview-placeholder {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .preview-notice {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .preview-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }
    
    .preview-meta {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 15px;
    }
    
    .preview-content {
        color: #555;
        line-height: 1.6;
    }
    
    .preview-tags {
        margin-top: 15px;
    }
    
    .preview-tag {
        display: inline-block;
        background: #e9ecef;
        color: #495057;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    .form-actions {
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        margin-top: 20px;
    }
    
    .form-actions .btn {
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .form-check-label {
        cursor: pointer;
    }
    
    .form-check-input:checked + .form-check-label {
        font-weight: 600;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .then(editor => {
            // Update preview when content changes
            editor.model.document.on('change:data', () => {
                updatePreview();
            });
        })
        .catch(error => {
            console.error(error);
        });

    // Update preview function
    function updatePreview() {
        const title = document.getElementById('title').value;
        const content = document.getElementById('content').value;
        const type = document.getElementById('type').value;
        const priority = document.getElementById('priority').value;
        const tags = Array.from(document.getElementById('tags').selectedOptions).map(option => option.text);
        
        const previewDiv = document.getElementById('noticePreview');
        
        if (title || content) {
            const previewHtml = `
                <div class="preview-notice">
                    ${title ? `<div class="preview-title">${title}</div>` : ''}
                    ${type || priority ? `
                        <div class="preview-meta">
                            ${type ? `<span class="badge bg-primary me-2">${type}</span>` : ''}
                            ${priority ? `<span class="badge bg-${getPriorityColor(priority)}">${priority}</span>` : ''}
                        </div>
                    ` : ''}
                    ${content ? `<div class="preview-content">${content}</div>` : ''}
                    ${tags.length > 0 ? `
                        <div class="preview-tags">
                            ${tags.map(tag => `<span class="preview-tag">${tag}</span>`).join('')}
                        </div>
                    ` : ''}
                </div>
            `;
            previewDiv.innerHTML = previewHtml;
        } else {
            previewDiv.innerHTML = `
                <div class="preview-placeholder">
                    <i class="fas fa-eye-slash fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Start typing to see a live preview of your notice</p>
                </div>
            `;
        }
    }

    // Get priority color
    function getPriorityColor(priority) {
        const colors = {
            'low': 'success',
            'medium': 'warning',
            'high': 'danger',
            'urgent': 'dark'
        };
        return colors[priority] || 'secondary';
    }

    // Update preview on input changes
    document.getElementById('title').addEventListener('input', updatePreview);
    document.getElementById('type').addEventListener('change', updatePreview);
    document.getElementById('priority').addEventListener('change', updatePreview);
    document.getElementById('tags').addEventListener('change', updatePreview);

    // Save as draft function
    function saveAsDraft() {
        document.getElementById('status').value = 'draft';
        document.getElementById('createNoticeForm').submit();
    }

    // Form validation
    document.getElementById('createNoticeForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        const type = document.getElementById('type').value;
        const priority = document.getElementById('priority').value;
        const status = document.getElementById('status').value;
        const startDate = document.getElementById('start_date').value;
        const targetAudience = document.getElementById('target_audience').value;
        
        if (!title || !content || !type || !priority || !status || !startDate || !targetAudience) {
            e.preventDefault();
            showNotification('Please fill in all required fields', 'warning');
            return false;
        }
        
        // Validate end date if provided
        const endDate = document.getElementById('end_date').value;
        if (endDate && new Date(endDate) <= new Date(startDate)) {
            e.preventDefault();
            showNotification('End date must be after start date', 'warning');
            return false;
        }
        
        return true;
    });

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = `
            <div class="notification notification-${type}" style="
                position: fixed; top: 20px; right: 20px; 
                background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : type === 'warning' ? '#ffc107' : '#17a2b8'}; 
                color: white; padding: 15px 20px; border-radius: 8px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999;
                animation: slideIn 0.3s ease-out;
            ">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; margin-left: 15px;">×</button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', notification);
        setTimeout(() => {
            const notif = document.querySelector('.notification');
            if (notif) notif.remove();
        }, 5000);
    }

    // Initialize preview
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
    });
</script>
@endsection

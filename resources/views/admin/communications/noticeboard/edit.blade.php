@extends('admin.layout.app')

@section('title', 'Edit Notice')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-edit text-primary me-2"></i>
                    Edit Notice: {{ $noticeboard->title }}
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.noticeboard.index') }}">Noticeboard</a></li>
                    <li class="breadcrumb-item active">Edit Notice</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.noticeboard.show', $noticeboard->id) }}" class="btn btn-info me-2">
                    <i class="fas fa-eye me-2"></i>View Notice
                </a>
                <a href="{{ route('admin.communications.noticeboard.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Notice Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.communications.noticeboard.update', $noticeboard->id) }}" method="POST" enctype="multipart/form-data" id="editNoticeForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $noticeboard->title) }}" 
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
                                            <option value="{{ $type }}" {{ old('type', $noticeboard->type) == $type ? 'selected' : '' }}>
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
                                            <option value="{{ $priority }}" {{ old('priority', $noticeboard->priority) == $priority ? 'selected' : '' }}>
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
                                            <option value="{{ $status }}" {{ old('status', $noticeboard->status) == $status ? 'selected' : '' }}>
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
                                           id="start_date" name="start_date" 
                                           value="{{ old('start_date', $noticeboard->start_date ? $noticeboard->start_date->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" 
                                           value="{{ old('end_date', $noticeboard->end_date ? $noticeboard->end_date->format('Y-m-d\TH:i') : '') }}">
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
                                            <option value="{{ $dept->id }}" {{ old('department_id', $noticeboard->department_id) == $dept->id ? 'selected' : '' }}>
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
                                            <option value="{{ $audience }}" {{ old('target_audience', $noticeboard->target_audience) == $audience ? 'selected' : '' }}>
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
                                      rows="8" placeholder="Enter notice content..." required>{{ old('content', $noticeboard->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <select class="form-select" id="tags" name="tags[]" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $noticeboard->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple tags</small>
                        </div>

                        <!-- Current Attachments -->
                        @if($noticeboard->attachments && count($noticeboard->attachments) > 0)
                        <div class="form-group mb-3">
                            <label class="form-label">Current Attachments</label>
                            <div class="current-attachments">
                                @foreach($noticeboard->attachments as $attachment)
                                <div class="attachment-item d-flex align-items-center p-2 border rounded mb-2">
                                    <i class="fas fa-paperclip text-primary me-2"></i>
                                    <span class="flex-grow-1">{{ $attachment->original_name }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttachment({{ $attachment->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="attachments" class="form-label">Add New Attachments</label>
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
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                           {{ old('is_featured', $noticeboard->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning me-2"></i>Featured Notice
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned" value="1" 
                                           {{ old('is_pinned', $noticeboard->is_pinned) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_pinned">
                                        <i class="fas fa-thumbtack text-primary me-2"></i>Pin Notice
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" 
                                           {{ old('is_public', $noticeboard->is_public) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_public">
                                        <i class="fas fa-globe text-info me-2"></i>Public Notice
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="expires_at" class="form-label">Expiry Date</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" 
                                   value="{{ old('expires_at', $noticeboard->expires_at ? $noticeboard->expires_at->format('Y-m-d\TH:i') : '') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty if notice should not expire</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Notice
                            </button>
                            <button type="button" class="btn btn-warning" onclick="saveAsDraft()">
                                <i class="fas fa-edit me-2"></i>Save as Draft
                            </button>
                            <a href="{{ route('admin.communications.noticeboard.show', $noticeboard->id) }}" class="btn btn-info">
                                <i class="fas fa-eye me-2"></i>View Notice
                            </a>
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

            <!-- Notice Stats Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Notice Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-primary mb-1">{{ $noticeboard->views_count ?? 0 }}</h4>
                                <small class="text-muted">Views</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-success mb-1">{{ $noticeboard->likes_count ?? 0 }}</h4>
                                <small class="text-muted">Likes</small>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-info mb-1">{{ $noticeboard->comments_count ?? 0 }}</h4>
                                <small class="text-muted">Comments</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-warning mb-1">{{ $noticeboard->shares_count ?? 0 }}</h4>
                                <small class="text-muted">Shares</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize live preview
    updatePreview();
    
    // Update preview on input changes
    $('#title, #content').on('input', function() {
        updatePreview();
    });
    
    // Update preview on select changes
    $('#type, #priority, #status, #target_audience').on('change', function() {
        updatePreview();
    });
});

function updatePreview() {
    const title = $('#title').val() || 'Notice Title';
    const content = $('#content').val() || 'Notice content will appear here...';
    const type = $('#type').val() || 'general';
    const priority = $('#priority').val() || 'normal';
    const status = $('#status').val() || 'draft';
    const audience = $('#target_audience').val() || 'all';
    
    const preview = `
        <div class="notice-preview-content">
            <div class="notice-header mb-3">
                <h4 class="notice-title">${title}</h4>
                <div class="notice-meta">
                    <span class="badge bg-${getPriorityColor(priority)} me-2">${priority.toUpperCase()}</span>
                    <span class="badge bg-${getTypeColor(type)} me-2">${type.toUpperCase()}</span>
                    <span class="badge bg-${getStatusColor(status)} me-2">${status.toUpperCase()}</span>
                    <span class="badge bg-info">${audience.replace('_', ' ').toUpperCase()}</span>
                </div>
            </div>
            <div class="notice-body">
                ${content.replace(/\n/g, '<br>')}
            </div>
        </div>
    `;
    
    $('#noticePreview').html(preview);
}

function getPriorityColor(priority) {
    const colors = {
        'low': 'success',
        'normal': 'info',
        'high': 'warning',
        'urgent': 'danger'
    };
    return colors[priority] || 'info';
}

function getTypeColor(type) {
    const colors = {
        'general': 'secondary',
        'announcement': 'primary',
        'event': 'success',
        'reminder': 'warning',
        'alert': 'danger'
    };
    return colors[type] || 'secondary';
}

function getStatusColor(status) {
    const colors = {
        'draft': 'secondary',
        'published': 'success',
        'archived': 'dark'
    };
    return colors[status] || 'secondary';
}

function saveAsDraft() {
    $('#status').val('draft');
    $('#editNoticeForm').submit();
}

function removeAttachment(attachmentId) {
    if (confirm('Are you sure you want to remove this attachment?')) {
        // Add a hidden input to mark attachment for removal
        const input = $('<input>').attr({
            type: 'hidden',
            name: 'remove_attachments[]',
            value: attachmentId
        });
        $('#editNoticeForm').append(input);
        
        // Remove the attachment item from UI
        $(`.attachment-item:has(button[onclick="removeAttachment(${attachmentId})"])`).remove();
    }
}
</script>
@endpush

@push('styles')
<style>
.notice-preview {
    min-height: 200px;
}

.preview-placeholder {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.notice-preview-content {
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background: #f8f9fa;
}

.notice-title {
    color: #495057;
    margin-bottom: 0.5rem;
}

.notice-meta {
    margin-bottom: 1rem;
}

.notice-body {
    color: #6c757d;
    line-height: 1.6;
}

.current-attachments {
    max-height: 200px;
    overflow-y: auto;
}

.attachment-item {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.attachment-item:hover {
    background: #e9ecef;
}

.stat-item {
    padding: 0.5rem;
}

.stat-item h4 {
    font-weight: 600;
    margin-bottom: 0.25rem;
}
</style>
@endpush

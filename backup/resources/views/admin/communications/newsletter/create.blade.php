@extends('admin.layout.app')

@section('title', 'Create Newsletter')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-plus text-primary me-2"></i>
                    Create New Newsletter
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.newsletter.index') }}">Newsletters</a></li>
                    <li class="breadcrumb-item active">Create Newsletter</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.newsletter.index') }}" class="btn btn-secondary">
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
                        <i class="fas fa-edit me-2"></i>Newsletter Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.communications.newsletter.store') }}" method="POST" id="createNewsletterForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" 
                                           placeholder="Enter newsletter title" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
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
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject" class="form-label">Subject Line <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject') }}" 
                                   placeholder="Enter email subject line" required>
                            <small class="form-text text-muted">
                                <span id="subjectCharCount">0</span>/255 characters. Keep it engaging and under 50 characters for best results.
                            </small>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="template_id" class="form-label">Template</label>
                            <select class="form-select @error('template_id') is-invalid @enderror" id="template_id" name="template_id">
                                <option value="">No Template (Custom Content)</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }} - {{ ucfirst($template->category) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Select a template to use as a starting point</small>
                            @error('template_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" 
                                      rows="12" placeholder="Enter newsletter content..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <select class="form-select" id="tags" name="tags[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag }}" {{ in_array($tag, old('tags', [])) ? 'selected' : '' }}>
                                                {{ ucfirst($tag) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple tags</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="scheduled_at" class="form-label">Schedule Date & Time</label>
                                    <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                           id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                                    <small class="form-text text-muted">Leave empty to save as draft</small>
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_draft" name="is_draft" value="1" 
                                           {{ old('is_draft', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_draft">
                                        <i class="fas fa-edit text-secondary me-2"></i>Save as Draft
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning me-2"></i>Featured Newsletter
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Newsletter
                            </button>
                            <button type="button" class="btn btn-warning" onclick="saveAsDraft()">
                                <i class="fas fa-edit me-2"></i>Save as Draft
                            </button>
                            <a href="{{ route('admin.communications.newsletter.index') }}" class="btn btn-light">
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
                    <div id="newsletterPreview" class="newsletter-preview">
                        <div class="preview-placeholder">
                            <i class="fas fa-eye-slash fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Start typing to see a live preview of your newsletter</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Preview -->
            <div class="card mt-3" id="templatePreviewCard" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-code me-2"></i>Template Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div id="templatePreview" class="template-preview">
                        <!-- Template preview will be loaded here -->
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
                            <strong>Subject:</strong> Keep it under 50 characters
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Content:</strong> Use clear, engaging language
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Preview:</strong> Always preview before sending
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Testing:</strong> Test with a small group first
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Character Count -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Content Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-primary mb-1" id="wordCount">0</h4>
                                <small class="text-muted">Words</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-success mb-1" id="charCount">0</h4>
                                <small class="text-muted">Characters</small>
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
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>
let editor;

$(document).ready(function() {
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
            placeholder: 'Start writing your newsletter content...'
        })
        .then(newEditor => {
            editor = newEditor;
            
            // Update preview and stats when content changes
            editor.model.document.on('change:data', () => {
                updatePreview();
                updateStats();
            });
        })
        .catch(error => {
            console.error(error);
        });

    // Update preview when title or subject changes
    $('#title, #subject').on('input', function() {
        updatePreview();
    });

    // Update subject character count
    $('#subject').on('input', function() {
        const count = $(this).val().length;
        $('#subjectCharCount').text(count);
        
        if (count > 50) {
            $('#subjectCharCount').addClass('text-warning');
        } else {
            $('#subjectCharCount').removeClass('text-warning');
        }
    });

    // Template selection change
    $('#template_id').change(function() {
        const templateId = $(this).val();
        if (templateId) {
            loadTemplatePreview(templateId);
        } else {
            $('#templatePreviewCard').hide();
        }
    });

    // Initialize preview
    updatePreview();
    updateStats();
});

function updatePreview() {
    const title = $('#title').val() || 'Newsletter Title';
    const subject = $('#subject').val() || 'Newsletter Subject';
    const content = editor ? editor.getData() : '';
    
    if (content.trim()) {
        const preview = `
            <div class="newsletter-preview-content">
                <div class="preview-header mb-3">
                    <h4 class="preview-title">${title}</h4>
                    <p class="preview-subject text-muted">${subject}</p>
                </div>
                <div class="preview-body">
                    ${content}
                </div>
            </div>
        `;
        $('#newsletterPreview').html(preview);
    } else {
        $('#newsletterPreview').html(`
            <div class="preview-placeholder">
                <i class="fas fa-eye-slash fa-2x text-muted mb-3"></i>
                <p class="text-muted">Start typing to see a live preview of your newsletter</p>
            </div>
        `);
    }
}

function updateStats() {
    const content = editor ? editor.getData() : '';
    const textContent = content.replace(/<[^>]*>/g, ''); // Remove HTML tags
    
    const wordCount = textContent.trim() ? textContent.trim().split(/\s+/).length : 0;
    const charCount = textContent.length;
    
    $('#wordCount').text(wordCount);
    $('#charCount').text(charCount);
}

function loadTemplatePreview(templateId) {
    // TODO: Implement template preview loading
    $('#templatePreviewCard').show();
    $('#templatePreview').html(`
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
            <p class="text-muted">Loading template preview...</p>
        </div>
    `);
}

function saveAsDraft() {
    $('#is_draft').prop('checked', true);
    $('#createNewsletterForm').submit();
}

// Form validation
$('#createNewsletterForm').submit(function(e) {
    const title = $('#title').val().trim();
    const subject = $('#subject').val().trim();
    const content = editor ? editor.getData().trim() : '';
    
    if (!title) {
        e.preventDefault();
        toastr.error('Please enter a newsletter title');
        $('#title').focus();
        return false;
    }
    
    if (!subject) {
        e.preventDefault();
        toastr.error('Please enter a subject line');
        $('#subject').focus();
        return false;
    }
    
    if (!content) {
        e.preventDefault();
        toastr.error('Please enter newsletter content');
        editor.focus();
        return false;
    }
    
    if (subject.length > 255) {
        e.preventDefault();
        toastr.error('Subject line is too long (max 255 characters)');
        $('#subject').focus();
        return false;
    }
    
    return true;
});
</script>
@endpush

@push('styles')
<style>
.newsletter-preview {
    min-height: 300px;
}

.preview-placeholder {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.newsletter-preview-content {
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background: #f8f9fa;
}

.preview-title {
    color: #495057;
    margin-bottom: 0.5rem;
}

.preview-subject {
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.preview-body {
    color: #6c757d;
    line-height: 1.6;
}

.template-preview {
    min-height: 200px;
}

.stat-item {
    padding: 0.5rem;
}

.stat-item h4 {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

#subjectCharCount.text-warning {
    color: #ffc107 !important;
    font-weight: bold;
}

.ck-editor__editable {
    min-height: 300px;
}

.ck-editor__editable:focus {
    box-shadow: none;
    border-color: #80bdff;
}

.form-actions {
    padding-top: 1rem;
    border-top: 1px solid #dee2e6;
}

.form-actions .btn {
    margin-right: 0.5rem;
}
</style>
@endpush

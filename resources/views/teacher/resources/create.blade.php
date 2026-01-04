@extends('teacher.layout.app')

@section('title', 'Create New Resource')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Create New Resource
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('teacher.resources.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Resources
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('teacher.resources.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Basic Information -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Basic Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="title">Title <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                           id="title" name="title" value="{{ old('title') }}" required>
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('category_id') is-invalid @enderror" 
                                                            id="category_id" name="category_id" required>
                                                        <option value="">Select Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="type">Type <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('type') is-invalid @enderror" 
                                                            id="type" name="type" required>
                                                        <option value="">Select Type</option>
                                                        <option value="file" {{ old('type') == 'file' ? 'selected' : '' }}>File</option>
                                                        <option value="link" {{ old('type') == 'link' ? 'selected' : '' }}>Link</option>
                                                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                                                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                                                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                                                        <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document</option>
                                                        <option value="presentation" {{ old('type') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                                        <option value="worksheet" {{ old('type') == 'worksheet' ? 'selected' : '' }}>Worksheet</option>
                                                        <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="3" 
                                                      placeholder="Brief description of the resource">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Section -->
                                <div class="card" id="content-section">
                                    <div class="card-header">
                                        <h5 class="card-title">Content</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- File Upload -->
                                        <div id="file-upload-section" style="display: none;">
                                            <div class="form-group">
                                                <label for="file">Upload File</label>
                                                <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                       id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov">
                                                <small class="form-text text-muted">Maximum file size: 10MB</small>
                                                @error('file')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- External URL -->
                                        <div id="url-section" style="display: none;">
                                            <div class="form-group">
                                                <label for="external_url">External URL</label>
                                                <input type="url" class="form-control @error('external_url') is-invalid @enderror" 
                                                       id="external_url" name="external_url" 
                                                       placeholder="https://example.com" value="{{ old('external_url') }}">
                                                @error('external_url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Text Content -->
                                        <div id="text-content-section" style="display: none;">
                                            <div class="form-group">
                                                <label for="content">Content</label>
                                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                                          id="content" name="content" rows="10" 
                                                          placeholder="Enter your content here...">{{ old('content') }}</textarea>
                                                @error('content')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Settings -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="visibility">Visibility <span class="text-danger">*</span></label>
                                            <select class="form-control @error('visibility') is-invalid @enderror" 
                                                    id="visibility" name="visibility" required>
                                                <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                                                <option value="shared" {{ old('visibility') == 'shared' ? 'selected' : '' }}>Shared</option>
                                                <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                                            </select>
                                            <small class="form-text text-muted">
                                                <strong>Private:</strong> Only you can see this resource<br>
                                                <strong>Shared:</strong> Other teachers in your school can see this resource<br>
                                                <strong>Public:</strong> Everyone can see this resource
                                            </small>
                                            @error('visibility')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" name="status" required>
                                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Featured Resource
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="is_pinned" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_pinned">
                                                Pin to Top
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div class="card" id="preview-section" style="display: none;">
                                    <div class="card-header">
                                        <h5 class="card-title">Preview</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="preview-content">
                                            <!-- Preview content will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Resource
                                    </button>
                                    <a href="{{ route('teacher.resources.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide content sections based on type
    function toggleContentSections() {
        const type = $('#type').val();
        
        // Hide all sections
        $('#file-upload-section, #url-section, #text-content-section, #preview-section').hide();
        
        // Show relevant section based on type
        switch(type) {
            case 'file':
            case 'video':
            case 'image':
            case 'document':
            case 'presentation':
            case 'worksheet':
            case 'quiz':
                $('#file-upload-section').show();
                break;
            case 'link':
                $('#url-section').show();
                break;
            case 'text':
                $('#text-content-section').show();
                break;
        }
        
        // Show preview for certain types
        if (['text', 'link'].includes(type)) {
            $('#preview-section').show();
            updatePreview();
        }
    }

    // Update preview
    function updatePreview() {
        const type = $('#type').val();
        const title = $('#title').val() || 'Resource Title';
        const description = $('#description').val() || 'Resource description...';
        
        let previewHtml = `
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">${title}</h6>
                    <p class="card-text text-muted">${description}</p>
        `;
        
        if (type === 'link') {
            const url = $('#external_url').val() || 'https://example.com';
            previewHtml += `<a href="${url}" class="btn btn-primary btn-sm" target="_blank">Visit Link</a>`;
        } else if (type === 'text') {
            const content = $('#content').val() || 'Content will appear here...';
            previewHtml += `<div class="mt-2">${content.replace(/\n/g, '<br>')}</div>`;
        }
        
        previewHtml += '</div></div>';
        $('#preview-content').html(previewHtml);
    }

    // Event listeners
    $('#type').on('change', toggleContentSections);
    $('#title, #description, #external_url, #content').on('input', updatePreview);
    
    // Initial setup
    toggleContentSections();
});
</script>
@endpush

@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bx bx-file-pdf me-2 text-primary"></i>Create Result Publication</h4>
        <a href="{{ route('admin.result-announcement.publications.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Back to List
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.result-announcement.publications.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h5 class="mb-3">Basic Information</h5>
                            
                            <div class="mb-3">
                                <label for="result_announcement_id" class="form-label">Result Announcement *</label>
                                <select class="form-select @error('result_announcement_id') is-invalid @enderror" 
                                        id="result_announcement_id" name="result_announcement_id" required>
                                    <option value="">Select Announcement</option>
                                    @foreach($announcements as $announcement)
                                        <option value="{{ $announcement->id }}" {{ old('result_announcement_id') == $announcement->id ? 'selected' : '' }}>
                                            {{ $announcement->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('result_announcement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="publication_title" class="form-label">Publication Title *</label>
                                <input type="text" class="form-control @error('publication_title') is-invalid @enderror" 
                                       id="publication_title" name="publication_title" value="{{ old('publication_title') }}" required>
                                @error('publication_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="publication_content" class="form-label">Publication Content</label>
                                <textarea class="form-control @error('publication_content') is-invalid @enderror" 
                                          id="publication_content" name="publication_content" rows="6">{{ old('publication_content') }}</textarea>
                                @error('publication_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Additional content or description for the publication</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="publication_type" class="form-label">Publication Type *</label>
                                        <select class="form-select @error('publication_type') is-invalid @enderror" 
                                                id="publication_type" name="publication_type" required>
                                            <option value="">Select Type</option>
                                            <option value="merit_list" {{ old('publication_type') == 'merit_list' ? 'selected' : '' }}>Merit List</option>
                                            <option value="rank_card" {{ old('publication_type') == 'rank_card' ? 'selected' : '' }}>Rank Card</option>
                                            <option value="grade_sheet" {{ old('publication_type') == 'grade_sheet' ? 'selected' : '' }}>Grade Sheet</option>
                                            <option value="performance_report" {{ old('publication_type') == 'performance_report' ? 'selected' : '' }}>Performance Report</option>
                                            <option value="certificate" {{ old('publication_type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                        </select>
                                        @error('publication_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Template Settings -->
                        <div class="mb-4">
                            <h5 class="mb-3">Template Settings</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="template_theme" class="form-label">Template Theme</label>
                                        <select class="form-select" id="template_theme" name="template_settings[theme]">
                                            <option value="default">Default</option>
                                            <option value="modern">Modern</option>
                                            <option value="classic">Classic</option>
                                            <option value="elegant">Elegant</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="template_color" class="form-label">Primary Color</label>
                                        <input type="color" class="form-control form-control-color" 
                                               id="template_color" name="template_settings[primary_color]" value="#007bff">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="template_logo" class="form-label">Include School Logo</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="template_settings[include_logo]" 
                                           value="1" id="template_logo" checked>
                                    <label class="form-check-label" for="template_logo">Show school logo in publication</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Publishing Options -->
                        <div class="mb-4">
                            <h5 class="mb-3">Publishing Options</h5>
                            
                            <div class="mb-3">
                                <label for="published_at" class="form-label">Publish Date & Time</label>
                                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                       id="published_at" name="published_at" value="{{ old('published_at') }}">
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to publish immediately</small>
                            </div>

                            <div class="mb-3">
                                <label for="expires_at" class="form-label">Expiry Date & Time</label>
                                <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                       id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty for no expiry</small>
                            </div>
                        </div>

                        <!-- Access Control -->
                        <div class="mb-4">
                            <h5 class="mb-3">Access Control</h5>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" 
                                       value="1" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Publication</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="allow_download" 
                                       value="1" id="allow_download" {{ old('allow_download', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_download">Allow Download</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="require_authentication" 
                                       value="1" id="require_authentication" {{ old('require_authentication', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="require_authentication">Require Authentication</label>
                            </div>
                        </div>

                        <!-- Access Permissions -->
                        <div class="mb-4">
                            <h5 class="mb-3">Access Permissions</h5>
                            
                            <div class="mb-2">
                                <label class="form-label">Who can access this publication?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="access_permissions[]" 
                                           value="students" id="perm_students" checked>
                                    <label class="form-check-label" for="perm_students">Students</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="access_permissions[]" 
                                           value="parents" id="perm_parents" checked>
                                    <label class="form-check-label" for="perm_parents">Parents</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="access_permissions[]" 
                                           value="teachers" id="perm_teachers" checked>
                                    <label class="form-check-label" for="perm_teachers">Teachers</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="access_permissions[]" 
                                           value="admin" id="perm_admin" checked>
                                    <label class="form-check-label" for="perm_admin">Administrators</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Create Publication
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

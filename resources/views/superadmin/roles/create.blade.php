@extends('superadmin.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Create New Role</h4>
        <a href="{{ route('superadmin.roles.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Roles
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('superadmin.roles.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Role Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Enter role name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Enter role description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Role
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Inactive roles cannot be assigned to users
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Role Preview</h5>
                        </div>
                        <div class="card-body">
                            <div class="role-preview">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user-shield text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0" id="previewName">Role Name</h6>
                                        <small class="text-muted" id="previewSlug">role-slug</small>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="badge bg-success" id="previewStatus">Active</span>
                                </div>
                                
                                <p class="text-muted mb-0" id="previewDescription">
                                    Role description will appear here
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Role
                                </button>
                                <a href="{{ route('superadmin.roles.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Real-time preview
    function updatePreview() {
        const name = $('#name').val() || 'Role Name';
        const description = $('#description').val() || 'Role description will appear here';
        const isActive = $('#is_active').is(':checked');
        
        // Generate slug
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        
        $('#previewName').text(name);
        $('#previewSlug').text(slug);
        $('#previewDescription').text(description);
        
        if (isActive) {
            $('#previewStatus').removeClass('bg-danger').addClass('bg-success').text('Active');
        } else {
            $('#previewStatus').removeClass('bg-success').addClass('bg-danger').text('Inactive');
        }
    }

    // Update preview on input change
    $('#name, #description, #is_active').on('input change', updatePreview);

    // Form validation
    $('form').on('submit', function(e) {
        const name = $('#name').val().trim();
        
        if (!name) {
            e.preventDefault();
            showAlert('error', 'Role name is required!');
            $('#name').focus();
            return false;
        }
        
        if (name.length < 2) {
            e.preventDefault();
            showAlert('error', 'Role name must be at least 2 characters long!');
            $('#name').focus();
            return false;
        }
    });

    function showAlert(type, message) {
        let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        let alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('.card-body').prepend(alertHtml);
        setTimeout(() => $('.alert').fadeOut(), 5000);
    }

    // Initialize preview
    updatePreview();
});
</script>
@endsection

@extends('superadmin.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Create New Permission</h4>
        <a href="{{ route('superadmin.permissions.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Permissions
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('superadmin.permissions.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Permission Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="module_name" class="form-label">Module <span class="text-danger">*</span></label>
                                        <select class="form-control @error('module_name') is-invalid @enderror" 
                                                id="module_name" name="module_name" required>
                                            <option value="">Select Module</option>
                                            @foreach($modules as $key => $value)
                                                <option value="{{ $key }}" {{ old('module_name') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('module_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="action_name" class="form-label">Action <span class="text-danger">*</span></label>
                                        <select class="form-control @error('action_name') is-invalid @enderror" 
                                                id="action_name" name="action_name" required>
                                            <option value="">Select Action</option>
                                            @foreach($actions as $key => $value)
                                                <option value="{{ $key }}" {{ old('action_name') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('action_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Enter permission description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Permission Preview</h5>
                        </div>
                        <div class="card-body">
                            <div class="permission-preview">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-key text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0" id="previewName">Module - Action</h6>
                                        <small class="text-muted" id="previewKey">module.action</small>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="badge bg-info" id="previewModule">Module</span>
                                    <span class="badge bg-warning ms-1" id="previewAction">Action</span>
                                </div>
                                
                                <p class="text-muted mb-0" id="previewDescription">
                                    Permission description will appear here
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
                                    <i class="fas fa-save"></i> Create Permission
                                </button>
                                <a href="{{ route('superadmin.permissions.index') }}" class="btn btn-outline-secondary">
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
        const module = $('#module_name').val() || 'module';
        const action = $('#action_name').val() || 'action';
        const description = $('#description').val() || 'Permission description will appear here';
        
        const permissionKey = module + '.' + action;
        const displayName = module.charAt(0).toUpperCase() + module.slice(1) + ' - ' + 
                           action.charAt(0).toUpperCase() + action.slice(1);
        
        $('#previewName').text(displayName);
        $('#previewKey').text(permissionKey);
        $('#previewModule').text(module.charAt(0).toUpperCase() + module.slice(1));
        $('#previewAction').text(action.charAt(0).toUpperCase() + action.slice(1));
        $('#previewDescription').text(description);
    }

    // Update preview on input change
    $('#module_name, #action_name, #description').on('input change', updatePreview);

    // Form validation
    $('form').on('submit', function(e) {
        const module = $('#module_name').val().trim();
        const action = $('#action_name').val().trim();
        
        if (!module) {
            e.preventDefault();
            showAlert('error', 'Please select a module!');
            $('#module_name').focus();
            return false;
        }
        
        if (!action) {
            e.preventDefault();
            showAlert('error', 'Please select an action!');
            $('#action_name').focus();
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

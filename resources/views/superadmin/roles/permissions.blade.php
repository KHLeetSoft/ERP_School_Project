@extends('superadmin.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Manage Permissions - {{ $role->name }}</h4>
        <a href="{{ route('superadmin.roles.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Roles
        </a>
    </div>
    <div class="card-body">
        <!-- Role Info -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-user-shield text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $role->name }}</h5>
                                <p class="text-muted mb-0">{{ $role->description ?: 'No description provided' }}</p>
                                <small class="text-muted">
                                    <span class="badge bg-{{ $role->is_active ? 'success' : 'danger' }}">
                                        {{ $role->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($role->is_system)
                                        <span class="badge bg-info ms-1">System Role</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0" id="selectedCount">0</h3>
                        <small>Permissions Selected</small>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('superadmin.roles.update-permissions', $role->id) }}" method="POST" id="permissionsForm">
            @csrf
            
            <!-- Module Permissions -->
            <div class="row">
                @foreach($modules as $module)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0 d-flex align-items-center">
                                <i class="fas fa-{{ getModuleIcon($module) }} me-2"></i>
                                {{ ucfirst($module) }}
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(isset($permissions[$module]))
                                @foreach($permissions[$module] as $permission)
                                <div class="form-check mb-2">
                                    <input class="form-check-input permission-checkbox" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           id="permission_{{ $permission->id }}"
                                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                        {{ ucfirst($permission->action_name) }}
                                        <small class="text-muted d-block">{{ $permission->description }}</small>
                                    </label>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No permissions available for this module</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="button" class="btn btn-outline-primary" id="selectAll">
                                        <i class="fas fa-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="deselectAll">
                                        <i class="fas fa-square"></i> Deselect All
                                    </button>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save Permissions
                                    </button>
                                    <a href="{{ route('superadmin.roles.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
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
    // Update selected count
    function updateSelectedCount() {
        const selectedCount = $('.permission-checkbox:checked').length;
        $('#selectedCount').text(selectedCount);
    }

    // Select all permissions
    $('#selectAll').on('click', function() {
        $('.permission-checkbox').prop('checked', true);
        updateSelectedCount();
    });

    // Deselect all permissions
    $('#deselectAll').on('click', function() {
        $('.permission-checkbox').prop('checked', false);
        updateSelectedCount();
    });

    // Update count on checkbox change
    $('.permission-checkbox').on('change', updateSelectedCount);

    // Form submission
    $('#permissionsForm').on('submit', function(e) {
        e.preventDefault();
        
        const selectedPermissions = $('.permission-checkbox:checked').length;
        
        if (selectedPermissions === 0) {
            showAlert('warning', 'Please select at least one permission!');
            return;
        }

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        // Submit form
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                showAlert('success', 'Permissions updated successfully!');
                submitBtn.html(originalText).prop('disabled', false);
            },
            error: function(xhr) {
                let message = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', message);
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    function showAlert(type, message) {
        let alertClass = type === 'success' ? 'alert-success' : 
                        type === 'warning' ? 'alert-warning' : 'alert-danger';
        let alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('.card-body').prepend(alertHtml);
        setTimeout(() => $('.alert').fadeOut(), 5000);
    }

    // Initialize count
    updateSelectedCount();
});
</script>
@endsection

@php
function getModuleIcon($module) {
    $icons = [
        'teacher' => 'chalkboard-teacher',
        'student' => 'graduation-cap',
        'parent' => 'user-friends',
        'accountant' => 'calculator',
        'librarian' => 'book',
        'payment' => 'credit-card',
        'attendance' => 'check-circle',
        'exam' => 'file-alt',
        'library' => 'books',
        'transport' => 'bus',
        'hostel' => 'bed',
        'report' => 'chart-bar',
        'setting' => 'cog',
        'role' => 'user-shield'
    ];
    return $icons[$module] ?? 'folder';
}
@endphp

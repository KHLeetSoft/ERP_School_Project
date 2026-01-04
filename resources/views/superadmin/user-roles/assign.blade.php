@extends('superadmin.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Assign Roles to User</h4>
        <a href="{{ route('superadmin.user-roles.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to User Roles
        </a>
    </div>
    <div class="card-body">
        <!-- User Info -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                <span class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                                <small class="text-muted">
                                    <span class="badge bg-{{ $user->status ? 'success' : 'danger' }}">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($user->school)
                                        <span class="badge bg-info ms-1">{{ $user->school->name }}</span>
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
                        <h3 class="mb-0" id="selectedRolesCount">{{ count($userRoles) }}</h3>
                        <small>Roles Selected</small>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('superadmin.user-roles.store', $user->id) }}" method="POST" id="assignRolesForm">
            @csrf
            
            <!-- Role Selection -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Select Roles</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($roles as $role)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border {{ in_array($role->id, $userRoles) ? 'border-primary' : '' }}">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input role-checkbox" 
                                                       type="checkbox" 
                                                       name="roles[]" 
                                                       value="{{ $role->id }}" 
                                                       id="role_{{ $role->id }}"
                                                       {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="role_{{ $role->id }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            <i class="fas fa-user-shield text-white"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $role->name }}</h6>
                                                            <small class="text-muted">{{ $role->description ?: 'No description' }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="button" class="btn btn-outline-primary" id="selectAllRoles">
                                        <i class="fas fa-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="deselectAllRoles">
                                        <i class="fas fa-square"></i> Deselect All
                                    </button>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save Role Assignment
                                    </button>
                                    <a href="{{ route('superadmin.user-roles.index') }}" class="btn btn-outline-secondary">
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
        const selectedCount = $('.role-checkbox:checked').length;
        $('#selectedRolesCount').text(selectedCount);
    }

    // Select all roles
    $('#selectAllRoles').on('click', function() {
        $('.role-checkbox').prop('checked', true);
        updateSelectedCount();
        updateCardStyles();
    });

    // Deselect all roles
    $('#deselectAllRoles').on('click', function() {
        $('.role-checkbox').prop('checked', false);
        updateSelectedCount();
        updateCardStyles();
    });

    // Update count and styles on checkbox change
    $('.role-checkbox').on('change', function() {
        updateSelectedCount();
        updateCardStyles();
    });

    // Update card styles based on selection
    function updateCardStyles() {
        $('.role-checkbox').each(function() {
            const card = $(this).closest('.card');
            if ($(this).is(':checked')) {
                card.removeClass('border-secondary').addClass('border-primary');
            } else {
                card.removeClass('border-primary').addClass('border-secondary');
            }
        });
    }

    // Form submission
    $('#assignRolesForm').on('submit', function(e) {
        e.preventDefault();
        
        const selectedRoles = $('.role-checkbox:checked').length;
        
        if (selectedRoles === 0) {
            showAlert('warning', 'Please select at least one role!');
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
                showAlert('success', 'Roles assigned successfully!');
                submitBtn.html(originalText).prop('disabled', false);
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = '{{ route("superadmin.user-roles.index") }}';
                }, 1500);
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

    // Initialize count and styles
    updateSelectedCount();
    updateCardStyles();
});
</script>
@endsection

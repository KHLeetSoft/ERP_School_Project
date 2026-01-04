@extends('superadmin.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>User Roles & Permissions</h4>
        <div>
            <a href="{{ route('superadmin.user-roles.assign', $user->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-user-plus"></i> Assign Roles
            </a>
            <a href="{{ route('superadmin.user-roles.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to User Roles
            </a>
        </div>
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
                        <h3 class="mb-0">{{ $user->roles->count() }}</h3>
                        <small>Assigned Roles</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Roles -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Assigned Roles</h5>
                    </div>
                    <div class="card-body">
                        @if($user->roles->count() > 0)
                            <div class="row">
                                @foreach($user->roles as $role)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user-shield text-white"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ $role->name }}</h6>
                                                    <small class="text-muted">{{ $role->description ?: 'No description' }}</small>
                                                </div>
                                                <div>
                                                    <button class="btn btn-sm btn-outline-danger remove-role-btn" 
                                                            data-role-id="{{ $role->id }}" 
                                                            data-role-name="{{ $role->name }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Roles Assigned</h5>
                                <p class="text-muted">This user doesn't have any roles assigned yet.</p>
                                <a href="{{ route('superadmin.user-roles.assign', $user->id) }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Assign Roles
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- User Permissions by Module -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Permissions by Module</h5>
                    </div>
                    <div class="card-body">
                        @if($permissions->count() > 0)
                            <div class="row">
                                @foreach($permissions as $module => $modulePermissions)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0 d-flex align-items-center">
                                                <i class="fas fa-{{ getModuleIcon($module) }} me-2"></i>
                                                {{ ucfirst($module) }}
                                                <span class="badge bg-primary ms-auto">{{ $modulePermissions->count() }}</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($modulePermissions as $permission)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <small>{{ ucfirst(explode('.', $permission->name)[1] ?? 'unknown') }}</small>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-key fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Permissions</h5>
                                <p class="text-muted">This user doesn't have any permissions assigned.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Remove role
    $(document).on('click', '.remove-role-btn', function(e) {
        e.preventDefault();
        
        const roleId = $(this).data('role-id');
        const roleName = $(this).data('role-name');
        
        if (confirm(`Are you sure you want to remove the "${roleName}" role from this user?`)) {
            $.ajax({
                url: '{{ url("superadmin/user-roles") }}/{{ $user->id }}/role/' + roleId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        // Reload the page to update the display
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function() {
                    showAlert('error', 'Something went wrong!');
                }
            });
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

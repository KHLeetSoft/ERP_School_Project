@extends('superadmin.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Role Details</h4>
        <div>
            <a href="{{ route('superadmin.roles.edit', $role->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('superadmin.roles.permissions', $role->id) }}" class="btn btn-sm btn-info">
                <i class="fas fa-key"></i> Manage Permissions
            </a>
            <a href="{{ route('superadmin.roles.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Roles
            </a>
        </div>
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
                        <h3 class="mb-0">{{ $role->permissions->count() }}</h3>
                        <small>Permissions Assigned</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $role->permissions->count() }}</h4>
                        <small>Total Permissions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $role->users->count() }}</h4>
                        <small>Users Assigned</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $role->permissions->groupBy(function($p) { return explode('.', $p->name)[0]; })->count() }}</h4>
                        <small>Modules Covered</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4 class="mb-0">{{ $role->created_at->diffForHumans() }}</h4>
                        <small>Created</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions by Module -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Permissions by Module</h5>
                    </div>
                    <div class="card-body">
                        @if($role->permissions->count() > 0)
                            @php
                                $permissionsByModule = $role->permissions->groupBy(function($permission) {
                                    $parts = explode('.', $permission->name);
                                    return $parts[0] ?? 'unknown';
                                });
                            @endphp
                            <div class="row">
                                @foreach($permissionsByModule as $module => $modulePermissions)
                                <div class="col-md-6 col-lg-4 mb-3">
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
                                <h5 class="text-muted">No Permissions Assigned</h5>
                                <p class="text-muted">This role doesn't have any permissions assigned yet.</p>
                                <a href="{{ route('superadmin.roles.permissions', $role->id) }}" class="btn btn-primary">
                                    <i class="fas fa-key"></i> Assign Permissions
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Users with this Role -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Users with this Role</h5>
                    </div>
                    <div class="card-body">
                        @if($role->users->count() > 0)
                            <div class="row">
                                @foreach($role->users as $user)
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                    <br>
                                                    <span class="badge bg-{{ $user->status ? 'success' : 'danger' }}">
                                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Users Assigned</h5>
                                <p class="text-muted">No users have been assigned this role yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

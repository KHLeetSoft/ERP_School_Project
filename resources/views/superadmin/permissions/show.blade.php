@extends('superadmin.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Permission Details</h4>
        <div>
            <a href="{{ route('superadmin.permissions.edit', $permission->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('superadmin.permissions.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Permissions
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Permission Info -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-key text-white"></i>
                            </div>
                            <div>
                                @php
                                    $parts = explode('.', $permission->name);
                                    $module = $parts[0] ?? 'unknown';
                                    $action = $parts[1] ?? 'unknown';
                                @endphp
                                <h5 class="mb-1">{{ ucfirst($module) }} - {{ ucfirst($action) }}</h5>
                                <p class="text-muted mb-0">{{ $permission->name }}</p>
                                <small class="text-muted">
                                    <span class="badge bg-success">Active</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $permission->roles->count() }}</h3>
                        <small>Roles Using This Permission</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Using This Permission -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Roles Using This Permission</h5>
                    </div>
                    <div class="card-body">
                        @if($permission->roles->count() > 0)
                            <div class="row">
                                @foreach($permission->roles as $role)
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user-shield text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $role->name }}</h6>
                                                    <small class="text-muted">{{ $role->description ?: 'No description' }}</small>
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
                                <p class="text-muted">This permission is not assigned to any roles yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

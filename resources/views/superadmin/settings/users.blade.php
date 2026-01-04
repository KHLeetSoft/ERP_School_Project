@extends('superadmin.app')

@section('title', 'User & Roles Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-user-check fs-1 text-success"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">User & Roles Settings</h1>
                            <p class="text-muted mb-0">Manage users, roles, permissions, and security settings</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-end">
                        <a href="{{ route('superadmin.settings.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-2"></i>Back to Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-user-check me-2 text-success"></i>User Management Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.users.update') }}" method="POST">
                                @csrf
                                
                                <!-- Login Security -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-shield me-2"></i>Login Security
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="max_login_attempts" class="form-label fw-bold">Maximum Login Attempts</label>
                                        <input type="number" class="form-control @error('max_login_attempts') is-invalid @enderror" 
                                               id="max_login_attempts" name="max_login_attempts" 
                                               value="{{ $settings['max_login_attempts'] ?? '5' }}" 
                                               min="1" max="10" required>
                                        @error('max_login_attempts')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Number of failed login attempts before account lockout</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="session_timeout" class="form-label fw-bold">Session Timeout (minutes)</label>
                                        <input type="number" class="form-control @error('session_timeout') is-invalid @enderror" 
                                               id="session_timeout" name="session_timeout" 
                                               value="{{ $settings['session_timeout'] ?? '120' }}" 
                                               min="30" max="1440" required>
                                        @error('session_timeout')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Session will expire after this many minutes of inactivity</small>
                                    </div>
                                </div>

                                <!-- Two-Factor Authentication -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-mobile me-2"></i>Two-Factor Authentication
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="two_factor_enabled" 
                                                   name="two_factor_enabled" value="1" 
                                                   {{ ($settings['two_factor_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="two_factor_enabled">
                                                Enable Two-Factor Authentication
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Require users to verify their identity with a second factor</small>
                                    </div>
                                </div>

                                <!-- Password Policy -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-lock me-2"></i>Password Policy
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_min_length" class="form-label fw-bold">Minimum Password Length</label>
                                        <input type="number" class="form-control @error('password_min_length') is-invalid @enderror" 
                                               id="password_min_length" name="password_min_length" 
                                               value="{{ $settings['password_min_length'] ?? '8' }}" 
                                               min="6" max="32" required>
                                        @error('password_min_length')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Minimum number of characters required in passwords</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" id="password_require_special" 
                                                   name="password_require_special" value="1" 
                                                   {{ ($settings['password_require_special'] ?? '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="password_require_special">
                                                Require Special Characters
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Passwords must contain special characters (!@#$%^&*)</small>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bx bx-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- User Statistics -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-bar-chart me-2 text-info"></i>User Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-icon bg-primary">
                                            <i class="bx bx-user-check"></i>
                                        </div>
                                        <h4 class="stat-number">{{ $superAdmins->count() }}</h4>
                                        <p class="stat-label">Super Admins</p>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-icon bg-success">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <h4 class="stat-number">{{ $admins->count() }}</h4>
                                        <p class="stat-label">Admins</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Settings -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-cog me-2 text-warning"></i>Current Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Max Login Attempts:</strong> 
                                    <span class="text-primary">{{ $settings['max_login_attempts'] ?? '5' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Session Timeout:</strong> 
                                    <span class="text-primary">{{ $settings['session_timeout'] ?? '120' }} minutes</span>
                                </li>
                                <li class="mb-2">
                                    <strong>2FA Enabled:</strong> 
                                    <span class="badge badge-{{ ($settings['two_factor_enabled'] ?? '0') == '1' ? 'success' : 'danger' }}">
                                        {{ ($settings['two_factor_enabled'] ?? '0') == '1' ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <strong>Min Password Length:</strong> 
                                    <span class="text-primary">{{ $settings['password_min_length'] ?? '8' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Special Chars Required:</strong> 
                                    <span class="badge badge-{{ ($settings['password_require_special'] ?? '1') == '1' ? 'success' : 'danger' }}">
                                        {{ ($settings['password_require_special'] ?? '1') == '1' ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Management Tables -->
            <div class="row mt-4">
                <!-- Super Admins -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-user-check me-2 text-primary"></i>Super Admins
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($superAdmins as $superAdmin)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bx bx-user text-white"></i>
                                                    </div>
                                                    <span class="fw-semibold">{{ $superAdmin->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $superAdmin->email }}</td>
                                            <td>
                                                <span class="badge badge-{{ $superAdmin->status ? 'success' : 'danger' }}">
                                                    {{ $superAdmin->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No super admins found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admins -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-user me-2 text-success"></i>School Admins
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>School</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($admins as $admin)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bx bx-user text-white"></i>
                                                    </div>
                                                    <span class="fw-semibold">{{ $admin->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $admin->managedSchool->name ?? 'No School' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $admin->status ? 'success' : 'danger' }}">
                                                    {{ $admin->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No admins found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.settings-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

.form-label {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e1e5e9;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn {
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stat-card {
    text-align: center;
    padding: 1rem;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    color: #2c3e50;
}

.stat-label {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

.avatar-sm {
    width: 35px;
    height: 35px;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.badge-success {
    background-color: #d4edda;
    color: #155724;
}

.badge-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,0.05);
}
</style>
@endsection

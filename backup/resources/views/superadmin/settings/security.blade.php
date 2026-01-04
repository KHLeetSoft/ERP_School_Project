@extends('superadmin.app')

@section('title', 'Security Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-shield fs-1 text-danger"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">Security Settings</h1>
                            <p class="text-muted mb-0">SSL, password policies, IP restrictions, and audit logs</p>
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
                                <i class="bx bx-shield me-2 text-danger"></i>Security Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.security.update') }}" method="POST">
                                @csrf
                                
                                <!-- SSL & HTTPS -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-lock me-2"></i>SSL & HTTPS
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="force_https" 
                                                   name="force_https" value="1" 
                                                   {{ ($settings['force_https'] ?? '0') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="force_https">
                                                Force HTTPS
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Redirect all HTTP traffic to HTTPS</small>
                                    </div>
                                </div>

                                <!-- Password Policy -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-key me-2"></i>Password Policy
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_expiry_days" class="form-label fw-bold">Password Expiry (Days)</label>
                                        <input type="number" class="form-control @error('password_expiry_days') is-invalid @enderror" 
                                               id="password_expiry_days" name="password_expiry_days" 
                                               value="{{ $settings['password_expiry_days'] ?? '90' }}" 
                                               min="30" max="365">
                                        @error('password_expiry_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Leave empty for no expiry</small>
                                    </div>
                                </div>

                                <!-- IP Restrictions -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-network-chart me-2"></i>IP Restrictions
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <label for="ip_whitelist" class="form-label fw-bold">IP Whitelist</label>
                                        <textarea class="form-control @error('ip_whitelist') is-invalid @enderror" 
                                                  id="ip_whitelist" name="ip_whitelist" rows="3" 
                                                  placeholder="Enter IP addresses, one per line...">{{ $settings['ip_whitelist'] ?? '' }}</textarea>
                                        @error('ip_whitelist')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">One IP address per line. Leave empty to allow all IPs.</small>
                                    </div>
                                </div>

                                <!-- Protection Settings -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-shield-check me-2"></i>Protection Settings
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="csrf_protection" 
                                                   name="csrf_protection" value="1" 
                                                   {{ ($settings['csrf_protection'] ?? '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="csrf_protection">
                                                CSRF Protection
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Protect against Cross-Site Request Forgery</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="xss_protection" 
                                                   name="xss_protection" value="1" 
                                                   {{ ($settings['xss_protection'] ?? '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="xss_protection">
                                                XSS Protection
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Protect against Cross-Site Scripting</small>
                                    </div>
                                </div>

                                <!-- Audit Logs -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-file me-2"></i>Audit & Logging
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="audit_log_enabled" 
                                                   name="audit_log_enabled" value="1" 
                                                   {{ ($settings['audit_log_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="audit_log_enabled">
                                                Enable Audit Logs
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Log all user activities and system events</small>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-danger btn-lg">
                                        <i class="bx bx-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security Status -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-shield-check me-2 text-success"></i>Security Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="security-status">
                                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                                    <span>HTTPS</span>
                                    <span class="badge badge-{{ ($settings['force_https'] ?? '0') == '1' ? 'success' : 'danger' }}">
                                        {{ ($settings['force_https'] ?? '0') == '1' ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </div>
                                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                                    <span>CSRF Protection</span>
                                    <span class="badge badge-{{ ($settings['csrf_protection'] ?? '1') == '1' ? 'success' : 'danger' }}">
                                        {{ ($settings['csrf_protection'] ?? '1') == '1' ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </div>
                                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                                    <span>XSS Protection</span>
                                    <span class="badge badge-{{ ($settings['xss_protection'] ?? '1') == '1' ? 'success' : 'danger' }}">
                                        {{ ($settings['xss_protection'] ?? '1') == '1' ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </div>
                                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                                    <span>Audit Logs</span>
                                    <span class="badge badge-{{ ($settings['audit_log_enabled'] ?? '1') == '1' ? 'success' : 'danger' }}">
                                        {{ ($settings['audit_log_enabled'] ?? '1') == '1' ? 'Enabled' : 'Disabled' }}
                                    </span>
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
                                    <strong>Password Expiry:</strong> 
                                    <span class="text-primary">{{ $settings['password_expiry_days'] ?? '90' }} days</span>
                                </li>
                                <li class="mb-2">
                                    <strong>IP Whitelist:</strong> 
                                    <span class="text-primary">{{ $settings['ip_whitelist'] ? 'Configured' : 'Not set' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.settings-icon {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
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
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
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

.status-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.status-item:last-child {
    border-bottom: none;
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

.list-unstyled li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.list-unstyled li:last-child {
    border-bottom: none;
}
</style>
@endsection

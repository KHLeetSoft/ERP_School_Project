@extends('superadmin.app')

@section('title', 'System Configuration')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-server fs-1 text-warning"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">System Configuration</h1>
                            <p class="text-muted mb-0">Environment, logging, maintenance mode, and API keys</p>
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
                                <i class="bx bx-server me-2 text-warning"></i>System Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.system.update') }}" method="POST">
                                @csrf
                                
                                <!-- Environment Settings -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-cog me-2"></i>Environment Settings
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="app_env" class="form-label fw-bold">Application Environment</label>
                                        <select class="form-select @error('app_env') is-invalid @enderror" 
                                                id="app_env" name="app_env" required>
                                            <option value="local" {{ ($settings['app_env'] ?? 'production') == 'local' ? 'selected' : '' }}>Local</option>
                                            <option value="staging" {{ ($settings['app_env'] ?? '') == 'staging' ? 'selected' : '' }}>Staging</option>
                                            <option value="production" {{ ($settings['app_env'] ?? 'production') == 'production' ? 'selected' : '' }}>Production</option>
                                        </select>
                                        @error('app_env')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="log_level" class="form-label fw-bold">Log Level</label>
                                        <select class="form-select @error('log_level') is-invalid @enderror" 
                                                id="log_level" name="log_level" required>
                                            <option value="debug" {{ ($settings['log_level'] ?? 'error') == 'debug' ? 'selected' : '' }}>Debug</option>
                                            <option value="info" {{ ($settings['log_level'] ?? '') == 'info' ? 'selected' : '' }}>Info</option>
                                            <option value="warning" {{ ($settings['log_level'] ?? '') == 'warning' ? 'selected' : '' }}>Warning</option>
                                            <option value="error" {{ ($settings['log_level'] ?? 'error') == 'error' ? 'selected' : '' }}>Error</option>
                                        </select>
                                        @error('log_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Debug & Maintenance -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-wrench me-2"></i>Debug & Maintenance
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="debug_mode" 
                                                   name="debug_mode" value="1" 
                                                   {{ ($settings['debug_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="debug_mode">
                                                Debug Mode
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Enable detailed error reporting</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="maintenance_mode" 
                                                   name="maintenance_mode" value="1" 
                                                   {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="maintenance_mode">
                                                Maintenance Mode
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Put the application in maintenance mode</small>
                                    </div>
                                </div>

                                <!-- API Keys -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-key me-2"></i>API Keys
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="stripe_key" class="form-label fw-bold">Stripe Public Key</label>
                                        <input type="text" class="form-control @error('stripe_key') is-invalid @enderror" 
                                               id="stripe_key" name="stripe_key" 
                                               value="{{ $settings['stripe_key'] ?? '' }}">
                                        @error('stripe_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="stripe_secret" class="form-label fw-bold">Stripe Secret Key</label>
                                        <input type="password" class="form-control @error('stripe_secret') is-invalid @enderror" 
                                               id="stripe_secret" name="stripe_secret" 
                                               value="{{ $settings['stripe_secret'] ?? '' }}">
                                        @error('stripe_secret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="razorpay_key" class="form-label fw-bold">Razorpay Key</label>
                                        <input type="text" class="form-control @error('razorpay_key') is-invalid @enderror" 
                                               id="razorpay_key" name="razorpay_key" 
                                               value="{{ $settings['razorpay_key'] ?? '' }}">
                                        @error('razorpay_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="razorpay_secret" class="form-label fw-bold">Razorpay Secret</label>
                                        <input type="password" class="form-control @error('razorpay_secret') is-invalid @enderror" 
                                               id="razorpay_secret" name="razorpay_secret" 
                                               value="{{ $settings['razorpay_secret'] ?? '' }}">
                                        @error('razorpay_secret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="google_api_key" class="form-label fw-bold">Google API Key</label>
                                        <input type="text" class="form-control @error('google_api_key') is-invalid @enderror" 
                                               id="google_api_key" name="google_api_key" 
                                               value="{{ $settings['google_api_key'] ?? '' }}">
                                        @error('google_api_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="openai_api_key" class="form-label fw-bold">OpenAI API Key</label>
                                        <input type="password" class="form-control @error('openai_api_key') is-invalid @enderror" 
                                               id="openai_api_key" name="openai_api_key" 
                                               value="{{ $settings['openai_api_key'] ?? '' }}">
                                        @error('openai_api_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="bx bx-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-info-circle me-2 text-info"></i>System Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>App Version:</strong> 
                                    <span class="text-primary">{{ $systemInfo['app_version'] ?? '1.0.0' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Laravel Version:</strong> 
                                    <span class="text-primary">{{ $systemInfo['laravel_version'] ?? 'Unknown' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>PHP Version:</strong> 
                                    <span class="text-primary">{{ $systemInfo['php_version'] ?? 'Unknown' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Server:</strong> 
                                    <span class="text-primary">{{ $systemInfo['server_software'] ?? 'Unknown' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Database:</strong> 
                                    <span class="text-primary">{{ $systemInfo['database_version'] ?? 'Unknown' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Memory Limit:</strong> 
                                    <span class="text-primary">{{ $systemInfo['memory_limit'] ?? 'Unknown' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Max Execution Time:</strong> 
                                    <span class="text-primary">{{ $systemInfo['max_execution_time'] ?? 'Unknown' }}s</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Upload Max Filesize:</strong> 
                                    <span class="text-primary">{{ $systemInfo['upload_max_filesize'] ?? 'Unknown' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-zap me-2 text-success"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" onclick="clearCache()">
                                    <i class="bx bx-refresh me-2"></i>Clear Cache
                                </button>
                                <button class="btn btn-outline-warning" onclick="toggleMaintenance()">
                                    <i class="bx bx-wrench me-2"></i>Toggle Maintenance
                                </button>
                                <button class="btn btn-outline-info" onclick="viewLogs()">
                                    <i class="bx bx-file me-2"></i>View Logs
                                </button>
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
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
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
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
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

.list-unstyled li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.list-unstyled li:last-child {
    border-bottom: none;
}
</style>

<script>
function clearCache() {
    if (confirm('Are you sure you want to clear all caches?')) {
        fetch('{{ route("superadmin.settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Cache cleared successfully!', 'success');
            } else {
                showAlert('Failed to clear cache!', 'error');
            }
        })
        .catch(error => {
            showAlert('Error occurred while clearing cache!', 'error');
        });
    }
}

function toggleMaintenance() {
    if (confirm('Are you sure you want to toggle maintenance mode?')) {
        fetch('{{ route("superadmin.settings.toggle-maintenance") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
            } else {
                showAlert('Failed to toggle maintenance mode!', 'error');
            }
        })
        .catch(error => {
            showAlert('Error occurred while toggling maintenance mode!', 'error');
        });
    }
}

function viewLogs() {
    window.open('{{ route("superadmin.settings.developer") }}', '_blank');
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="bx bx-${type === 'success' ? 'check-circle' : 'error-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection

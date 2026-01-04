@extends('superadmin.app')

@section('title', 'Developer Tools')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-code-alt fs-1 text-dark"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">Developer Tools</h1>
                            <p class="text-muted mb-0">API documentation, webhooks, debugging, and system info</p>
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
                                <i class="bx bx-code-alt me-2 text-dark"></i>Developer Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.developer.update') }}" method="POST">
                                @csrf
                                
                                <!-- API Documentation -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-book me-2"></i>API Documentation
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="api_documentation_enabled" 
                                                   name="api_documentation_enabled" value="1" 
                                                   {{ ($settings['api_documentation_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="api_documentation_enabled">
                                                Enable API Documentation
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Enable Swagger/OpenAPI documentation</small>
                                    </div>
                                </div>

                                <!-- Webhooks -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-webhook me-2"></i>Webhooks
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <label for="webhook_url" class="form-label fw-bold">Webhook URL</label>
                                        <input type="url" class="form-control @error('webhook_url') is-invalid @enderror" 
                                               id="webhook_url" name="webhook_url" 
                                               value="{{ $settings['webhook_url'] ?? '' }}" 
                                               placeholder="https://example.com/webhook">
                                        @error('webhook_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">URL to receive webhook notifications</small>
                                    </div>
                                </div>

                                <!-- Custom Code -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-code me-2"></i>Custom Code
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <label for="custom_css" class="form-label fw-bold">Custom CSS</label>
                                        <textarea class="form-control @error('custom_css') is-invalid @enderror" 
                                                  id="custom_css" name="custom_css" rows="5" 
                                                  placeholder="/* Custom CSS code */">{{ $settings['custom_css'] ?? '' }}</textarea>
                                        @error('custom_css')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label for="custom_js" class="form-label fw-bold">Custom JavaScript</label>
                                        <textarea class="form-control @error('custom_js') is-invalid @enderror" 
                                                  id="custom_js" name="custom_js" rows="5" 
                                                  placeholder="// Custom JavaScript code">{{ $settings['custom_js'] ?? '' }}</textarea>
                                        @error('custom_js')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-dark btn-lg">
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
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Logs -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-file me-2 text-warning"></i>Recent Logs
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="logs-container" style="max-height: 300px; overflow-y: auto;">
                                @forelse($logs as $log)
                                <div class="log-entry mb-2 p-2 bg-light rounded">
                                    <small class="text-muted">{{ $log }}</small>
                                </div>
                                @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bx bx-file fs-1"></i>
                                    <p class="mb-0">No logs found</p>
                                </div>
                                @endforelse
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
    background: linear-gradient(135deg, #343a40 0%, #212529 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(52, 58, 64, 0.3);
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
    border-color: #343a40;
    box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.25);
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

.log-entry {
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
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

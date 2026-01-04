@extends('superadmin.app')

@section('title', 'Advanced Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-slider-alt fs-1 text-warning"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">Advanced Settings</h1>
                            <p class="text-muted mb-0">Multi-tenancy, GDPR compliance, and system reset options</p>
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
                                <i class="bx bx-slider-alt me-2 text-warning"></i>Advanced Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.advanced.update') }}" method="POST">
                                @csrf
                                
                                <!-- Multi-Tenancy -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-buildings me-2"></i>Multi-Tenancy
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="multi_tenancy_enabled" 
                                                   name="multi_tenancy_enabled" value="1" 
                                                   {{ ($settings['multi_tenancy_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="multi_tenancy_enabled">
                                                Enable Multi-Tenancy
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Enable multi-tenant architecture for multiple organizations</small>
                                    </div>
                                </div>

                                <!-- Data Retention -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-data me-2"></i>Data Retention Policy
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="data_retention_days" class="form-label fw-bold">Data Retention (Days)</label>
                                        <input type="number" class="form-control @error('data_retention_days') is-invalid @enderror" 
                                               id="data_retention_days" name="data_retention_days" 
                                               value="{{ $settings['data_retention_days'] ?? '365' }}" 
                                               min="30" max="3650">
                                        @error('data_retention_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">How long to keep user data (30-3650 days)</small>
                                    </div>
                                </div>

                                <!-- GDPR Compliance -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-shield me-2"></i>GDPR Compliance
                                        </h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="gdpr_compliance" 
                                                   name="gdpr_compliance" value="1" 
                                                   {{ ($settings['gdpr_compliance'] ?? '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="gdpr_compliance">
                                                Enable GDPR Compliance
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Enable GDPR compliance features and data protection</small>
                                    </div>
                                </div>

                                <!-- Legal Pages -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-file me-2"></i>Legal Pages
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="privacy_policy_url" class="form-label fw-bold">Privacy Policy URL</label>
                                        <input type="url" class="form-control @error('privacy_policy_url') is-invalid @enderror" 
                                               id="privacy_policy_url" name="privacy_policy_url" 
                                               value="{{ $settings['privacy_policy_url'] ?? '' }}" 
                                               placeholder="https://example.com/privacy-policy">
                                        @error('privacy_policy_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="terms_of_service_url" class="form-label fw-bold">Terms of Service URL</label>
                                        <input type="url" class="form-control @error('terms_of_service_url') is-invalid @enderror" 
                                               id="terms_of_service_url" name="terms_of_service_url" 
                                               value="{{ $settings['terms_of_service_url'] ?? '' }}" 
                                               placeholder="https://example.com/terms-of-service">
                                        @error('terms_of_service_url')
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

                <!-- System Reset -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-reset me-2 text-danger"></i>System Reset
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="bx bx-error-circle me-2"></i>
                                <strong>Warning:</strong> These actions are irreversible and should only be performed by developers.
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-danger" onclick="resetSettings()">
                                    <i class="bx bx-reset me-2"></i>Reset All Settings
                                </button>
                                <button class="btn btn-outline-warning" onclick="clearAllData()">
                                    <i class="bx bx-trash me-2"></i>Clear All Data
                                </button>
                                
                                <!-- Export Settings -->
                                <div class="dropdown">
                                    <button class="btn btn-outline-info dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-export me-2"></i>Export Settings
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        <li><a class="dropdown-item" href="#" onclick="exportSettings('json', 'all')">
                                            <i class="bx bx-file me-2"></i>Export All (JSON)
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportSettings('csv', 'all')">
                                            <i class="bx bx-file me-2"></i>Export All (CSV)
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportSettings('excel', 'all')">
                                            <i class="bx bx-file me-2"></i>Export All (Excel)
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportSettings('json', 'general')">
                                            <i class="bx bx-cog me-2"></i>General Settings (JSON)
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportSettings('json', 'security')">
                                            <i class="bx bx-shield me-2"></i>Security Settings (JSON)
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="exportSettings('json', 'theme')">
                                            <i class="bx bx-palette me-2"></i>Theme Settings (JSON)
                                        </a></li>
                                    </ul>
                                </div>
                                
                                <!-- Import Settings -->
                                <button class="btn btn-outline-primary" onclick="showImportModal()">
                                    <i class="bx bx-import me-2"></i>Import Settings
                                </button>
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
                                    <strong>Multi-Tenancy:</strong> 
                                    <span class="badge badge-{{ ($settings['multi_tenancy_enabled'] ?? '0') == '1' ? 'success' : 'danger' }}">
                                        {{ ($settings['multi_tenancy_enabled'] ?? '0') == '1' ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <strong>Data Retention:</strong> 
                                    <span class="text-primary">{{ $settings['data_retention_days'] ?? '365' }} days</span>
                                </li>
                                <li class="mb-2">
                                    <strong>GDPR:</strong> 
                                    <span class="badge badge-{{ ($settings['gdpr_compliance'] ?? '1') == '1' ? 'success' : 'danger' }}">
                                        {{ ($settings['gdpr_compliance'] ?? '1') == '1' ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <strong>Privacy Policy:</strong> 
                                    <span class="text-primary">{{ $settings['privacy_policy_url'] ? 'Set' : 'Not set' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Terms of Service:</strong> 
                                    <span class="text-primary">{{ $settings['terms_of_service_url'] ? 'Set' : 'Not set' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Import Settings Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="bx bx-import me-2"></i>Import Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importForm" action="{{ route('superadmin.settings.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Supported formats:</strong> JSON, CSV, Excel (.xlsx)
                    </div>
                    
                    <div class="mb-3">
                        <label for="settings_file" class="form-label fw-bold">Select Settings File</label>
                        <input type="file" class="form-control @error('settings_file') is-invalid @enderror" 
                               id="settings_file" name="settings_file" 
                               accept=".json,.csv,.xlsx" required>
                        @error('settings_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Maximum file size: 2MB</small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bx bx-error-circle me-2"></i>
                        <strong>Warning:</strong> Importing settings will overwrite existing settings with the same keys.
                    </div>
                    
                    <!-- Progress indicator -->
                    <div id="importProgress" class="d-none">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%">
                                Importing settings...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="importBtn">
                        <i class="bx bx-import me-2"></i>Import Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
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

<script>
function resetSettings() {
    if (confirm('Are you sure you want to reset all settings to default? This action cannot be undone.')) {
        // Implementation for resetting settings
        showAlert('Settings reset functionality not implemented yet.', 'warning');
    }
}

function clearAllData() {
    if (confirm('Are you sure you want to clear all data? This will delete all users, schools, and other data. This action cannot be undone.')) {
        // Implementation for clearing all data
        showAlert('Clear data functionality not implemented yet.', 'warning');
    }
}

function exportSettings(format, category) {
    const url = new URL('{{ route("superadmin.settings.export") }}', window.location.origin);
    url.searchParams.append('format', format);
    url.searchParams.append('category', category);
    
    // Create a temporary link to trigger download
    const link = document.createElement('a');
    link.href = url.toString();
    link.download = `settings_export_${category}_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.${format}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showAlert(`Settings exported successfully as ${format.toUpperCase()}!`, 'success');
}

function showImportModal() {
    const modal = new bootstrap.Modal(document.getElementById('importModal'));
    modal.show();
    
    // Reset form when modal is shown
    document.getElementById('importForm').reset();
    document.getElementById('importProgress').classList.add('d-none');
    document.getElementById('importBtn').disabled = false;
}

function importSettings() {
    // This function is called when the form is submitted
    // The actual import is handled by the form submission
    // No need to show alert as the form will handle the submission
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const importForm = document.getElementById('importForm');
    const importBtn = document.getElementById('importBtn');
    const importProgress = document.getElementById('importProgress');
    
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            // Show progress indicator
            importProgress.classList.remove('d-none');
            importBtn.disabled = true;
            importBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Importing...';
        });
    }
});

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'warning' ? 'error-circle' : 'info-circle'} me-2"></i>
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

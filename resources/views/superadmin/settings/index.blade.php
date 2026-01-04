@extends('superadmin.app')

@section('title', 'System Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-cog fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">System Settings</h1>
                            <p class="text-muted mb-0">Configure and manage all system settings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Settings Categories -->
            <div class="row">
                <!-- General Settings -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-cog fs-1 text-primary"></i>
                            </div>
                            <h5 class="card-title fw-bold">General Settings</h5>
                            <p class="card-text text-muted">App name, logo, language, timezone, and currency settings</p>
                            <a href="{{ route('superadmin.settings.general') }}" class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User & Roles Settings -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-user-check fs-1 text-success"></i>
                            </div>
                            <h5 class="card-title fw-bold">User & Roles</h5>
                            <p class="card-text text-muted">Manage users, roles, permissions, and security settings</p>
                            <a href="{{ route('superadmin.settings.users') }}" class="btn btn-success">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Email & Notifications -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-envelope fs-1 text-info"></i>
                            </div>
                            <h5 class="card-title fw-bold">Email & Notifications</h5>
                            <p class="card-text text-muted">SMTP configuration, email templates, and notification settings</p>
                            <a href="{{ route('superadmin.settings.email') }}" class="btn btn-info">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Configuration -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-server fs-1 text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">System Configuration</h5>
                            <p class="card-text text-muted">Environment, logging, maintenance mode, and API keys</p>
                            <a href="{{ route('superadmin.settings.system') }}" class="btn btn-warning">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Database & Backup -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-data fs-1 text-secondary"></i>
                            </div>
                            <h5 class="card-title fw-bold">Database & Backup</h5>
                            <p class="card-text text-muted">Backup settings, storage configuration, and data management</p>
                            <a href="{{ route('superadmin.settings.database') }}" class="btn btn-secondary">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-shield fs-1 text-danger"></i>
                            </div>
                            <h5 class="card-title fw-bold">Security Settings</h5>
                            <p class="card-text text-muted">SSL, password policies, IP restrictions, and audit logs</p>
                            <a href="{{ route('superadmin.settings.security') }}" class="btn btn-danger">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payment Settings -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-credit-card fs-1 text-primary"></i>
                            </div>
                            <h5 class="card-title fw-bold">Payment Settings</h5>
                            <p class="card-text text-muted">Payment gateways, subscription plans, and billing configuration</p>
                            <a href="{{ route('superadmin.settings.payment') }}" class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Developer Tools -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-code-alt fs-1 text-dark"></i>
                            </div>
                            <h5 class="card-title fw-bold">Developer Tools</h5>
                            <p class="card-text text-muted">API documentation, webhooks, debugging, and system info</p>
                            <a href="{{ route('superadmin.settings.developer') }}" class="btn btn-dark">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- UI/Theme Settings -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-palette fs-1 text-info"></i>
                            </div>
                            <h5 class="card-title fw-bold">UI/Theme Settings</h5>
                            <p class="card-text text-muted">Theme customization, colors, fonts, and UI preferences</p>
                            <a href="{{ route('superadmin.settings.theme') }}" class="btn btn-info">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Advanced Settings -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card settings-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="settings-category-icon mb-3">
                                <i class="bx bx-slider-alt fs-1 text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Advanced Settings</h5>
                            <p class="card-text text-muted">Multi-tenancy, GDPR compliance, and system reset options</p>
                            <a href="{{ route('superadmin.settings.advanced') }}" class="btn btn-warning">
                                <i class="bx bx-edit me-1"></i>Configure
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-zap me-2 text-warning"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-primary w-100" onclick="clearCache()">
                                        <i class="bx bx-refresh me-2"></i>Clear Cache
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-success w-100" onclick="createBackup()">
                                        <i class="bx bx-data me-2"></i>Create Backup
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-warning w-100" onclick="toggleMaintenance()">
                                        <i class="bx bx-wrench me-2"></i>Toggle Maintenance
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-info w-100" onclick="viewLogs()">
                                        <i class="bx bx-file me-2"></i>View Logs
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-success dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-export me-2"></i>Export Settings
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="exportSettings('json', 'all')">
                                                <i class="bx bx-file me-2"></i>Export All (JSON)
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportSettings('csv', 'all')">
                                                <i class="bx bx-file me-2"></i>Export All (CSV)
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportSettings('excel', 'all')">
                                                <i class="bx bx-file me-2"></i>Export All (Excel)
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-primary w-100" onclick="showImportModal()">
                                        <i class="bx bx-import me-2"></i>Import Settings
                                    </button>
                                </div>
                            </div>
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.settings-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.settings-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.settings-category-icon {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    transition: all 0.3s ease;
}

.settings-card:hover .settings-category-icon {
    transform: scale(1.1);
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
}

.card-title {
    color: #2c3e50;
    margin-bottom: 1rem;
}

.card-text {
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
}

.btn {
    border-radius: 25px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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

function createBackup() {
    if (confirm('Are you sure you want to create a database backup?')) {
        fetch('{{ route("superadmin.settings.create-backup") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Backup created successfully!', 'success');
            } else {
                showAlert('Failed to create backup!', 'error');
            }
        })
        .catch(error => {
            showAlert('Error occurred while creating backup!', 'error');
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
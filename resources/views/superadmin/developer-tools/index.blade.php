@extends('superadmin.app')

@section('title', 'Developer Tools')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-dark text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-code me-3"></i>Developer Tools
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Advanced development and deployment management tools</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">Dev Center</div>
                            <small class="opacity-75">Power User Tools</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-4 mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-rocket fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Deployment</h5>
                    <p class="text-muted small mb-3">Manage code deployments and releases</p>
                    <a href="{{ route('superadmin.developer-tools.deployment') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>Go to Deployment
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-4 mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-bug fa-2x text-danger"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Error Logs</h5>
                    <p class="text-muted small mb-3">View and manage system error logs</p>
                    <a href="{{ route('superadmin.developer-tools.error-logs') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>View Logs
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-4 mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-toggle-on fa-2x text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Feature Toggles</h5>
                    <p class="text-muted small mb-3">Control feature flags and toggles</p>
                    <a href="{{ route('superadmin.developer-tools.feature-toggles') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>Manage Toggles
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-4 mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-code-branch fa-2x text-info"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Version Control</h5>
                    <p class="text-muted small mb-3">Manage code versions and branches</p>
                    <a href="{{ route('superadmin.developer-tools.version-control') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>Version Control
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health & Status -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">System Health Check</h5>
                    <p class="text-muted mb-0">Real-time system status and performance metrics</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="health-item p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Database Connection</span>
                                    <span class="badge bg-success" id="dbStatus">Healthy</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="health-item p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Cache System</span>
                                    <span class="badge bg-success" id="cacheStatus">Healthy</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="health-item p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">File Storage</span>
                                    <span class="badge bg-warning" id="storageStatus">Warning</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="health-item p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Queue System</span>
                                    <span class="badge bg-success" id="queueStatus">Healthy</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Quick Actions</h5>
                    <p class="text-muted mb-0">Common development tasks</p>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="runArtisanCommand('cache:clear')">
                            <i class="fas fa-broom me-2"></i>Clear Cache
                        </button>
                        <button class="btn btn-outline-success" onclick="runArtisanCommand('config:cache')">
                            <i class="fas fa-cog me-2"></i>Cache Config
                        </button>
                        <button class="btn btn-outline-warning" onclick="runArtisanCommand('route:cache')">
                            <i class="fas fa-route me-2"></i>Cache Routes
                        </button>
                        <button class="btn btn-outline-info" onclick="runArtisanCommand('view:cache')">
                            <i class="fas fa-eye me-2"></i>Cache Views
                        </button>
                        <button class="btn btn-outline-danger" onclick="runArtisanCommand('migrate')">
                            <i class="fas fa-database me-2"></i>Run Migrations
                        </button>
                        <button class="btn btn-outline-secondary" onclick="runArtisanCommand('optimize')">
                            <i class="fas fa-tachometer-alt me-2"></i>Optimize App
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deployments -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Recent Deployments</h5>
                            <p class="text-muted mb-0">Latest code deployments and releases</p>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshDeployments()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Version</th>
                                    <th>Environment</th>
                                    <th>Deployed By</th>
                                    <th>Status</th>
                                    <th>Deployed At</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="deploymentsTable">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Toggles Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Active Feature Toggles</h5>
                            <p class="text-muted mb-0">Currently enabled feature flags</p>
                        </div>
                        <button class="btn btn-outline-success btn-sm" onclick="refreshFeatureToggles()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row" id="featureTogglesGrid">
                        <!-- Data will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Artisan Command Runner -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Artisan Command Runner</h5>
                    <p class="text-muted mb-0">Execute Laravel Artisan commands directly</p>
                </div>
                <div class="card-body p-4">
                    <form id="artisanCommandForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text">php artisan</span>
                                    <input type="text" class="form-control" id="artisanCommand" placeholder="Enter command (e.g., migrate, cache:clear)" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-play me-2"></i>Execute Command
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <div id="commandOutput" class="mt-3" style="display: none;">
                        <div class="card bg-dark text-white">
                            <div class="card-header">
                                <h6 class="mb-0">Command Output</h6>
                            </div>
                            <div class="card-body">
                                <pre id="commandResult" class="mb-0"></pre>
                            </div>
                        </div>
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
    loadDeployments();
    loadFeatureToggles();
    updateSystemHealth();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        updateSystemHealth();
        loadDeployments();
        loadFeatureToggles();
    }, 30000);
});

function loadDeployments() {
    const deployments = [
        {
            version: 'v2.1.0',
            environment: 'production',
            deployedBy: 'John Doe',
            status: 'success',
            deployedAt: '2 hours ago',
            notes: 'Added new AI features and improved performance'
        },
        {
            version: 'v2.0.5',
            environment: 'staging',
            deployedBy: 'Jane Smith',
            status: 'success',
            deployedAt: '1 day ago',
            notes: 'Bug fixes and security updates'
        },
        {
            version: 'v2.0.4',
            environment: 'production',
            deployedBy: 'Mike Johnson',
            status: 'failed',
            deployedAt: '3 days ago',
            notes: 'Rollback due to database issues'
        }
    ];
    
    let tableHtml = '';
    deployments.forEach(deployment => {
        const statusClass = {
            'success': 'success',
            'failed': 'danger',
            'pending': 'warning'
        }[deployment.status];
        
        tableHtml += `
            <tr>
                <td><code>${deployment.version}</code></td>
                <td><span class="badge bg-${deployment.environment === 'production' ? 'danger' : 'info'}">${deployment.environment.toUpperCase()}</span></td>
                <td>${deployment.deployedBy}</td>
                <td><span class="badge bg-${statusClass}">${deployment.status.toUpperCase()}</span></td>
                <td>${deployment.deployedAt}</td>
                <td>${deployment.notes}</td>
            </tr>
        `;
    });
    
    $('#deploymentsTable').html(tableHtml);
}

function loadFeatureToggles() {
    const toggles = [
        {
            name: 'ai_analytics',
            description: 'AI-powered analytics dashboard',
            enabled: true,
            schools: 'All',
            roles: 'superadmin,admin'
        },
        {
            name: 'advanced_reporting',
            description: 'Advanced reporting features',
            enabled: true,
            schools: 'Premium',
            roles: 'superadmin,admin'
        },
        {
            name: 'beta_features',
            description: 'Beta testing features',
            enabled: false,
            schools: 'Test Schools',
            roles: 'superadmin'
        },
        {
            name: 'mobile_app',
            description: 'Mobile application features',
            enabled: true,
            schools: 'All',
            roles: 'all'
        }
    ];
    
    let gridHtml = '';
    toggles.forEach(toggle => {
        const statusClass = toggle.enabled ? 'success' : 'secondary';
        const statusText = toggle.enabled ? 'Enabled' : 'Disabled';
        
        gridHtml += `
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-1 fw-bold">${toggle.name}</h6>
                            <span class="badge bg-${statusClass}">${statusText}</span>
                        </div>
                        <p class="text-muted small mb-2">${toggle.description}</p>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Schools: ${toggle.schools}</small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Roles: ${toggle.roles}</small>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="toggleFeature('${toggle.name}')">
                                <i class="fas fa-toggle-${toggle.enabled ? 'on' : 'off'} me-1"></i>
                                ${toggle.enabled ? 'Disable' : 'Enable'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#featureTogglesGrid').html(gridHtml);
}

function updateSystemHealth() {
    // Simulate system health checks
    const healthChecks = [
        { id: 'dbStatus', status: Math.random() > 0.1 ? 'success' : 'danger' },
        { id: 'cacheStatus', status: Math.random() > 0.05 ? 'success' : 'warning' },
        { id: 'storageStatus', status: Math.random() > 0.2 ? 'warning' : 'success' },
        { id: 'queueStatus', status: Math.random() > 0.05 ? 'success' : 'danger' }
    ];
    
    healthChecks.forEach(check => {
        const statusText = check.status === 'success' ? 'Healthy' : 
                          check.status === 'warning' ? 'Warning' : 'Error';
        const statusClass = check.status === 'success' ? 'success' : 
                           check.status === 'warning' ? 'warning' : 'danger';
        
        $(`#${check.id}`).removeClass('bg-success bg-warning bg-danger')
                         .addClass(`bg-${statusClass}`)
                         .text(statusText);
    });
}

function runArtisanCommand(command) {
    $('#artisanCommand').val(command);
    $('#artisanCommandForm').submit();
}

function refreshDeployments() {
    loadDeployments();
    showAlert('success', 'Deployments refreshed!');
}

function refreshFeatureToggles() {
    loadFeatureToggles();
    showAlert('success', 'Feature toggles refreshed!');
}

function toggleFeature(featureName) {
    showAlert('info', `Toggling feature: ${featureName}`);
    // Implement feature toggle logic
}

$('#artisanCommandForm').on('submit', function(e) {
    e.preventDefault();
    
    const command = $('#artisanCommand').val();
    if (!command.trim()) {
        showAlert('warning', 'Please enter a command');
        return;
    }
    
    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Executing...');
    submitBtn.prop('disabled', true);
    
    // Simulate command execution
    setTimeout(() => {
        $('#commandOutput').show();
        $('#commandResult').text(`Command: php artisan ${command}\n\nOutput:\nCommand executed successfully!\n\nExecution time: 0.123s\nMemory usage: 12.5MB`);
        
        submitBtn.html(originalText);
        submitBtn.prop('disabled', false);
        
        showAlert('success', 'Command executed successfully!');
    }, 2000);
});

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('body').prepend(alertHtml);
    
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 3000);
}
</script>
@endsection

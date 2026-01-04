@extends('superadmin.app')

@section('title', 'AI Fraud Detection')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-warning text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-shield-alt me-3"></i>AI Fraud Detection
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Intelligent security monitoring and threat detection</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">Security Center</div>
                            <small class="opacity-75">24/7 Monitoring</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-danger fw-bold small mb-1">High Risk</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="highRiskCount">0</div>
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-triangle me-1"></i>Immediate action needed
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-warning fw-bold small mb-1">Medium Risk</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="mediumRiskCount">0</div>
                            <div class="text-warning small">
                                <i class="fas fa-eye me-1"></i>Monitor closely
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-eye fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-info fw-bold small mb-1">Low Risk</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="lowRiskCount">0</div>
                            <div class="text-info small">
                                <i class="fas fa-check-circle me-1"></i>Normal activity
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-success fw-bold small mb-1">Blocked</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="blockedCount">0</div>
                            <div class="text-success small">
                                <i class="fas fa-shield-alt me-1"></i>Threats blocked
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-shield-alt fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspicious Activities -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Suspicious Activities</h5>
                            <p class="text-muted mb-0">AI-detected potential security threats</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshData()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="exportData()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Severity</th>
                                    <th>Description</th>
                                    <th>Detected</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="suspiciousActivitiesTable">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Patterns -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Threat Patterns</h5>
                    <p class="text-muted mb-0">Common security patterns detected</p>
                </div>
                <div class="card-body p-4">
                    <div class="threat-patterns">
                        <div class="pattern-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fw-bold">Multiple IP Logins</h6>
                                    <p class="mb-0 small text-muted">Users logging in from multiple IP addresses</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning">Medium</span>
                                    <div class="small text-muted">5 cases</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pattern-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fw-bold">Rapid Registrations</h6>
                                    <p class="mb-0 small text-muted">Unusual spike in new user registrations</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger">High</span>
                                    <div class="small text-muted">2 cases</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pattern-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fw-bold">Failed Payments</h6>
                                    <p class="mb-0 small text-muted">High number of failed payment attempts</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-info">Low</span>
                                    <div class="small text-muted">8 cases</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Security Recommendations</h5>
                    <p class="text-muted mb-0">AI-generated security suggestions</p>
                </div>
                <div class="card-body p-4">
                    <div class="recommendations">
                        <div class="recommendation-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-lightbulb text-warning me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Enable 2FA</h6>
                                    <p class="mb-0 small text-muted">Enable two-factor authentication for all admin accounts</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="recommendation-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-shield-alt text-info me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">IP Whitelisting</h6>
                                    <p class="mb-0 small text-muted">Consider implementing IP whitelisting for sensitive operations</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="recommendation-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-clock text-success me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Session Timeout</h6>
                                    <p class="mb-0 small text-muted">Reduce session timeout for inactive users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Monitoring -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Real-time Security Monitoring</h5>
                    <p class="text-muted mb-0">Live security events and alerts</p>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">Active Monitoring</h5>
                                <p class="text-muted small">24/7 AI surveillance</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-bolt fa-2x text-warning mb-2"></i>
                                <h5 class="mb-1">Instant Alerts</h5>
                                <p class="text-muted small">Real-time threat detection</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                                <h5 class="mb-1">Auto Protection</h5>
                                <p class="text-muted small">Automated security responses</p>
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
    loadSuspiciousActivities();
    updateSecurityMetrics();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadSuspiciousActivities();
        updateSecurityMetrics();
    }, 30000);
});

function loadSuspiciousActivities() {
    // Simulate loading suspicious activities
    const activities = [
        {
            type: 'Multiple IP Logins',
            severity: 'medium',
            description: 'User john@example.com logged in from 5 different IPs in 24 hours',
            detected: '2 hours ago',
            status: 'investigating'
        },
        {
            type: 'Rapid Registrations',
            severity: 'high',
            description: '15 new registrations in the last hour - possible bot activity',
            detected: '1 hour ago',
            status: 'active'
        },
        {
            type: 'Failed Payments',
            severity: 'low',
            description: '25 failed payment attempts from same IP in 10 minutes',
            detected: '30 minutes ago',
            status: 'resolved'
        },
        {
            type: 'Suspicious Login Pattern',
            severity: 'medium',
            description: 'User admin@school.com logged in at unusual hours',
            detected: '1 hour ago',
            status: 'monitoring'
        }
    ];
    
    let tableHtml = '';
    activities.forEach(activity => {
        const severityClass = {
            'high': 'danger',
            'medium': 'warning',
            'low': 'info'
        }[activity.severity];
        
        const statusClass = {
            'active': 'danger',
            'investigating': 'warning',
            'monitoring': 'info',
            'resolved': 'success'
        }[activity.status];
        
        tableHtml += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-${severityClass} me-2"></i>
                        ${activity.type}
                    </div>
                </td>
                <td><span class="badge bg-${severityClass}">${activity.severity.toUpperCase()}</span></td>
                <td>${activity.description}</td>
                <td>${activity.detected}</td>
                <td><span class="badge bg-${statusClass}">${activity.status.toUpperCase()}</span></td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" onclick="investigate('${activity.type}')">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="resolve('${activity.type}')">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="block('${activity.type}')">
                            <i class="fas fa-ban"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    $('#suspiciousActivitiesTable').html(tableHtml);
}

function updateSecurityMetrics() {
    // Simulate security metrics
    $('#highRiskCount').text(Math.floor(Math.random() * 5));
    $('#mediumRiskCount').text(Math.floor(Math.random() * 10) + 5);
    $('#lowRiskCount').text(Math.floor(Math.random() * 20) + 10);
    $('#blockedCount').text(Math.floor(Math.random() * 15) + 5);
}

function refreshData() {
    loadSuspiciousActivities();
    updateSecurityMetrics();
    showAlert('success', 'Security data refreshed successfully!');
}

function exportData() {
    showAlert('info', 'Exporting security data...');
    // Implement export functionality
}

function investigate(type) {
    showAlert('info', `Investigating ${type}...`);
}

function resolve(type) {
    showAlert('success', `${type} marked as resolved`);
}

function block(type) {
    showAlert('warning', `${type} blocked successfully`);
}

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

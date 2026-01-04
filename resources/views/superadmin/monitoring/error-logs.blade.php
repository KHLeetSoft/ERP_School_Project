@extends('superadmin.app')

@section('title', 'Error Logs Monitoring')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-danger text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-exclamation-triangle me-3"></i>Error Logs Monitoring
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">System error tracking and debugging tools</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0" id="totalErrors">0</div>
                            <small class="opacity-75">Errors Today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-danger fw-bold small mb-1">Critical Errors</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="criticalErrors">0</div>
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-circle me-1"></i>Immediate action needed
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
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
                            <div class="text-uppercase text-warning fw-bold small mb-1">Warnings</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="warnings">0</div>
                            <div class="text-warning small">
                                <i class="fas fa-exclamation-triangle me-1"></i>Needs attention
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
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
                            <div class="text-uppercase text-info fw-bold small mb-1">Info Messages</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="infoMessages">0</div>
                            <div class="text-info small">
                                <i class="fas fa-info-circle me-1"></i>Informational
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-info-circle fa-2x text-info"></i>
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
                            <div class="text-uppercase text-success fw-bold small mb-1">Resolved</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="resolvedErrors">0</div>
                            <div class="text-success small">
                                <i class="fas fa-check-circle me-1"></i>Fixed today
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Trends Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Error Trends</h5>
                    <p class="text-muted mb-0">Error frequency over the last 24 hours</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="errorTrendsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Logs Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Error Logs</h5>
                            <p class="text-muted mb-0">Recent system errors and warnings</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshErrorLogs()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="exportErrorLogs()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="clearErrorLogs()">
                                <i class="fas fa-trash me-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Level</th>
                                    <th>Message</th>
                                    <th>File</th>
                                    <th>Line</th>
                                    <th>Timestamp</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="errorLogsTable">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Analysis -->
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Error Categories</h5>
                    <p class="text-muted mb-0">Most common error types</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="errorCategoriesChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Top Error Sources</h5>
                    <p class="text-muted mb-0">Files with most errors</p>
                </div>
                <div class="card-body p-4">
                    <div class="error-sources">
                        <div class="source-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code class="small">app/Http/Controllers/AuthController.php</code>
                                    <div class="text-muted small">Authentication controller</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-danger">15</div>
                                    <div class="text-muted small">errors</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="source-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code class="small">app/Models/User.php</code>
                                    <div class="text-muted small">User model</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-warning">12</div>
                                    <div class="text-muted small">errors</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="source-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code class="small">app/Services/PaymentService.php</code>
                                    <div class="text-muted small">Payment service</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-info">8</div>
                                    <div class="text-muted small">errors</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="source-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code class="small">app/Http/Middleware/AuthMiddleware.php</code>
                                    <div class="text-muted small">Authentication middleware</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-secondary">6</div>
                                    <div class="text-muted small">errors</div>
                                </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let errorTrendsChart, errorCategoriesChart;

$(document).ready(function() {
    initializeCharts();
    loadErrorLogs();
    updateErrorStats();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadErrorLogs();
        updateErrorStats();
    }, 30000);
});

function initializeCharts() {
    // Error Trends Chart
    const ctx1 = document.getElementById('errorTrendsChart').getContext('2d');
    errorTrendsChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Errors',
                data: [],
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Error Categories Chart
    const ctx2 = document.getElementById('errorCategoriesChart').getContext('2d');
    errorCategoriesChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Database', 'Authentication', 'Payment', 'Validation', 'Other'],
            datasets: [{
                data: [35, 25, 20, 15, 5],
                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981', '#6b7280']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Generate initial data
    generateErrorTrendsData();
}

function generateErrorTrendsData() {
    const now = new Date();
    const labels = [];
    const data = [];
    
    for (let i = 23; i >= 0; i--) {
        const time = new Date(now.getTime() - i * 60 * 60 * 1000);
        labels.push(time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
        data.push(Math.floor(Math.random() * 20));
    }
    
    errorTrendsChart.data.labels = labels;
    errorTrendsChart.data.datasets[0].data = data;
    errorTrendsChart.update();
}

function loadErrorLogs() {
    const errors = [
        {
            level: 'ERROR',
            message: 'Database connection failed',
            file: 'Database.php',
            line: 45,
            timestamp: '2 minutes ago'
        },
        {
            level: 'WARNING',
            message: 'Memory usage high',
            file: 'MemoryManager.php',
            line: 23,
            timestamp: '5 minutes ago'
        },
        {
            level: 'ERROR',
            message: 'API rate limit exceeded',
            file: 'ApiController.php',
            line: 67,
            timestamp: '8 minutes ago'
        },
        {
            level: 'INFO',
            message: 'Cache cleared successfully',
            file: 'CacheManager.php',
            line: 12,
            timestamp: '10 minutes ago'
        },
        {
            level: 'ERROR',
            message: 'File upload failed',
            file: 'FileUploader.php',
            line: 89,
            timestamp: '15 minutes ago'
        },
        {
            level: 'WARNING',
            message: 'Slow query detected',
            file: 'QueryBuilder.php',
            line: 156,
            timestamp: '20 minutes ago'
        },
        {
            level: 'ERROR',
            message: 'Authentication failed',
            file: 'AuthController.php',
            line: 34,
            timestamp: '25 minutes ago'
        },
        {
            level: 'INFO',
            message: 'User logged in',
            file: 'AuthController.php',
            line: 78,
            timestamp: '30 minutes ago'
        }
    ];
    
    let tableHtml = '';
    errors.forEach(error => {
        const levelClass = {
            'ERROR': 'danger',
            'WARNING': 'warning',
            'INFO': 'info'
        }[error.level];
        
        tableHtml += `
            <tr>
                <td><span class="badge bg-${levelClass}">${error.level}</span></td>
                <td>
                    <div class="error-message">
                        <div class="fw-bold">${error.message}</div>
                        <small class="text-muted">${error.file}:${error.line}</small>
                    </div>
                </td>
                <td><code>${error.file}</code></td>
                <td><span class="badge bg-light text-dark">${error.line}</span></td>
                <td>${error.timestamp}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewErrorDetails('${error.message}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="markAsResolved('${error.message}')">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteError('${error.message}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    $('#errorLogsTable').html(tableHtml);
}

function updateErrorStats() {
    // Simulate real-time stats
    const criticalErrors = Math.floor(Math.random() * 10);
    const warnings = Math.floor(Math.random() * 20) + 10;
    const infoMessages = Math.floor(Math.random() * 30) + 20;
    const resolvedErrors = Math.floor(Math.random() * 15) + 5;
    
    $('#totalErrors').text(criticalErrors + warnings + infoMessages);
    $('#criticalErrors').text(criticalErrors);
    $('#warnings').text(warnings);
    $('#infoMessages').text(infoMessages);
    $('#resolvedErrors').text(resolvedErrors);
}

function refreshErrorLogs() {
    loadErrorLogs();
    updateErrorStats();
    showAlert('success', 'Error logs refreshed!');
}

function exportErrorLogs() {
    showAlert('info', 'Exporting error logs...');
}

function clearErrorLogs() {
    if (confirm('Are you sure you want to clear all error logs?')) {
        $('#errorLogsTable').html('<tr><td colspan="6" class="text-center text-muted">No error logs found</td></tr>');
        showAlert('success', 'Error logs cleared!');
    }
}

function viewErrorDetails(message) {
    showAlert('info', `Viewing details for: ${message}`);
}

function markAsResolved(message) {
    showAlert('success', `Marked as resolved: ${message}`);
}

function deleteError(message) {
    if (confirm('Are you sure you want to delete this error?')) {
        showAlert('success', `Deleted: ${message}`);
    }
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

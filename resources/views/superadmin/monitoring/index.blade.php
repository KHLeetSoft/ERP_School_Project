@extends('superadmin.app')

@section('title', 'Monitoring & Analytics')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-info text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-chart-line me-3"></i>Monitoring & Analytics
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Real-time system monitoring and performance analytics</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">Live Dashboard</div>
                            <small class="opacity-75">Real-time Data</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-primary fw-bold small mb-1">Active Users</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="activeUsersCount">0</div>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up me-1"></i>+12% from last hour
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users fa-2x text-primary"></i>
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
                            <div class="text-uppercase text-success fw-bold small mb-1">API Requests</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="apiRequestsCount">0</div>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up me-1"></i>+8% from last hour
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-code fa-2x text-success"></i>
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
                            <div class="text-uppercase text-warning fw-bold small mb-1">Server Load</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="serverLoadPercent">0%</div>
                            <div class="text-warning small">
                                <i class="fas fa-server me-1"></i>Moderate load
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-server fa-2x text-warning"></i>
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
                            <div class="text-uppercase text-danger fw-bold small mb-1">Error Rate</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="errorRatePercent">0%</div>
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-triangle me-1"></i>Needs attention
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
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">System Performance Trends</h5>
                    <p class="text-muted mb-0">Real-time performance metrics over time</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="performanceChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Server Health</h5>
                    <p class="text-muted mb-0">Current server status indicators</p>
                </div>
                <div class="card-body p-4">
                    <div class="server-health">
                        <div class="health-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">CPU Usage</span>
                                <span class="text-primary fw-bold" id="cpuUsage">0%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" id="cpuProgress" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="health-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Memory Usage</span>
                                <span class="text-success fw-bold" id="memoryUsage">0%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" id="memoryProgress" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="health-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Disk Usage</span>
                                <span class="text-warning fw-bold" id="diskUsage">0%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" id="diskProgress" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="health-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Network I/O</span>
                                <span class="text-info fw-bold" id="networkUsage">0%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" id="networkProgress" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Data Tables -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Active Users</h5>
                            <p class="text-muted mb-0">Currently online users</p>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshActiveUsers()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>School</th>
                                    <th>Last Activity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="activeUsersTable">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">API Usage</h5>
                            <p class="text-muted mb-0">Recent API requests and responses</p>
                        </div>
                        <button class="btn btn-outline-success btn-sm" onclick="refreshApiUsage()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Endpoint</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody id="apiUsageTable">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Logs -->
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
                            <button class="btn btn-outline-danger btn-sm" onclick="refreshErrorLogs()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="clearErrorLogs()">
                                <i class="fas fa-trash me-1"></i>Clear
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
                                    <th>Time</th>
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

    <!-- Anomaly Detection -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">AI Anomaly Detection</h5>
                            <p class="text-muted mb-0">AI-powered anomaly detection and alerts</p>
                        </div>
                        <button class="btn btn-outline-warning btn-sm" onclick="refreshAnomalies()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="anomaly-card p-3 bg-light rounded mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle text-warning me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Unusual Traffic Spike</h6>
                                        <p class="mb-0 small text-muted">Detected 300% increase in API requests</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="anomaly-card p-3 bg-light rounded mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Response Time Anomaly</h6>
                                        <p class="mb-0 small text-muted">Average response time increased by 200%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="anomaly-card p-3 bg-light rounded mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-danger me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">User Activity Drop</h6>
                                        <p class="mb-0 small text-muted">50% decrease in active users</p>
                                    </div>
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
let performanceChart;

$(document).ready(function() {
    initializeCharts();
    loadActiveUsers();
    loadApiUsage();
    loadErrorLogs();
    updateMetrics();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        updateMetrics();
        loadActiveUsers();
        loadApiUsage();
        loadErrorLogs();
    }, 30000);
});

function initializeCharts() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Active Users',
                data: [],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }, {
                label: 'API Requests',
                data: [],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.1
            }, {
                label: 'Error Rate',
                data: [],
                borderColor: 'rgb(255, 205, 86)',
                backgroundColor: 'rgba(255, 205, 86, 0.1)',
                tension: 0.1
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
    
    // Generate initial data
    generateChartData();
}

function generateChartData() {
    const now = new Date();
    const labels = [];
    const activeUsers = [];
    const apiRequests = [];
    const errorRates = [];
    
    for (let i = 23; i >= 0; i--) {
        const time = new Date(now.getTime() - i * 60 * 60 * 1000);
        labels.push(time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
        activeUsers.push(Math.floor(Math.random() * 100) + 50);
        apiRequests.push(Math.floor(Math.random() * 1000) + 200);
        errorRates.push(Math.random() * 5);
    }
    
    performanceChart.data.labels = labels;
    performanceChart.data.datasets[0].data = activeUsers;
    performanceChart.data.datasets[1].data = apiRequests;
    performanceChart.data.datasets[2].data = errorRates;
    performanceChart.update();
}

function updateMetrics() {
    // Simulate real-time metrics
    $('#activeUsersCount').text(Math.floor(Math.random() * 100) + 50);
    $('#apiRequestsCount').text(Math.floor(Math.random() * 1000) + 200);
    $('#serverLoadPercent').text(Math.floor(Math.random() * 100) + '%');
    $('#errorRatePercent').text((Math.random() * 5).toFixed(2) + '%');
    
    // Update server health
    const cpuUsage = Math.floor(Math.random() * 100);
    const memoryUsage = Math.floor(Math.random() * 100);
    const diskUsage = Math.floor(Math.random() * 100);
    const networkUsage = Math.floor(Math.random() * 100);
    
    $('#cpuUsage').text(cpuUsage + '%');
    $('#cpuProgress').css('width', cpuUsage + '%');
    
    $('#memoryUsage').text(memoryUsage + '%');
    $('#memoryProgress').css('width', memoryUsage + '%');
    
    $('#diskUsage').text(diskUsage + '%');
    $('#diskProgress').css('width', diskUsage + '%');
    
    $('#networkUsage').text(networkUsage + '%');
    $('#networkProgress').css('width', networkUsage + '%');
}

function loadActiveUsers() {
    const users = [
        { name: 'John Doe', school: 'ABC School', lastActivity: '2 minutes ago', status: 'online' },
        { name: 'Jane Smith', school: 'XYZ Academy', lastActivity: '5 minutes ago', status: 'online' },
        { name: 'Mike Johnson', school: 'ABC School', lastActivity: '10 minutes ago', status: 'away' },
        { name: 'Sarah Wilson', school: 'DEF College', lastActivity: '1 minute ago', status: 'online' },
        { name: 'David Brown', school: 'XYZ Academy', lastActivity: '15 minutes ago', status: 'offline' }
    ];
    
    let tableHtml = '';
    users.forEach(user => {
        const statusClass = {
            'online': 'success',
            'away': 'warning',
            'offline': 'secondary'
        }[user.status];
        
        tableHtml += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-${statusClass} rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                        ${user.name}
                    </div>
                </td>
                <td>${user.school}</td>
                <td>${user.lastActivity}</td>
                <td><span class="badge bg-${statusClass}">${user.status.toUpperCase()}</span></td>
            </tr>
        `;
    });
    
    $('#activeUsersTable').html(tableHtml);
}

function loadApiUsage() {
    const requests = [
        { endpoint: '/api/schools', method: 'GET', status: 200, time: '2ms' },
        { endpoint: '/api/users', method: 'POST', status: 201, time: '15ms' },
        { endpoint: '/api/payments', method: 'GET', status: 200, time: '8ms' },
        { endpoint: '/api/reports', method: 'GET', status: 500, time: '120ms' },
        { endpoint: '/api/settings', method: 'PUT', status: 200, time: '25ms' }
    ];
    
    let tableHtml = '';
    requests.forEach(req => {
        const statusClass = req.status >= 200 && req.status < 300 ? 'success' : 
                           req.status >= 400 && req.status < 500 ? 'warning' : 'danger';
        
        tableHtml += `
            <tr>
                <td><code>${req.endpoint}</code></td>
                <td><span class="badge bg-${req.method === 'GET' ? 'info' : req.method === 'POST' ? 'success' : 'warning'}">${req.method}</span></td>
                <td><span class="badge bg-${statusClass}">${req.status}</span></td>
                <td>${req.time}</td>
            </tr>
        `;
    });
    
    $('#apiUsageTable').html(tableHtml);
}

function loadErrorLogs() {
    const errors = [
        { level: 'ERROR', message: 'Database connection failed', file: 'Database.php:45', time: '2 minutes ago' },
        { level: 'WARNING', message: 'Memory usage high', file: 'MemoryManager.php:23', time: '5 minutes ago' },
        { level: 'ERROR', message: 'API rate limit exceeded', file: 'ApiController.php:67', time: '8 minutes ago' },
        { level: 'INFO', message: 'Cache cleared successfully', file: 'CacheManager.php:12', time: '10 minutes ago' },
        { level: 'ERROR', message: 'File upload failed', file: 'FileUploader.php:89', time: '15 minutes ago' }
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
                <td>${error.message}</td>
                <td><code>${error.file}</code></td>
                <td>${error.time}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="viewErrorDetails('${error.message}')">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    $('#errorLogsTable').html(tableHtml);
}

function refreshActiveUsers() {
    loadActiveUsers();
    showAlert('success', 'Active users data refreshed!');
}

function refreshApiUsage() {
    loadApiUsage();
    showAlert('success', 'API usage data refreshed!');
}

function refreshErrorLogs() {
    loadErrorLogs();
    showAlert('success', 'Error logs refreshed!');
}

function refreshAnomalies() {
    showAlert('info', 'Anomaly detection refreshed!');
}

function clearErrorLogs() {
    $('#errorLogsTable').html('<tr><td colspan="5" class="text-center text-muted">No error logs found</td></tr>');
    showAlert('success', 'Error logs cleared!');
}

function viewErrorDetails(message) {
    alert('Error Details:\n\n' + message);
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
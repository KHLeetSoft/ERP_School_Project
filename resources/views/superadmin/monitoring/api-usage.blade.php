@extends('superadmin.app')

@section('title', 'API Usage Monitoring')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-success text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-code me-3"></i>API Usage Monitoring
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Track API requests, responses, and performance metrics</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0" id="totalApiRequests">0</div>
                            <small class="opacity-75">Requests Today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Metrics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-success fw-bold small mb-1">Success Rate</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="successRate">0%</div>
                            <div class="text-success small">
                                <i class="fas fa-check-circle me-1"></i>API Health
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
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-info fw-bold small mb-1">Avg Response</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="avgResponseTime">0ms</div>
                            <div class="text-info small">
                                <i class="fas fa-tachometer-alt me-1"></i>Performance
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-tachometer-alt fa-2x text-info"></i>
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
                            <div class="text-uppercase text-warning fw-bold small mb-1">Error Rate</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="errorRate">0%</div>
                            <div class="text-warning small">
                                <i class="fas fa-exclamation-triangle me-1"></i>Issues
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
                            <div class="text-uppercase text-primary fw-bold small mb-1">Peak RPS</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="peakRps">0</div>
                            <div class="text-primary small">
                                <i class="fas fa-chart-line me-1"></i>Requests/sec
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-chart-line fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Usage Chart -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">API Request Trends</h5>
                    <p class="text-muted mb-0">24-hour API usage patterns</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="apiUsageChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Top Endpoints</h5>
                    <p class="text-muted mb-0">Most frequently used API endpoints</p>
                </div>
                <div class="card-body p-4">
                    <div class="endpoint-list">
                        <div class="endpoint-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code class="small">/api/schools</code>
                                    <div class="text-muted small">Schools management</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">1,234</div>
                                    <div class="text-success small">98.5% success</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="endpoint-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code class="small">/api/users</code>
                                    <div class="text-muted small">User management</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">987</div>
                                    <div class="text-success small">99.2% success</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="endpoint-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code class="small">/api/payments</code>
                                    <div class="text-muted small">Payment processing</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">756</div>
                                    <div class="text-warning small">95.8% success</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="endpoint-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <code class="small">/api/reports</code>
                                    <div class="text-muted small">Report generation</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">432</div>
                                    <div class="text-success small">97.1% success</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Requests Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Recent API Requests</h5>
                            <p class="text-muted mb-0">Live API request logs and responses</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshApiRequests()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="exportApiData()">
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
                                    <th>Endpoint</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Response Time</th>
                                    <th>IP Address</th>
                                    <th>Timestamp</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="apiRequestsTable">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Performance Metrics -->
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Response Time Distribution</h5>
                    <p class="text-muted mb-0">API response time analysis</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="responseTimeChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Error Analysis</h5>
                    <p class="text-muted mb-0">API error types and frequency</p>
                </div>
                <div class="card-body p-4">
                    <div class="error-analysis">
                        <div class="error-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">500 Internal Server Error</span>
                                    <div class="text-muted small">Database connection issues</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-danger">23</div>
                                    <div class="text-muted small">occurrences</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="error-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">404 Not Found</span>
                                    <div class="text-muted small">Invalid endpoint requests</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-warning">15</div>
                                    <div class="text-muted small">occurrences</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="error-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">401 Unauthorized</span>
                                    <div class="text-muted small">Authentication failures</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-info">8</div>
                                    <div class="text-muted small">occurrences</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="error-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">429 Too Many Requests</span>
                                    <div class="text-muted small">Rate limit exceeded</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-secondary">5</div>
                                    <div class="text-muted small">occurrences</div>
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
let apiUsageChart, responseTimeChart;

$(document).ready(function() {
    initializeCharts();
    loadApiRequests();
    updateApiMetrics();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadApiRequests();
        updateApiMetrics();
    }, 30000);
});

function initializeCharts() {
    // API Usage Chart
    const ctx1 = document.getElementById('apiUsageChart').getContext('2d');
    apiUsageChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'API Requests',
                data: [],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
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
    
    // Response Time Chart
    const ctx2 = document.getElementById('responseTimeChart').getContext('2d');
    responseTimeChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Fast (<100ms)', 'Medium (100-500ms)', 'Slow (>500ms)'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Generate initial data
    generateApiChartData();
}

function generateApiChartData() {
    const now = new Date();
    const labels = [];
    const data = [];
    
    for (let i = 23; i >= 0; i--) {
        const time = new Date(now.getTime() - i * 60 * 60 * 1000);
        labels.push(time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
        data.push(Math.floor(Math.random() * 100) + 50);
    }
    
    apiUsageChart.data.labels = labels;
    apiUsageChart.data.datasets[0].data = data;
    apiUsageChart.update();
}

function loadApiRequests() {
    const requests = [
        {
            endpoint: '/api/schools',
            method: 'GET',
            status: 200,
            responseTime: '45ms',
            ipAddress: '192.168.1.100',
            timestamp: '2 minutes ago'
        },
        {
            endpoint: '/api/users',
            method: 'POST',
            status: 201,
            responseTime: '120ms',
            ipAddress: '192.168.1.101',
            timestamp: '5 minutes ago'
        },
        {
            endpoint: '/api/payments',
            method: 'GET',
            status: 200,
            responseTime: '89ms',
            ipAddress: '192.168.1.102',
            timestamp: '8 minutes ago'
        },
        {
            endpoint: '/api/reports',
            method: 'GET',
            status: 500,
            responseTime: '1200ms',
            ipAddress: '192.168.1.103',
            timestamp: '12 minutes ago'
        },
        {
            endpoint: '/api/settings',
            method: 'PUT',
            status: 200,
            responseTime: '67ms',
            ipAddress: '192.168.1.104',
            timestamp: '15 minutes ago'
        }
    ];
    
    let tableHtml = '';
    requests.forEach(req => {
        const statusClass = req.status >= 200 && req.status < 300 ? 'success' : 
                           req.status >= 400 && req.status < 500 ? 'warning' : 'danger';
        
        const methodClass = {
            'GET': 'info',
            'POST': 'success',
            'PUT': 'warning',
            'DELETE': 'danger'
        }[req.method];
        
        tableHtml += `
            <tr>
                <td><code>${req.endpoint}</code></td>
                <td><span class="badge bg-${methodClass}">${req.method}</span></td>
                <td><span class="badge bg-${statusClass}">${req.status}</span></td>
                <td>${req.responseTime}</td>
                <td><code>${req.ipAddress}</code></td>
                <td>${req.timestamp}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="viewRequestDetails('${req.endpoint}')">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    $('#apiRequestsTable').html(tableHtml);
}

function updateApiMetrics() {
    // Simulate real-time metrics
    const totalRequests = Math.floor(Math.random() * 1000) + 500;
    const successRate = (Math.random() * 10 + 90).toFixed(1);
    const avgResponseTime = Math.floor(Math.random() * 200) + 50;
    const errorRate = (Math.random() * 5).toFixed(1);
    const peakRps = Math.floor(Math.random() * 50) + 20;
    
    $('#totalApiRequests').text(totalRequests.toLocaleString());
    $('#successRate').text(successRate + '%');
    $('#avgResponseTime').text(avgResponseTime + 'ms');
    $('#errorRate').text(errorRate + '%');
    $('#peakRps').text(peakRps);
}

function refreshApiRequests() {
    loadApiRequests();
    updateApiMetrics();
    showAlert('success', 'API requests data refreshed!');
}

function exportApiData() {
    showAlert('info', 'Exporting API data...');
}

function viewRequestDetails(endpoint) {
    showAlert('info', `Viewing details for ${endpoint}`);
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

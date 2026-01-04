@extends('superadmin.app')

@section('title', 'AI Anomaly Detection')

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
                                <i class="fas fa-brain me-3"></i>AI Anomaly Detection
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Intelligent pattern recognition and anomaly detection</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0" id="anomaliesDetected">0</div>
                            <small class="opacity-75">Anomalies Found</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anomaly Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-danger fw-bold small mb-1">Critical Anomalies</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="criticalAnomalies">0</div>
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-circle me-1"></i>Immediate attention
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
                            <div class="text-uppercase text-warning fw-bold small mb-1">Suspicious Patterns</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="suspiciousPatterns">0</div>
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
                            <div class="text-uppercase text-info fw-bold small mb-1">False Positives</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="falsePositives">0</div>
                            <div class="text-info small">
                                <i class="fas fa-times-circle me-1"></i>Filtered out
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-times-circle fa-2x text-info"></i>
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
                            <div class="text-uppercase text-success fw-bold small mb-1">Accuracy Rate</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="accuracyRate">0%</div>
                            <div class="text-success small">
                                <i class="fas fa-chart-line me-1"></i>AI Performance
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-chart-line fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anomaly Detection Chart -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Anomaly Detection Timeline</h5>
                    <p class="text-muted mb-0">AI-detected anomalies over time</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="anomalyChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Detection Categories</h5>
                    <p class="text-muted mb-0">Types of anomalies detected</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="anomalyCategoriesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detected Anomalies Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Detected Anomalies</h5>
                            <p class="text-muted mb-0">AI-detected suspicious activities and patterns</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshAnomalies()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="exportAnomalies()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="trainModel()">
                                <i class="fas fa-brain me-1"></i>Train Model
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
                                    <th>Confidence</th>
                                    <th>Detected</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="anomaliesTable">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Model Performance -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Model Performance</h5>
                    <p class="text-muted mb-0">AI model accuracy and learning metrics</p>
                </div>
                <div class="card-body p-4">
                    <div class="model-metrics">
                        <div class="metric-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Precision</span>
                                <span class="text-success fw-bold">94.2%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 94.2%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Recall</span>
                                <span class="text-info fw-bold">89.7%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: 89.7%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">F1 Score</span>
                                <span class="text-primary fw-bold">91.9%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: 91.9%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Training Data</span>
                                <span class="text-warning fw-bold">1.2M samples</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Learning Progress</h5>
                    <p class="text-muted mb-0">AI model learning and adaptation</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="learningChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Anomaly Patterns -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Common Anomaly Patterns</h5>
                    <p class="text-muted mb-0">Frequently detected suspicious patterns</p>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="pattern-card p-3 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-user-times text-danger me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Unusual Login Patterns</h6>
                                        <p class="mb-1 small text-muted">Multiple failed login attempts from same IP</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-danger">High Risk</span>
                                            <small class="text-muted">23 detections</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="pattern-card p-3 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-chart-line text-warning me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Traffic Spikes</h6>
                                        <p class="mb-1 small text-muted">Sudden increase in API requests</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-warning">Medium Risk</span>
                                            <small class="text-muted">15 detections</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="pattern-card p-3 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-database text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Data Access Anomalies</h6>
                                        <p class="mb-1 small text-muted">Unusual data access patterns</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-info">Low Risk</span>
                                            <small class="text-muted">8 detections</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="pattern-card p-3 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-clock text-secondary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Time-based Anomalies</h6>
                                        <p class="mb-1 small text-muted">Activity at unusual hours</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-secondary">Info</span>
                                            <small class="text-muted">12 detections</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="pattern-card p-3 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-shield-alt text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Security Events</h6>
                                        <p class="mb-1 small text-muted">Potential security breaches</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-success">Resolved</span>
                                            <small class="text-muted">5 detections</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="pattern-card p-3 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-exclamation-triangle text-warning me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">System Performance</h6>
                                        <p class="mb-1 small text-muted">Unusual system behavior</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-warning">Monitoring</span>
                                            <small class="text-muted">18 detections</small>
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
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let anomalyChart, anomalyCategoriesChart, learningChart;

$(document).ready(function() {
    initializeCharts();
    loadAnomalies();
    updateAnomalyStats();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadAnomalies();
        updateAnomalyStats();
    }, 30000);
});

function initializeCharts() {
    // Anomaly Detection Chart
    const ctx1 = document.getElementById('anomalyChart').getContext('2d');
    anomalyChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Anomalies Detected',
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
    
    // Anomaly Categories Chart
    const ctx2 = document.getElementById('anomalyCategoriesChart').getContext('2d');
    anomalyCategoriesChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Login Anomalies', 'Traffic Spikes', 'Data Access', 'Time-based', 'Security', 'Performance'],
            datasets: [{
                data: [25, 20, 15, 12, 18, 10],
                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#6b7280', '#10b981', '#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Learning Chart
    const ctx3 = document.getElementById('learningChart').getContext('2d');
    learningChart = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
            datasets: [{
                label: 'Accuracy',
                data: [85, 87, 89, 91, 93, 94],
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
    
    // Generate initial data
    generateAnomalyData();
}

function generateAnomalyData() {
    const now = new Date();
    const labels = [];
    const data = [];
    
    for (let i = 23; i >= 0; i--) {
        const time = new Date(now.getTime() - i * 60 * 60 * 1000);
        labels.push(time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
        data.push(Math.floor(Math.random() * 10));
    }
    
    anomalyChart.data.labels = labels;
    anomalyChart.data.datasets[0].data = data;
    anomalyChart.update();
}

function loadAnomalies() {
    const anomalies = [
        {
            type: 'Login Anomaly',
            severity: 'high',
            description: 'Multiple failed login attempts from IP 192.168.1.100',
            confidence: 95,
            detected: '5 minutes ago',
            status: 'investigating'
        },
        {
            type: 'Traffic Spike',
            severity: 'medium',
            description: '300% increase in API requests detected',
            confidence: 87,
            detected: '15 minutes ago',
            status: 'monitoring'
        },
        {
            type: 'Data Access Anomaly',
            severity: 'low',
            description: 'Unusual data access pattern from user admin@school.com',
            confidence: 72,
            detected: '1 hour ago',
            status: 'resolved'
        },
        {
            type: 'Time-based Anomaly',
            severity: 'info',
            description: 'Activity detected at unusual hours (3:00 AM)',
            confidence: 68,
            detected: '2 hours ago',
            status: 'investigating'
        },
        {
            type: 'Security Event',
            severity: 'critical',
            description: 'Potential SQL injection attempt detected',
            confidence: 98,
            detected: '3 hours ago',
            status: 'blocked'
        },
        {
            type: 'Performance Anomaly',
            severity: 'medium',
            description: 'Response time increased by 200%',
            confidence: 83,
            detected: '4 hours ago',
            status: 'monitoring'
        }
    ];
    
    let tableHtml = '';
    anomalies.forEach(anomaly => {
        const severityClass = {
            'critical': 'danger',
            'high': 'warning',
            'medium': 'info',
            'low': 'secondary',
            'info': 'primary'
        }[anomaly.severity];
        
        const statusClass = {
            'investigating': 'warning',
            'monitoring': 'info',
            'resolved': 'success',
            'blocked': 'danger'
        }[anomaly.status];
        
        const confidenceColor = anomaly.confidence >= 90 ? 'success' : 
                               anomaly.confidence >= 70 ? 'warning' : 'danger';
        
        tableHtml += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-${severityClass} me-2"></i>
                        ${anomaly.type}
                    </div>
                </td>
                <td><span class="badge bg-${severityClass}">${anomaly.severity.toUpperCase()}</span></td>
                <td>${anomaly.description}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="progress me-2" style="width: 60px; height: 6px;">
                            <div class="progress-bar bg-${confidenceColor}" style="width: ${anomaly.confidence}%"></div>
                        </div>
                        <span class="small">${anomaly.confidence}%</span>
                    </div>
                </td>
                <td>${anomaly.detected}</td>
                <td><span class="badge bg-${statusClass}">${anomaly.status.toUpperCase()}</span></td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewAnomalyDetails('${anomaly.type}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="markAsResolved('${anomaly.type}')">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="investigateAnomaly('${anomaly.type}')">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    $('#anomaliesTable').html(tableHtml);
}

function updateAnomalyStats() {
    // Simulate real-time stats
    const criticalAnomalies = Math.floor(Math.random() * 5);
    const suspiciousPatterns = Math.floor(Math.random() * 15) + 5;
    const falsePositives = Math.floor(Math.random() * 10) + 2;
    const accuracyRate = (Math.random() * 10 + 85).toFixed(1);
    
    $('#anomaliesDetected').text(criticalAnomalies + suspiciousPatterns);
    $('#criticalAnomalies').text(criticalAnomalies);
    $('#suspiciousPatterns').text(suspiciousPatterns);
    $('#falsePositives').text(falsePositives);
    $('#accuracyRate').text(accuracyRate + '%');
}

function refreshAnomalies() {
    loadAnomalies();
    updateAnomalyStats();
    showAlert('success', 'Anomalies refreshed!');
}

function exportAnomalies() {
    showAlert('info', 'Exporting anomaly data...');
}

function trainModel() {
    showAlert('info', 'Training AI model with new data...');
}

function viewAnomalyDetails(type) {
    showAlert('info', `Viewing details for ${type}`);
}

function markAsResolved(type) {
    showAlert('success', `Marked as resolved: ${type}`);
}

function investigateAnomaly(type) {
    showAlert('warning', `Investigating ${type}...`);
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

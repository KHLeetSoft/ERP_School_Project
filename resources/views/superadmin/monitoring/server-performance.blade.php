@extends('superadmin.app')

@section('title', 'Server Performance Monitoring')

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
                                <i class="fas fa-server me-3"></i>Server Performance Monitoring
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Real-time server health and performance metrics</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0" id="serverStatus">Online</div>
                            <small class="opacity-75">System Status</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Metrics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-primary fw-bold small mb-1">CPU Usage</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="cpuUsage">0%</div>
                            <div class="text-primary small">
                                <i class="fas fa-microchip me-1"></i>Processor
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-microchip fa-2x text-primary"></i>
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
                            <div class="text-uppercase text-success fw-bold small mb-1">Memory Usage</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="memoryUsage">0%</div>
                            <div class="text-success small">
                                <i class="fas fa-memory me-1"></i>RAM
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-memory fa-2x text-success"></i>
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
                            <div class="text-uppercase text-warning fw-bold small mb-1">Disk Usage</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="diskUsage">0%</div>
                            <div class="text-warning small">
                                <i class="fas fa-hdd me-1"></i>Storage
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-hdd fa-2x text-warning"></i>
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
                            <div class="text-uppercase text-info fw-bold small mb-1">Network I/O</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="networkUsage">0%</div>
                            <div class="text-info small">
                                <i class="fas fa-network-wired me-1"></i>Bandwidth
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-network-wired fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
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
                    <h5 class="mb-1 fw-bold">Resource Utilization</h5>
                    <p class="text-muted mb-0">Current system resource usage</p>
                </div>
                <div class="card-body p-4">
                    <div class="resource-usage">
                        <div class="resource-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">CPU Cores</span>
                                <span class="text-primary fw-bold">4/4</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" id="cpuProgress" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="resource-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Memory</span>
                                <span class="text-success fw-bold">8GB/16GB</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" id="memoryProgress" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="resource-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Disk Space</span>
                                <span class="text-warning fw-bold">500GB/1TB</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" id="diskProgress" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="resource-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Network</span>
                                <span class="text-info fw-bold">100Mbps</span>
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

    <!-- Server Processes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Running Processes</h5>
                            <p class="text-muted mb-0">Top resource-consuming processes</p>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshProcesses()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Process</th>
                                    <th>PID</th>
                                    <th>CPU %</th>
                                    <th>Memory %</th>
                                    <th>Status</th>
                                    <th>Uptime</th>
                                </tr>
                            </thead>
                            <tbody id="processesTable">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Alerts -->
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">System Alerts</h5>
                    <p class="text-muted mb-0">Current system warnings and alerts</p>
                </div>
                <div class="card-body p-4">
                    <div class="alerts-list">
                        <div class="alert-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle text-warning me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">High CPU Usage</h6>
                                    <p class="mb-1 small text-muted">CPU usage has exceeded 80% for the last 5 minutes</p>
                                    <small class="text-warning">2 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle text-info me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Memory Usage Normal</h6>
                                    <p class="mb-1 small text-muted">Memory usage is within acceptable limits</p>
                                    <small class="text-info">5 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Disk Space OK</h6>
                                    <p class="mb-1 small text-muted">Available disk space is sufficient</p>
                                    <small class="text-success">10 minutes ago</small>
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
                    <h5 class="mb-1 fw-bold">Quick Actions</h5>
                    <p class="text-muted mb-0">Common server management tasks</p>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="restartServices()">
                            <i class="fas fa-redo me-2"></i>Restart Services
                        </button>
                        <button class="btn btn-outline-success" onclick="clearCache()">
                            <i class="fas fa-broom me-2"></i>Clear System Cache
                        </button>
                        <button class="btn btn-outline-warning" onclick="optimizeDatabase()">
                            <i class="fas fa-database me-2"></i>Optimize Database
                        </button>
                        <button class="btn btn-outline-info" onclick="checkUpdates()">
                            <i class="fas fa-sync-alt me-2"></i>Check for Updates
                        </button>
                        <button class="btn btn-outline-danger" onclick="emergencyRestart()">
                            <i class="fas fa-power-off me-2"></i>Emergency Restart
                        </button>
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
    initializeChart();
    loadProcesses();
    updateServerMetrics();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        updateServerMetrics();
        loadProcesses();
    }, 30000);
});

function initializeChart() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'CPU Usage (%)',
                data: [],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }, {
                label: 'Memory Usage (%)',
                data: [],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.1
            }, {
                label: 'Disk Usage (%)',
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
                    beginAtZero: true,
                    max: 100
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
    const cpuData = [];
    const memoryData = [];
    const diskData = [];
    
    for (let i = 23; i >= 0; i--) {
        const time = new Date(now.getTime() - i * 60 * 60 * 1000);
        labels.push(time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
        cpuData.push(Math.floor(Math.random() * 80) + 10);
        memoryData.push(Math.floor(Math.random() * 70) + 20);
        diskData.push(Math.floor(Math.random() * 30) + 40);
    }
    
    performanceChart.data.labels = labels;
    performanceChart.data.datasets[0].data = cpuData;
    performanceChart.data.datasets[1].data = memoryData;
    performanceChart.data.datasets[2].data = diskData;
    performanceChart.update();
}

function loadProcesses() {
    const processes = [
        {
            name: 'php-fpm',
            pid: 1234,
            cpu: 15.2,
            memory: 8.5,
            status: 'running',
            uptime: '2d 5h 30m'
        },
        {
            name: 'nginx',
            pid: 5678,
            cpu: 2.1,
            memory: 12.3,
            status: 'running',
            uptime: '5d 12h 15m'
        },
        {
            name: 'mysql',
            pid: 9012,
            cpu: 8.7,
            memory: 25.8,
            status: 'running',
            uptime: '1d 8h 45m'
        },
        {
            name: 'redis',
            pid: 3456,
            cpu: 1.5,
            memory: 3.2,
            status: 'running',
            uptime: '3d 2h 10m'
        },
        {
            name: 'cron',
            pid: 7890,
            cpu: 0.8,
            memory: 1.1,
            status: 'running',
            uptime: '7d 1h 20m'
        }
    ];
    
    let tableHtml = '';
    processes.forEach(process => {
        const statusClass = process.status === 'running' ? 'success' : 'danger';
        
        tableHtml += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-${statusClass} rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                        <span class="fw-bold">${process.name}</span>
                    </div>
                </td>
                <td><code>${process.pid}</code></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="progress me-2" style="width: 60px; height: 6px;">
                            <div class="progress-bar bg-primary" style="width: ${process.cpu}%"></div>
                        </div>
                        <span class="small">${process.cpu}%</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="progress me-2" style="width: 60px; height: 6px;">
                            <div class="progress-bar bg-success" style="width: ${process.memory}%"></div>
                        </div>
                        <span class="small">${process.memory}%</span>
                    </div>
                </td>
                <td><span class="badge bg-${statusClass}">${process.status.toUpperCase()}</span></td>
                <td>${process.uptime}</td>
            </tr>
        `;
    });
    
    $('#processesTable').html(tableHtml);
}

function updateServerMetrics() {
    // Simulate real-time metrics
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
    
    // Update server status
    if (cpuUsage > 90 || memoryUsage > 90 || diskUsage > 90) {
        $('#serverStatus').text('Warning').removeClass('text-success').addClass('text-warning');
    } else {
        $('#serverStatus').text('Online').removeClass('text-warning').addClass('text-success');
    }
}

function refreshProcesses() {
    loadProcesses();
    showAlert('success', 'Processes refreshed!');
}

function restartServices() {
    showAlert('info', 'Restarting services...');
}

function clearCache() {
    showAlert('info', 'Clearing system cache...');
}

function optimizeDatabase() {
    showAlert('info', 'Optimizing database...');
}

function checkUpdates() {
    showAlert('info', 'Checking for updates...');
}

function emergencyRestart() {
    if (confirm('Are you sure you want to perform an emergency restart?')) {
        showAlert('warning', 'Emergency restart initiated...');
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

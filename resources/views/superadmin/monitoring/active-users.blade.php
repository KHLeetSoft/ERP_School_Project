@extends('superadmin.app')

@section('title', 'Active Users Monitoring')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-users me-3"></i>Active Users Monitoring
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Real-time user activity and engagement tracking</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0" id="totalActiveUsers">0</div>
                            <small class="opacity-75">Currently Online</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Activity Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">User Activity Over Time</h5>
                    <p class="text-muted mb-0">24-hour user activity trends</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="userActivityChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-success fw-bold small mb-1">Online Now</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="onlineUsers">0</div>
                            <div class="text-success small">
                                <i class="fas fa-circle me-1"></i>Active
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-check fa-2x text-success"></i>
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
                            <div class="text-uppercase text-warning fw-bold small mb-1">Away</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="awayUsers">0</div>
                            <div class="text-warning small">
                                <i class="fas fa-clock me-1"></i>Idle
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-user-clock fa-2x text-warning"></i>
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
                            <div class="text-uppercase text-info fw-bold small mb-1">Today's Peak</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="peakUsers">0</div>
                            <div class="text-info small">
                                <i class="fas fa-chart-line me-1"></i>Peak activity
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-chart-line fa-2x text-info"></i>
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
                            <div class="text-uppercase text-primary fw-bold small mb-1">Avg Session</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="avgSession">0m</div>
                            <div class="text-primary small">
                                <i class="fas fa-stopwatch me-1"></i>Duration
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-stopwatch fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Currently Active Users</h5>
                            <p class="text-muted mb-0">Real-time user activity details</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshActiveUsers()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="exportUserData()">
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
                                    <th>User</th>
                                    <th>School</th>
                                    <th>Role</th>
                                    <th>Last Activity</th>
                                    <th>Status</th>
                                    <th>IP Address</th>
                                    <th>Actions</th>
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
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let userActivityChart;

$(document).ready(function() {
    initializeChart();
    loadActiveUsers();
    updateUserStats();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadActiveUsers();
        updateUserStats();
    }, 30000);
});

function initializeChart() {
    const ctx = document.getElementById('userActivityChart').getContext('2d');
    userActivityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Active Users',
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
    
    // Generate initial data
    generateChartData();
}

function generateChartData() {
    const now = new Date();
    const labels = [];
    const data = [];
    
    for (let i = 23; i >= 0; i--) {
        const time = new Date(now.getTime() - i * 60 * 60 * 1000);
        labels.push(time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
        data.push(Math.floor(Math.random() * 50) + 20);
    }
    
    userActivityChart.data.labels = labels;
    userActivityChart.data.datasets[0].data = data;
    userActivityChart.update();
}

function loadActiveUsers() {
    const users = [
        {
            name: 'John Doe',
            school: 'ABC School',
            role: 'Admin',
            lastActivity: '2 minutes ago',
            status: 'online',
            ipAddress: '192.168.1.100'
        },
        {
            name: 'Jane Smith',
            school: 'XYZ Academy',
            role: 'Teacher',
            lastActivity: '5 minutes ago',
            status: 'online',
            ipAddress: '192.168.1.101'
        },
        {
            name: 'Mike Johnson',
            school: 'ABC School',
            role: 'Student',
            lastActivity: '10 minutes ago',
            status: 'away',
            ipAddress: '192.168.1.102'
        },
        {
            name: 'Sarah Wilson',
            school: 'DEF College',
            role: 'Admin',
            lastActivity: '1 minute ago',
            status: 'online',
            ipAddress: '192.168.1.103'
        },
        {
            name: 'David Brown',
            school: 'XYZ Academy',
            role: 'Teacher',
            lastActivity: '15 minutes ago',
            status: 'offline',
            ipAddress: '192.168.1.104'
        }
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
                        <div>
                            <div class="fw-bold">${user.name}</div>
                            <small class="text-muted">${user.email || 'user@example.com'}</small>
                        </div>
                    </div>
                </td>
                <td>${user.school}</td>
                <td><span class="badge bg-info">${user.role}</span></td>
                <td>${user.lastActivity}</td>
                <td><span class="badge bg-${statusClass}">${user.status.toUpperCase()}</span></td>
                <td><code>${user.ipAddress}</code></td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewUserDetails('${user.name}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="sendMessage('${user.name}')">
                            <i class="fas fa-comment"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    $('#activeUsersTable').html(tableHtml);
}

function updateUserStats() {
    // Simulate real-time stats
    const onlineUsers = Math.floor(Math.random() * 20) + 10;
    const awayUsers = Math.floor(Math.random() * 10) + 5;
    const peakUsers = Math.floor(Math.random() * 30) + 50;
    const avgSession = Math.floor(Math.random() * 60) + 30;
    
    $('#totalActiveUsers').text(onlineUsers + awayUsers);
    $('#onlineUsers').text(onlineUsers);
    $('#awayUsers').text(awayUsers);
    $('#peakUsers').text(peakUsers);
    $('#avgSession').text(avgSession + 'm');
}

function refreshActiveUsers() {
    loadActiveUsers();
    updateUserStats();
    showAlert('success', 'Active users data refreshed!');
}

function exportUserData() {
    showAlert('info', 'Exporting user data...');
}

function viewUserDetails(userName) {
    showAlert('info', `Viewing details for ${userName}`);
}

function sendMessage(userName) {
    showAlert('info', `Sending message to ${userName}`);
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

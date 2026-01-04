@extends('superadmin.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="container-fluid p-0">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-crown me-3"></i>Super Admin Dashboard
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Complete system overview and management control center</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">{{ now()->format('l, F j, Y') }}</div>
                            <small class="opacity-75">{{ now()->format('h:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-primary fw-bold small mb-1">Total Schools</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="totalSchools">0</div>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up me-1"></i>+12% this month
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-school fa-2x text-primary"></i>
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
                            <div class="text-uppercase text-success fw-bold small mb-1">Active Plans</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="activePlans">0</div>
                            <div class="text-success small">
                                <i class="fas fa-check-circle me-1"></i>98% uptime
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
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-warning fw-bold small mb-1">Monthly Revenue</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="monthlyRevenue">₹0</div>
                            <div class="text-warning small">
                                <i class="fas fa-arrow-up me-1"></i>+8.5% vs last month
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-rupee-sign fa-2x text-warning"></i>
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
                            <div class="text-uppercase text-info fw-bold small mb-1">Active Users</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="activeUsers">0</div>
                            <div class="text-info small">
                                <i class="fas fa-users me-1"></i>Across all schools
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health & AI Insights Row -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">System Health & Performance</h5>
                            <p class="text-muted mb-0">Real-time system monitoring and analytics</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshSystemHealth()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Server Uptime -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-server fa-lg text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Server Uptime</h6>
                                    <div class="h4 mb-0 text-success" id="serverUptime">99.9%</div>
                                    <small class="text-muted">Last 30 days</small>
                                </div>
                            </div>
                        </div>
                        <!-- Storage Usage -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-hdd fa-lg text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Storage Usage</h6>
                                    <div class="h4 mb-0 text-warning" id="storageUsage">65%</div>
                                    <small class="text-muted">2.1TB of 3.2TB used</small>
                                </div>
                            </div>
                        </div>
                        <!-- API Response Time -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-bolt fa-lg text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">API Response</h6>
                                    <div class="h4 mb-0 text-info" id="apiResponse">120ms</div>
                                    <small class="text-muted">Average response time</small>
                                </div>
                            </div>
                        </div>
                        <!-- Error Rate -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-exclamation-triangle fa-lg text-danger"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Error Rate</h6>
                                    <div class="h4 mb-0 text-danger" id="errorRate">0.02%</div>
                                    <small class="text-muted">Last 24 hours</small>
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
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-robot me-2 text-primary"></i>AI Insights
                    </h5>
                    <p class="text-muted mb-0">Smart predictions and recommendations</p>
                </div>
                <div class="card-body p-4">
                    <div class="ai-insights">
                        <div class="ai-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-chart-line text-primary me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Revenue Forecast</h6>
                                    <p class="mb-1 small text-muted">Next quarter predicted revenue: <strong>₹2.4M</strong></p>
                                    <span class="badge bg-success">+15% growth</span>
                                </div>
                            </div>
                        </div>
                        <div class="ai-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-arrow-up text-warning me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Upgrade Suggestions</h6>
                                    <p class="mb-1 small text-muted">3 schools ready for plan upgrade</p>
                                    <span class="badge bg-warning">High potential</span>
                                </div>
                            </div>
                        </div>
                        <div class="ai-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-circle text-danger me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Churn Alert</h6>
                                    <p class="mb-1 small text-muted">2 schools showing low activity</p>
                                    <span class="badge bg-danger">Action needed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart & Recent Activity Row -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Revenue Analytics</h5>
                            <p class="text-muted mb-0">Monthly revenue trends and forecasting</p>
                        </div>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="revenuePeriod">
                                <option value="6months">Last 6 Months</option>
                                <option value="1year">Last Year</option>
                                <option value="2years">Last 2 Years</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Recent Activity</h5>
                    <p class="text-muted mb-0">Latest system events and updates</p>
                </div>
                <div class="card-body p-0">
                    <div class="activity-feed">
                        <div class="activity-item p-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-plus text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">New School Registered</h6>
                                    <p class="mb-1 small text-muted">Delhi Public School joined the platform</p>
                                    <small class="text-muted">2 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-credit-card text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Payment Received</h6>
                                    <p class="mb-1 small text-muted">₹25,000 from ABC School</p>
                                    <small class="text-muted">15 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-arrow-up text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Plan Upgrade</h6>
                                    <p class="mb-1 small text-muted">XYZ School upgraded to Premium</p>
                                    <small class="text-muted">1 hour ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-cog text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">System Update</h6>
                                    <p class="mb-1 small text-muted">New features deployed successfully</p>
                                    <small class="text-muted">3 hours ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & AI Tools Row -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Quick Actions</h5>
                    <p class="text-muted mb-0">Frequently used administrative tasks</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('superadmin.schools.index') }}" class="btn btn-outline-primary w-100 p-3 text-start">
                                <i class="fas fa-school me-2"></i>
                                <div>
                                    <div class="fw-bold">Manage Schools</div>
                                    <small class="text-muted">View and manage all schools</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('superadmin.payment.plans.index') }}" class="btn btn-outline-success w-100 p-3 text-start">
                                <i class="fas fa-chart-line me-2"></i>
                                <div>
                                    <div class="fw-bold">Plan Management</div>
                                    <small class="text-muted">Create and edit subscription plans</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('superadmin.payment.reports.index') }}" class="btn btn-outline-warning w-100 p-3 text-start">
                                <i class="fas fa-chart-bar me-2"></i>
                                <div>
                                    <div class="fw-bold">Analytics</div>
                                    <small class="text-muted">View detailed reports and insights</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('superadmin.settings.index') }}" class="btn btn-outline-info w-100 p-3 text-start">
                                <i class="fas fa-cog me-2"></i>
                                <div>
                                    <div class="fw-bold">System Settings</div>
                                    <small class="text-muted">Configure system parameters</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-robot me-2 text-primary"></i>AI Assistant
                    </h5>
                    <p class="text-muted mb-0">Ask questions in natural language</p>
                </div>
                <div class="card-body p-4">
                    <div class="ai-chat">
                        <div class="ai-message mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-robot text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">AI Assistant</h6>
                                    <p class="mb-0 small">Hello! I can help you analyze data, generate reports, and answer questions about your system. Try asking:</p>
                                    <ul class="small text-muted mt-2 mb-0">
                                        <li>"Show me schools whose plan will expire in 10 days"</li>
                                        <li>"What's the revenue trend for last quarter?"</li>
                                        <li>"Which schools have the highest user activity?"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Ask me anything about your system..." id="aiQuery">
                            <button class="btn btn-primary" type="button" onclick="askAI()">
                                <i class="fas fa-paper-plane"></i>
                            </button>
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
$(document).ready(function() {
    // Initialize dashboard data
    loadDashboardData();
    initializeRevenueChart();
    
    // Auto-refresh every 30 seconds
    setInterval(loadDashboardData, 30000);
});

function loadDashboardData() {
    // Load key metrics
    $.ajax({
        url: '{{ route("superadmin.dashboard") }}',
        data: { action: 'metrics' },
        success: function(data) {
            $('#totalSchools').text(data.totalSchools || 0);
            $('#activePlans').text(data.activePlans || 0);
            $('#monthlyRevenue').text('₹' + (data.monthlyRevenue || 0).toLocaleString());
            $('#activeUsers').text(data.activeUsers || 0);
        }
    });
}

function initializeRevenueChart() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue (₹)',
                data: [1200000, 1350000, 1500000, 1420000, 1680000, 1850000],
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Predicted (₹)',
                data: [null, null, null, null, null, 1850000, 2100000, 2250000],
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderDash: [5, 5],
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

function refreshSystemHealth() {
    // Simulate system health data refresh
    $('#serverUptime').text('99.9%');
    $('#storageUsage').text('65%');
    $('#apiResponse').text('120ms');
    $('#errorRate').text('0.02%');
    
    showAlert('success', 'System health data refreshed successfully!');
}

function askAI() {
    const query = $('#aiQuery').val();
    if (!query.trim()) return;
    
    // Simulate AI response
    const responses = [
        "Based on my analysis, I found 3 schools whose plans will expire in the next 10 days. Would you like me to show you the details?",
        "The revenue trend for last quarter shows a 15% growth compared to the previous quarter. The main drivers are new school registrations and plan upgrades.",
        "The schools with highest user activity are Delhi Public School (98% active), ABC School (95% active), and XYZ School (92% active)."
    ];
    
    const randomResponse = responses[Math.floor(Math.random() * responses.length)];
    
    // Add user message
    const userMessage = `
        <div class="user-message mb-3 p-3 bg-primary text-white rounded">
            <div class="d-flex align-items-start">
                <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">You</h6>
                    <p class="mb-0 small">${query}</p>
                </div>
            </div>
        </div>
    `;
    
    // Add AI response
    const aiMessage = `
        <div class="ai-message mb-3 p-3 bg-light rounded">
            <div class="d-flex align-items-start">
                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                    <i class="fas fa-robot text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">AI Assistant</h6>
                    <p class="mb-0 small">${randomResponse}</p>
                </div>
            </div>
        </div>
    `;
    
    $('.ai-chat').prepend(userMessage + aiMessage);
    $('#aiQuery').val('');
}

// Add smooth animations
$('.card').each(function(index) {
    $(this).css('animation-delay', (index * 0.1) + 's');
    $(this).addClass('fade-in-up');
});
</script>
@endsection

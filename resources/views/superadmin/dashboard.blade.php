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
                                <i class="fas fa-tachometer-alt me-3"></i>Super Admin Dashboard
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your system.</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">{{ now()->format('M d, Y') }}</div>
                            <small class="opacity-75">{{ now()->format('l') }}</small>
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
                            <div class="text-uppercase text-primary fw-bold small mb-1">Total Schools</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="totalSchools">0</div>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up me-1"></i>+12% from last month
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
                                <i class="fas fa-arrow-up me-1"></i>+8% from last month
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-credit-card fa-2x text-success"></i>
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
                            <div class="text-success small">
                                <i class="fas fa-arrow-up me-1"></i>+15% from last month
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
                            <div class="text-uppercase text-info fw-bold small mb-1">Total Purchases</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="totalPurchases">0</div>
                            <div class="text-info small">
                                <i class="fas fa-shopping-cart me-1"></i>This month
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-shopping-cart fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health & AI Insights -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Revenue Analytics</h5>
                    <p class="text-muted mb-0">Monthly revenue trends and projections</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">System Health</h5>
                    <p class="text-muted mb-0">Real-time system status</p>
                </div>
                <div class="card-body p-4">
                    <div class="system-health">
                        <div class="health-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Server Status</span>
                                <span class="badge bg-success">Online</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div class="health-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Database</span>
                                <span class="badge bg-success">Healthy</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div class="health-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">API Response</span>
                                <span class="badge bg-success">Fast</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div class="health-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">Storage</span>
                                <span class="badge bg-warning">75% Used</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Features & Recent Activity -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">AI Insights</h5>
                    <p class="text-muted mb-0">Smart analytics and predictions</p>
                </div>
                <div class="card-body p-4">
                    <div class="ai-insights">
                        <div class="insight-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-chart-line text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Revenue Forecast</h6>
                                    <p class="mb-1 small text-muted">Next quarter predicted revenue</p>
                                    <div class="h5 mb-0 text-primary">₹2.4M</div>
                                    <span class="badge bg-success">+15% growth</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="insight-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-arrow-up text-warning me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Upgrade Suggestions</h6>
                                    <p class="mb-1 small text-muted">Schools ready for plan upgrade</p>
                                    <div class="h5 mb-0 text-warning">3</div>
                                    <span class="badge bg-warning">High potential</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="insight-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-circle text-danger me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Churn Alert</h6>
                                    <p class="mb-1 small text-muted">Schools showing low activity</p>
                                    <div class="h5 mb-0 text-danger">2</div>
                                    <span class="badge bg-danger">Action needed</span>
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
                    <h5 class="mb-1 fw-bold">Recent Activity</h5>
                    <p class="text-muted mb-0">Latest system activities and events</p>
                </div>
                <div class="card-body p-0">
                    <div class="activity-list">
                        <div class="activity-item p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-school text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">New School Registered</h6>
                                    <p class="mb-0 small text-muted">ABC School joined the platform</p>
                                </div>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-credit-card text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Payment Received</h6>
                                    <p class="mb-0 small text-muted">₹25,000 from XYZ Academy</p>
                                </div>
                                <small class="text-muted">4 hours ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">Plan Expiring Soon</h6>
                                    <p class="mb-0 small text-muted">DEF College plan expires in 3 days</p>
                                </div>
                                <small class="text-muted">6 hours ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-user text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">New Admin Added</h6>
                                    <p class="mb-0 small text-muted">John Doe added to ABC School</p>
                                </div>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Quick Actions</h5>
                    <p class="text-muted mb-0">Common administrative tasks</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('superadmin.schools.index') }}" class="btn btn-outline-primary w-100 p-3 text-start">
                                <i class="fas fa-school me-2"></i>
                                <div>
                                    <div class="fw-bold">Manage Schools</div>
                                    <small class="text-muted">View and manage all schools</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('superadmin.admins.index') }}" class="btn btn-outline-success w-100 p-3 text-start">
                                <i class="fas fa-users me-2"></i>
                                <div>
                                    <div class="fw-bold">Manage Admins</div>
                                    <small class="text-muted">Add and manage admin users</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('superadmin.payment.reports.index') }}" class="btn btn-outline-warning w-100 p-3 text-start">
                                <i class="fas fa-chart-bar me-2"></i>
                                <div>
                                    <div class="fw-bold">View Reports</div>
                                    <small class="text-muted">Financial and analytics reports</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('superadmin.settings.index') }}" class="btn btn-outline-info w-100 p-3 text-start">
                                <i class="fas fa-cog me-2"></i>
                                <div>
                                    <div class="fw-bold">System Settings</div>
                                    <small class="text-muted">Configure system preferences</small>
                                </div>
                            </a>
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
let revenueChart;

$(document).ready(function() {
    initializeCharts();
    loadDashboardData();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadDashboardData();
    }, 30000);
});

function initializeCharts() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue (₹)',
                data: [120000, 150000, 180000, 200000, 220000, 250000, 280000, 300000, 320000, 350000, 380000, 400000],
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
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function loadDashboardData() {
    // Simulate loading dashboard data
    $('#totalSchools').text(Math.floor(Math.random() * 50) + 100);
    $('#activePlans').text(Math.floor(Math.random() * 30) + 80);
    $('#monthlyRevenue').text('₹' + (Math.floor(Math.random() * 500000) + 200000).toLocaleString());
    $('#totalPurchases').text(Math.floor(Math.random() * 200) + 150);
}
</script>
@endsection
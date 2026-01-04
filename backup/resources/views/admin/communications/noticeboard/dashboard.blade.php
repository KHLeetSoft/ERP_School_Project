@extends('admin.layout.app')

@section('title', 'Noticeboard Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-tachometer-alt text-primary me-2"></i>
                    Noticeboard Dashboard
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.noticeboard.index') }}">Noticeboard</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.communications.noticeboard.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Notice
                    </a>
                    <a href="{{ route('admin.communications.noticeboard.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list me-2"></i>View All
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-primary text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4 id="totalNotices">0</h4>
                        <p>Total Notices</p>
                        <small class="stats-trend positive">
                            <i class="fas fa-arrow-up"></i> +12% this month
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-success text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4 id="publishedNotices">0</h4>
                        <p>Published</p>
                        <small class="stats-trend positive">
                            <i class="fas fa-arrow-up"></i> +8% this week
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-warning text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4 id="totalViews">0</h4>
                        <p>Total Views</p>
                        <small class="stats-trend positive">
                            <i class="fas fa-arrow-up"></i> +15% today
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-info text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4 id="activeUsers">0</h4>
                        <p>Active Users</p>
                        <small class="stats-trend positive">
                            <i class="fas fa-arrow-up"></i> +5% this month
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Notice Activity Overview
                    </h5>
                    <div class="card-header-actions">
                        <select class="form-select form-select-sm" id="chartPeriod">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 3 Months</option>
                            <option value="365">Last Year</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Notice Types Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>Top Performing Notices
                    </h5>
                </div>
                <div class="card-body">
                    <div id="topNotices">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div id="recentActivity">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Insights -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.communications.noticeboard.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New Notice
                        </a>
                        <button type="button" class="btn btn-success" onclick="bulkPublish()">
                            <i class="fas fa-check me-2"></i>Bulk Publish
                        </button>
                        <button type="button" class="btn btn-warning" onclick="bulkArchive()">
                            <i class="fas fa-archive me-2"></i>Bulk Archive
                        </button>
                        <button type="button" class="btn btn-info" onclick="exportData()">
                            <i class="fas fa-download me-2"></i>Export Data
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Quick Insights
                    </h5>
                </div>
                <div class="card-body">
                    <div id="quickInsights">
                        <div class="insight-item">
                            <i class="fas fa-chart-line text-success me-2"></i>
                            <span>Notice engagement increased by 25% this month</span>
                        </div>
                        <div class="insight-item">
                            <i class="fas fa-users text-info me-2"></i>
                            <span>Most active department: IT Department</span>
                        </div>
                        <div class="insight-item">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <span>Peak viewing time: 10:00 AM - 2:00 PM</span>
                        </div>
                        <div class="insight-item">
                            <i class="fas fa-star text-warning me-2"></i>
                            <span>Featured notices get 3x more views</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
                    </h5>
                </div>
                <div class="card-body">
                    <div id="upcomingEvents">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>Department Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Total Notices</th>
                                    <th>Published</th>
                                    <th>Total Views</th>
                                    <th>Avg. Views</th>
                                    <th>Engagement Rate</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody id="departmentPerformance">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stats-card {
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-card-body {
        display: flex;
        align-items: center;
    }
    
    .stats-card-icon {
        font-size: 2.5rem;
        margin-right: 15px;
    }
    
    .stats-card-content h4 {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
    }
    
    .stats-card-content p {
        margin: 0;
        opacity: 0.9;
    }
    
    .stats-trend {
        font-size: 0.8rem;
        opacity: 0.8;
    }
    
    .stats-trend.positive {
        color: #fff;
    }
    
    .stats-trend.negative {
        color: #ffebee;
    }
    
    .card-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .insight-item {
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .insight-item:last-child {
        border-bottom: none;
    }
    
    .notice-item {
        padding: 10px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }
    
    .notice-item:hover {
        background: #e9ecef;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.2rem;
        color: white;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: #666;
    }
    
    .performance-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .performance-excellent { background: #d4edda; color: #155724; }
    .performance-good { background: #d1ecf1; color: #0c5460; }
    .performance-average { background: #fff3cd; color: #856404; }
    .performance-poor { background: #f8d7da; color: #721c24; }
    
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 15px;
        }
        
        .card-header-actions {
            margin-top: 10px;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-bottom: 5px;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Global variables
    let activityChart, typeChart;
    let dashboardData = {};
    
    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        loadDashboardData();
        initializeCharts();
        setupEventListeners();
    });
    
    // Load dashboard data
    function loadDashboardData() {
        // Load statistics
        loadStatistics();
        
        // Load charts data
        loadChartData();
        
        // Load top notices
        loadTopNotices();
        
        // Load recent activity
        loadRecentActivity();
        
        // Load upcoming events
        loadUpcomingEvents();
        
        // Load department performance
        loadDepartmentPerformance();
    }
    
    // Load statistics
    function loadStatistics() {
        // Simulate API call - replace with actual API endpoint
        setTimeout(() => {
            document.getElementById('totalNotices').textContent = '156';
            document.getElementById('publishedNotices').textContent = '89';
            document.getElementById('totalViews').textContent = '2,847';
            document.getElementById('activeUsers').textContent = '45';
        }, 1000);
    }
    
    // Load chart data
    function loadChartData() {
        // Simulate API call - replace with actual API endpoint
        setTimeout(() => {
            updateActivityChart();
            updateTypeChart();
        }, 1500);
    }
    
    // Initialize charts
    function initializeCharts() {
        // Activity Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        activityChart = new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Notices Created',
                    data: [],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Views',
                    data: [],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
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
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Type Chart
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Announcements', 'News', 'Events', 'Policies', 'General'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        '#007bff',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#6c757d'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
    
    // Update activity chart
    function updateActivityChart() {
        const period = document.getElementById('chartPeriod').value;
        const days = parseInt(period);
        
        // Generate sample data based on period
        const labels = [];
        const noticesData = [];
        const viewsData = [];
        
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            
            // Generate random data (replace with actual API data)
            noticesData.push(Math.floor(Math.random() * 10) + 1);
            viewsData.push(Math.floor(Math.random() * 100) + 20);
        }
        
        activityChart.data.labels = labels;
        activityChart.data.datasets[0].data = noticesData;
        activityChart.data.datasets[1].data = viewsData;
        activityChart.update();
    }
    
    // Update type chart
    function updateTypeChart() {
        // Generate random data (replace with actual API data)
        const data = [
            Math.floor(Math.random() * 50) + 20,
            Math.floor(Math.random() * 40) + 15,
            Math.floor(Math.random() * 30) + 10,
            Math.floor(Math.random() * 25) + 8,
            Math.floor(Math.random() * 20) + 5
        ];
        
        typeChart.data.datasets[0].data = data;
        typeChart.update();
    }
    
    // Load top notices
    function loadTopNotices() {
        // Simulate API call - replace with actual API endpoint
        setTimeout(() => {
            const topNoticesHtml = `
                <div class="notice-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Company Policy Update</h6>
                            <small class="text-muted">IT Department</small>
                        </div>
                        <span class="badge bg-success">2.5k views</span>
                    </div>
                </div>
                <div class="notice-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Annual Meeting Schedule</h6>
                            <small class="text-muted">HR Department</small>
                        </div>
                        <span class="badge bg-primary">1.8k views</span>
                    </div>
                </div>
                <div class="notice-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">New Software Release</h6>
                            <small class="text-muted">IT Department</small>
                        </div>
                        <span class="badge bg-info">1.2k views</span>
                    </div>
                </div>
            `;
            document.getElementById('topNotices').innerHTML = topNoticesHtml;
        }, 2000);
    }
    
    // Load recent activity
    function loadRecentActivity() {
        // Simulate API call - replace with actual API endpoint
        setTimeout(() => {
            const activityHtml = `
                <div class="activity-item">
                    <div class="activity-icon bg-primary">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div><strong>New notice created:</strong> "Company Policy Update"</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon bg-success">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="activity-content">
                        <div><strong>Notice published:</strong> "Annual Meeting Schedule"</div>
                        <div class="activity-time">4 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon bg-warning">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="activity-content">
                        <div><strong>Notice featured:</strong> "New Software Release"</div>
                        <div class="activity-time">6 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon bg-info">
                        <i class="fas fa-thumbtack"></i>
                    </div>
                    <div class="activity-content">
                        <div><strong>Notice pinned:</strong> "Important Announcement"</div>
                        <div class="activity-time">1 day ago</div>
                    </div>
                </div>
            `;
            document.getElementById('recentActivity').innerHTML = activityHtml;
        }, 2500);
    }
    
    // Load upcoming events
    function loadUpcomingEvents() {
        // Simulate API call - replace with actual API endpoint
        setTimeout(() => {
            const eventsHtml = `
                <div class="notice-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Team Building Event</h6>
                            <small class="text-muted">Tomorrow, 2:00 PM</small>
                        </div>
                        <span class="badge bg-warning">Event</span>
                    </div>
                </div>
                <div class="notice-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Quarterly Review</h6>
                            <small class="text-muted">Next Week, 10:00 AM</small>
                        </div>
                        <span class="badge bg-info">Meeting</span>
                    </div>
                </div>
                <div class="notice-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Training Session</h6>
                            <small class="text-muted">Next Week, 3:00 PM</small>
                        </div>
                        <span class="badge bg-success">Training</span>
                    </div>
                </div>
            `;
            document.getElementById('upcomingEvents').innerHTML = eventsHtml;
        }, 3000);
    }
    
    // Load department performance
    function loadDepartmentPerformance() {
        // Simulate API call - replace with actual API endpoint
        setTimeout(() => {
            const performanceHtml = `
                <tr>
                    <td><strong>IT Department</strong></td>
                    <td>45</td>
                    <td>32</td>
                    <td>1,250</td>
                    <td>28</td>
                    <td>85%</td>
                    <td><span class="performance-badge performance-excellent">Excellent</span></td>
                </tr>
                <tr>
                    <td><strong>HR Department</strong></td>
                    <td>38</td>
                    <td>28</td>
                    <td>980</td>
                    <td>26</td>
                    <td>78%</td>
                    <td><span class="performance-badge performance-good">Good</span></td>
                </tr>
                <tr>
                    <td><strong>Finance Department</strong></td>
                    <td>32</td>
                    <td>25</td>
                    <td>720</td>
                    <td>23</td>
                    <td>72%</td>
                    <td><span class="performance-badge performance-average">Average</span></td>
                </tr>
                <tr>
                    <td><strong>Marketing Department</strong></td>
                    <td>28</td>
                    <td>20</td>
                    <td>580</td>
                    <td>21</td>
                    <td>68%</td>
                    <td><span class="performance-badge performance-average">Average</span></td>
                </tr>
            `;
            document.getElementById('departmentPerformance').innerHTML = performanceHtml;
        }, 3500);
    }
    
    // Setup event listeners
    function setupEventListeners() {
        // Chart period change
        document.getElementById('chartPeriod').addEventListener('change', updateActivityChart);
    }
    
    // Quick action functions
    function bulkPublish() {
        showNotification('Bulk publish functionality will be implemented soon!', 'info');
    }
    
    function bulkArchive() {
        showNotification('Bulk archive functionality will be implemented soon!', 'info');
    }
    
    function exportData() {
        // Implement export functionality
        showNotification('Export functionality will be implemented soon!', 'info');
    }
    
    function refreshDashboard() {
        showNotification('Refreshing dashboard...', 'info');
        loadDashboardData();
    }
    
    // Notification system
    function showNotification(message, type = 'info') {
        const notification = `
            <div class="notification notification-${type}" style="
                position: fixed; top: 20px; right: 20px; 
                background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : type === 'warning' ? '#ffc107' : '#17a2b8'}; 
                color: white; padding: 15px 20px; border-radius: 8px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999;
                animation: slideIn 0.3s ease-out;
            ">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; margin-left: 15px;">×</button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', notification);
        setTimeout(() => {
            const notif = document.querySelector('.notification');
            if (notif) notif.remove();
        }, 5000);
    }
    
    // Auto-refresh dashboard every 5 minutes
    setInterval(() => {
        // Only refresh if user is active
        if (!document.hidden) {
            loadStatistics();
        }
    }, 300000); // 5 minutes
</script>
@endsection

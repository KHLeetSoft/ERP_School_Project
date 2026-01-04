@extends('admin.layout.app')

@section('title', 'Newsletter Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Newsletter Dashboard</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Newsletter Analytics</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.newsletter.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Create Newsletter
                </a>
                <a href="{{ route('admin.communications.newsletter.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list mr-1"></i> View All
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-card__content">
                        <div class="stat-card__icon stat-card__icon--primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-card__info">
                            <p class="stat-card__number">{{ number_format($totalSubscribers ?? 0) }}</p>
                            <p class="stat-card__desc">Total Subscribers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-card__content">
                        <div class="stat-card__icon stat-card__icon--success">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div class="stat-card__info">
                            <p class="stat-card__number">{{ number_format($totalNewsletters ?? 0) }}</p>
                            <p class="stat-card__desc">Total Newsletters</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-card__content">
                        <div class="stat-card__icon stat-card__icon--info">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-card__info">
                            <p class="stat-card__number">{{ number_format($averageOpenRate ?? 0, 1) }}%</p>
                            <p class="stat-card__desc">Average Open Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-card__content">
                        <div class="stat-card__icon stat-card__icon--warning">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <div class="stat-card__info">
                            <p class="stat-card__number">{{ number_format($averageClickRate ?? 0, 1) }}%</p>
                            <p class="stat-card__desc">Average Click Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8">
            <!-- Subscriber Growth Chart -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Subscriber Growth Trends
                    </h4>
                    <div class="card-tools">
                        <select class="form-control form-control-sm" id="growthPeriod">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 3 Months</option>
                            <option value="365">Last Year</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="subscriberGrowthChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Engagement Metrics -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Engagement Overview
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="engagementChart" height="200"></canvas>
                    <div class="engagement-stats mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="engagement-stat">
                                    <h5 class="text-primary">{{ number_format($totalOpens ?? 0) }}</h5>
                                    <small>Total Opens</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="engagement-stat">
                                    <h5 class="text-success">{{ number_format($totalClicks ?? 0) }}</h5>
                                    <small>Total Clicks</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="engagement-stat">
                                    <h5 class="text-warning">{{ number_format($totalBounces ?? 0) }}</h5>
                                    <small>Bounces</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts Row -->
    <div class="row">
        <div class="col-xl-6">
            <!-- Newsletter Performance by Category -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Performance by Category
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="categoryPerformanceChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <!-- Delivery Time Analysis -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-clock mr-2"></i>
                        Best Sending Times
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="sendingTimeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics Row -->
    <div class="row">
        <div class="col-xl-4">
            <!-- Geographic Distribution -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-globe mr-2"></i>
                        Geographic Distribution
                    </h4>
                </div>
                <div class="card-body">
                    <div class="geo-stats">
                        @foreach($topCountries ?? [] as $country)
                        <div class="geo-stat-item">
                            <div class="geo-stat-info">
                                <span class="geo-stat-country">{{ $country->country ?? 'Unknown' }}</span>
                                <span class="geo-stat-count">{{ number_format($country->total) }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: {{ ($country->total / max(1, $totalSubscribers ?? 1)) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Device & Browser Stats -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-mobile-alt mr-2"></i>
                        Device & Browser
                    </h4>
                </div>
                <div class="card-body">
                    <div class="device-stats">
                        <div class="device-category">
                            <h6>Device Types</h6>
                            @foreach($deviceStats ?? [] as $device)
                            <div class="device-stat-item">
                                <span class="device-name">{{ ucfirst($device->device_type ?? 'Unknown') }}</span>
                                <span class="device-percentage">{{ number_format(($device->total / max(1, $totalOpens ?? 1)) * 100, 1) }}%</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="browser-category mt-3">
                            <h6>Top Browsers</h6>
                            @foreach($browserStats ?? [] as $browser)
                            <div class="browser-stat-item">
                                <span class="browser-name">{{ $browser->browser ?? 'Unknown' }}</span>
                                <span class="browser-percentage">{{ number_format(($browser->total / max(1, $totalOpens ?? 1)) * 100, 1) }}%</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Subscriber Activity Heatmap -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Weekly Activity Pattern
                    </h4>
                </div>
                <div class="card-body">
                    <div class="activity-heatmap">
                        @foreach($weeklyActivity ?? [] as $day => $activity)
                        <div class="heatmap-row">
                            <span class="day-label">{{ $day }}</span>
                            <div class="heatmap-cells">
                                @foreach($activity as $hour => $count)
                                <div class="heatmap-cell" 
                                     style="background-color: rgba(59, 130, 246, {{ min(1, $count / max(1, $maxHourlyActivity ?? 1)) }})"
                                     title="{{ $day }} {{ $hour }}:00 - {{ $count }} activities">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="row">
        <div class="col-xl-8">
            <!-- Recent Newsletters -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-newspaper mr-2"></i>
                        Recent Newsletters
                    </h4>
                    <div class="card-tools">
                        <a href="{{ route('admin.communications.newsletter.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Performance</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentNewsletters ?? [] as $newsletter)
                                <tr>
                                    <td>
                                        <div class="newsletter-title">
                                            <strong>{{ Str::limit($newsletter->title, 40) }}</strong>
                                            @if($newsletter->is_featured)
                                                <span class="badge badge-warning ml-1">Featured</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst($newsletter->category) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $newsletter->status === 'sent' ? 'success' : ($newsletter->status === 'scheduled' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($newsletter->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="performance-indicators">
                                            @if($newsletter->status === 'sent')
                                                <div class="performance-item">
                                                    <small class="text-muted">Open Rate:</small>
                                                    <span class="text-info">{{ number_format(($newsletter->opened_count / max(1, $newsletter->sent_count)) * 100, 1) }}%</span>
                                                </div>
                                                <div class="performance-item">
                                                    <small class="text-muted">Click Rate:</small>
                                                    <span class="text-success">{{ number_format(($newsletter->clicked_count / max(1, $newsletter->sent_count)) * 100, 1) }}%</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Not sent yet</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $newsletter->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.communications.newsletter.show', $newsletter->id) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.communications.newsletter.edit', $newsletter->id) }}" 
                                               class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>No newsletters created yet</p>
                                        <a href="{{ route('admin.communications.newsletter.create') }}" class="btn btn-primary">
                                            Create Your First Newsletter
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-bolt mr-2"></i>
                        Quick Actions
                    </h4>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('admin.communications.newsletter.create') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-plus mr-2"></i>Create Newsletter
                        </a>
                        <a href="{{ route('admin.communications.newsletter.index') }}" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-list mr-2"></i>Manage Newsletters
                        </a>
                        <button class="btn btn-success btn-block mb-2" onclick="exportAnalytics()">
                            <i class="fas fa-download mr-2"></i>Export Analytics
                        </button>
                        <button class="btn btn-warning btn-block mb-2" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Top Performing Newsletters -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-trophy mr-2"></i>
                        Top Performers
                    </h4>
                </div>
                <div class="card-body">
                    @forelse($topPerformingNewsletters ?? [] as $newsletter)
                    <div class="top-performer-item">
                        <div class="performer-rank">
                            <span class="rank-number">{{ $loop->iteration }}</span>
                        </div>
                        <div class="performer-info">
                            <h6 class="performer-title">{{ Str::limit($newsletter->title, 30) }}</h6>
                            <div class="performer-stats">
                                <span class="stat-item">
                                    <i class="fas fa-eye text-info"></i> {{ number_format($newsletter->opened_count) }}
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-mouse-pointer text-success"></i> {{ number_format($newsletter->clicked_count) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center">No performance data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Metrics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-area mr-2"></i>
                        Real-time Metrics
                    </h4>
                    <div class="card-tools">
                        <span class="badge badge-success" id="liveIndicator">
                            <i class="fas fa-circle"></i> Live
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="realtime-metric">
                                <h3 class="metric-value" id="realtimeOpens">0</h3>
                                <p class="metric-label">Opens Today</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="realtime-metric">
                                <h3 class="metric-value" id="realtimeClicks">0</h3>
                                <p class="metric-label">Clicks Today</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="realtime-metric">
                                <h3 class="metric-value" id="realtimeSubscribers">0</h3>
                                <p class="metric-label">New Subscribers</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="realtime-metric">
                                <h3 class="metric-value" id="realtimeBounces">0</h3>
                                <p class="metric-label">Bounces Today</p>
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
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Subscriber Growth Chart
    const growthCtx = document.getElementById('subscriberGrowthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Total Subscribers',
                data: [120, 150, 180, 220, 280, 350],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Engagement Chart
    const engagementCtx = document.getElementById('engagementChart').getContext('2d');
    new Chart(engagementCtx, {
        type: 'doughnut',
        data: {
            labels: ['Opens', 'Clicks', 'Bounces', 'Unsubscribed'],
            datasets: [{
                data: [65, 25, 8, 2],
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}
</script>
@endsection

@section('styles')
<style>
.stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.stat-card__content {
    display: flex;
    align-items: center;
}

.stat-card__icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 24px;
    color: white;
}

.stat-card__icon--primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card__icon--success { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card__icon--info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card__icon--warning { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

.stat-card__number {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
}

.stat-card__desc {
    margin: 0;
    color: #718096;
    font-size: 14px;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.newsletter-title {
    max-width: 200px;
}

.performance-indicators {
    font-size: 12px;
}

.performance-item {
    margin-bottom: 2px;
}
</style>
@endsection

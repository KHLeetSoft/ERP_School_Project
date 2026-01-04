@extends('parent.layout.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Welcome back, {{ auth()->user()->name }}!</h4>
                        <p class="text-muted mb-0">Here's what's happening with your children today.</p>
                    </div>
                    <div class="d-none d-md-block">
                        <div class="text-end">
                            <div class="h5 mb-0 text-primary">{{ now()->format('l, F j, Y') }}</div>
                            <small class="text-muted">{{ now()->format('g:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card h-100">
            <div class="card-body text-center">
                <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-child"></i>
                </div>
                <div class="stats-number">{{ $stats['children_count'] }}</div>
                <div class="stats-label">My Children</div>
                <div class="mt-2">
                    <small class="text-success">
                        <i class="fas fa-arrow-up me-1"></i>
                        {{ $stats['active_children'] }} active
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card h-100">
            <div class="card-body text-center">
                <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-number">95%</div>
                <div class="stats-label">Attendance Rate</div>
                <div class="mt-2">
                    <small class="text-success">
                        <i class="fas fa-arrow-up me-1"></i>
                        +2% from last month
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card h-100">
            <div class="card-body text-center">
                <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-number">{{ $stats['recent_communications'] }}</div>
                <div class="stats-label">New Messages</div>
                <div class="mt-2">
                    <small class="text-info">
                        <i class="fas fa-bell me-1"></i>
                        {{ $stats['recent_communications'] > 0 ? 'Unread messages' : 'All caught up' }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card h-100">
            <div class="card-body text-center">
                <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stats-number">3</div>
                <div class="stats-label">Pending Tasks</div>
                <div class="mt-2">
                    <small class="text-warning">
                        <i class="fas fa-clock me-1"></i>
                        Due this week
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- My Children Section -->
    <div class="col-lg-8 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-child me-2 text-primary"></i>My Children
                    </h5>
                    <a href="{{ route('parent.children') }}" class="btn btn-sm btn-primary-modern">
                        <i class="fas fa-list me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($children->count() > 0)
                    <div class="row">
                        @foreach($children as $child)
                            <div class="col-md-6 mb-3">
                                <div class="modern-card border-0" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="fas fa-user fa-lg"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold">{{ $child->first_name }} {{ $child->last_name }}</h6>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-graduation-cap me-1"></i>
                                                    {{ $child->schoolClass->name ?? 'N/A' }} - {{ $child->section->name ?? 'N/A' }}
                                                </p>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge {{ $child->status ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $child->status ? 'Active' : 'Inactive' }}
                                                    </span>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Last seen: Today
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('parent.children.show', $child) }}">
                                                        <i class="fas fa-eye me-2"></i>View Details
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('parent.children.progress', $child) }}">
                                                        <i class="fas fa-chart-line me-2"></i>Progress
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('parent.children.attendance', $child) }}">
                                                        <i class="fas fa-calendar-check me-2"></i>Attendance
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-child fa-4x text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted mb-2">No children registered</h5>
                        <p class="text-muted mb-4">Contact the school administration to register your children.</p>
                        <a href="{{ route('parent.profile') }}" class="btn btn-primary-modern">
                            <i class="fas fa-user-plus me-2"></i>Update Profile
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions & Activities -->
    <div class="col-lg-4 mb-4">
        <!-- Quick Actions -->
        <div class="modern-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('parent.attendance') }}" class="btn btn-modern btn-primary-modern">
                        <i class="fas fa-calendar-check me-2"></i>View Attendance
                    </a>
                    <a href="{{ route('parent.results') }}" class="btn btn-modern btn-success-modern">
                        <i class="fas fa-chart-line me-2"></i>Check Results
                    </a>
                    <a href="{{ route('parent.fees') }}" class="btn btn-modern" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white;">
                        <i class="fas fa-credit-card me-2"></i>Pay Fees
                    </a>
                    <a href="{{ route('parent.communications') }}" class="btn btn-modern" style="background: linear-gradient(135deg, #06b6d4, #0891b2); color: white;">
                        <i class="fas fa-comments me-2"></i>Messages
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2 text-info"></i>Recent Activities
                </h5>
            </div>
            <div class="card-body">
                @if($recentActivities->count() > 0)
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ $activity['title'] ?? 'Activity Update' }}</h6>
                                    <p class="text-muted mb-1 small">{{ $activity['description'] ?? 'Activity description' }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $activity['time'] ?? 'Just now' }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-clock fa-2x text-muted mb-3 opacity-50"></i>
                        <p class="text-muted mb-0">No recent activities</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Communications -->
<div class="row">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-comments me-2 text-success"></i>Recent Communications
                    </h5>
                    <a href="{{ route('parent.communications') }}" class="btn btn-sm btn-success-modern">
                        <i class="fas fa-list me-1"></i>View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($parent->communications()->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parent->communications()->latest()->limit(5)->get() as $communication)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar me-2 text-muted"></i>
                                                {{ $communication->created_at->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    {{ substr($communication->sender->name ?? 'S', 0, 1) }}
                                                </div>
                                                {{ $communication->sender->name ?? 'School' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $communication->subject }}</div>
                                            <small class="text-muted">{{ Str::limit($communication->message, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $communication->is_read ? 'bg-success' : 'bg-warning' }}">
                                                <i class="fas fa-{{ $communication->is_read ? 'check' : 'clock' }} me-1"></i>
                                                {{ $communication->is_read ? 'Read' : 'Unread' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-4x text-muted mb-3 opacity-50"></i>
                        <h5 class="text-muted mb-2">No communications yet</h5>
                        <p class="text-muted mb-4">You'll receive messages from teachers and school administration here.</p>
                        <a href="{{ route('parent.profile') }}" class="btn btn-primary-modern">
                            <i class="fas fa-user me-2"></i>Update Contact Info
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #e5e7eb;
    }

    .timeline-content {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }

    .stats-card {
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .modern-card {
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .btn-modern {
        transition: all 0.3s ease;
        border: none;
        font-weight: 600;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh dashboard every 5 minutes
    setInterval(function() {
        // Only refresh if user is still active
        if (document.visibilityState === 'visible') {
            location.reload();
        }
    }, 300000);

    // Add animation to stats cards on load
    document.addEventListener('DOMContentLoaded', function() {
        const statsCards = document.querySelectorAll('.stats-card');
        statsCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
    });

    // Add hover effects to children cards
    document.querySelectorAll('.modern-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>
@endpush
@extends('admin.layout.app')

@section('title', 'Noticeboard Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-bullhorn text-primary me-2"></i>
                    Noticeboard Management
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Communications</a></li>
                    <li class="breadcrumb-item active">Noticeboard</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.communications.noticeboard.dashboard') }}" class="btn btn-info">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.communications.noticeboard.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Notice
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card bg-primary text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4>{{ $stats['total'] }}</h4>
                        <p>Total Notices</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card bg-success text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4>{{ $stats['published'] }}</h4>
                        <p>Published</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card bg-warning text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4>{{ $stats['draft'] }}</h4>
                        <p>Drafts</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card bg-info text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4>{{ $stats['featured'] }}</h4>
                        <p>Featured</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card bg-secondary text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-thumbtack"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4>{{ $stats['pinned'] }}</h4>
                        <p>Pinned</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card bg-dark text-white">
                <div class="stats-card-body">
                    <div class="stats-card-icon">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="stats-card-content">
                        <h4>{{ $stats['archived'] }}</h4>
                        <p>Archived</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Advanced Filters
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.communications.noticeboard.index') }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search notices..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Priority</label>
                            <select name="priority" class="form-select">
                                <option value="">All Priorities</option>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                        {{ ucfirst($priority) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <a href="{{ route('admin.communications.noticeboard.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <a href="{{ route('admin.communications.noticeboard.export') }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-success w-100">
                                <i class="fas fa-download me-2"></i>Export
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Noticeboard List -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Noticeboard Items</h5>
                </div>
                <div class="col-auto">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($noticeboards->count() > 0)
                <div class="row" id="noticeboardGrid">
                    @foreach($noticeboards as $notice)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="notice-card {{ $notice->is_pinned ? 'pinned' : '' }} {{ $notice->is_featured ? 'featured' : '' }}">
                                <div class="notice-card-header">
                                    <div class="notice-type-badge">
                                        <i class="{{ $notice->type_icon }}"></i>
                                        {{ ucfirst($notice->type) }}
                                    </div>
                                    <div class="notice-priority-badge priority-{{ $notice->priority }}">
                                        {{ ucfirst($notice->priority) }}
                                    </div>
                                </div>
                                
                                <div class="notice-card-body">
                                    <h5 class="notice-title">
                                        @if($notice->is_pinned)
                                            <i class="fas fa-thumbtack text-primary me-2"></i>
                                        @endif
                                        @if($notice->is_featured)
                                            <i class="fas fa-star text-warning me-2"></i>
                                        @endif
                                        {{ $notice->title }}
                                    </h5>
                                    
                                    <div class="notice-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $notice->author->name ?? 'Unknown' }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $notice->start_date->format('M d, Y') }}
                                        </span>
                                        @if($notice->department)
                                            <span class="meta-item">
                                                <i class="fas fa-building me-1"></i>
                                                {{ $notice->department->name }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="notice-content">
                                        {!! Str::limit(strip_tags($notice->content), 150) !!}
                                    </div>
                                    
                                    <div class="notice-stats">
                                        <span class="stat-item">
                                            <i class="fas fa-eye me-1"></i>
                                            {{ $notice->views_count }}
                                        </span>
                                        <span class="stat-item">
                                            <i class="fas fa-comments me-1"></i>
                                            {{ $notice->comments->count() }}
                                        </span>
                                        <span class="stat-item">
                                            <i class="fas fa-heart me-1"></i>
                                            {{ $notice->likes->count() }}
                                        </span>
                                    </div>
                                    
                                    <div class="notice-tags">
                                        @foreach($notice->tags->take(3) as $tag)
                                            <span class="tag">{{ $tag->name }}</span>
                                        @endforeach
                                        @if($notice->tags->count() > 3)
                                            <span class="tag">+{{ $notice->tags->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="notice-card-footer">
                                    <div class="notice-status status-{{ $notice->status }}">
                                        {{ ucfirst($notice->status) }}
                                    </div>
                                    <div class="notice-actions">
                                        <a href="{{ route('admin.communications.noticeboard.show', $notice->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.communications.noticeboard.edit', $notice->id) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="togglePin({{ $notice->id }})" title="Toggle Pin">
                                            <i class="fas fa-thumbtack"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                onclick="toggleFeature({{ $notice->id }})" title="Toggle Feature">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                onclick="publishNotice({{ $notice->id }})" title="Publish"
                                                {{ $notice->status === 'published' ? 'disabled' : '' }}>
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="archiveNotice({{ $notice->id }})" title="Archive"
                                                {{ $notice->status === 'archived' ? 'disabled' : '' }}>
                                            <i class="fas fa-archive"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteNotice({{ $notice->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $noticeboards->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No notices found</h5>
                    <p class="text-muted">Create your first notice to get started.</p>
                    <a href="{{ route('admin.communications.noticeboard.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Notice
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Noticeboard Styles */
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
    
    .notice-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .notice-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .notice-card.pinned {
        border-left: 5px solid #007bff;
    }
    
    .notice-card.featured {
        border-left: 5px solid #ffc107;
    }
    
    .notice-card-header {
        padding: 15px 20px 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .notice-type-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .notice-priority-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
    }
    
    .priority-low { background: #28a745; }
    .priority-medium { background: #ffc107; }
    .priority-high { background: #fd7e14; }
    .priority-urgent { background: #dc3545; }
    
    .notice-card-body {
        padding: 20px;
    }
    
    .notice-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
        line-height: 1.4;
    }
    
    .notice-meta {
        margin-bottom: 15px;
    }
    
    .meta-item {
        display: inline-block;
        margin-right: 15px;
        font-size: 0.85rem;
        color: #666;
    }
    
    .notice-content {
        color: #555;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    
    .notice-stats {
        margin-bottom: 15px;
    }
    
    .stat-item {
        display: inline-block;
        margin-right: 15px;
        font-size: 0.85rem;
        color: #666;
    }
    
    .notice-tags {
        margin-bottom: 15px;
    }
    
    .tag {
        display: inline-block;
        background: #f8f9fa;
        color: #666;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    .notice-card-footer {
        padding: 15px 20px;
        background: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .notice-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
    }
    
    .status-draft { background: #6c757d; }
    .status-published { background: #28a745; }
    .status-archived { background: #fd7e14; }
    
    .notice-actions {
        display: flex;
        gap: 5px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 15px;
        }
        
        .notice-card {
            margin-bottom: 20px;
        }
        
        .notice-actions {
            flex-wrap: wrap;
            gap: 3px;
        }
        
        .notice-actions .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Individual Actions
    function togglePin(id) {
        fetch(`/admin/communications/noticeboard/${id}/toggle-pin`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        });
    }
    
    function toggleFeature(id) {
        fetch(`/admin/communications/noticeboard/${id}/toggle-feature`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        });
    }
    
    function publishNotice(id) {
        fetch(`/admin/communications/noticeboard/${id}/publish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        });
    }
    
    function archiveNotice(id) {
        fetch(`/admin/communications/noticeboard/${id}/archive`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        });
    }
    
    function deleteNotice(id) {
        if (confirm('Are you sure you want to delete this notice? This action cannot be undone.')) {
            fetch(`/admin/communications/noticeboard/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    showNotification('Notice deleted successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('An error occurred', 'error');
                }
            });
        }
    }
    
    // View Toggle
    document.querySelectorAll('[data-view]').forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active button
            document.querySelectorAll('[data-view]').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Toggle view
            if (view === 'list') {
                document.getElementById('noticeboardGrid').classList.add('list-view');
            } else {
                document.getElementById('noticeboardGrid').classList.remove('list-view');
            }
        });
    });
    
    // Auto-submit filters on change
    document.querySelectorAll('#filterForm select, #filterForm input[type="date"]').forEach(element => {
        element.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
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
</script>
@endsection

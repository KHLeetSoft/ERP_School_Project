@extends('admin.layout.app')

@section('title', $noticeboard->title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-eye text-primary me-2"></i>
                    View Notice
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.noticeboard.index') }}">Noticeboard</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($noticeboard->title, 50) }}</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.communications.noticeboard.edit', $noticeboard->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.communications.noticeboard.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Notice Content -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0">{{ $noticeboard->title }}</h4>
                        </div>
                        <div class="col-auto">
                            <div class="badge-group">
                                @if($noticeboard->is_pinned)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-thumbtack me-1"></i>Pinned
                                    </span>
                                @endif
                                @if($noticeboard->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star me-1"></i>Featured
                                    </span>
                                @endif
                                <span class="badge bg-{{ $noticeboard->priority_color }}">
                                    {{ ucfirst($noticeboard->priority) }}
                                </span>
                                <span class="badge bg-{{ $noticeboard->status === 'published' ? 'success' : ($noticeboard->status === 'draft' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($noticeboard->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="notice-meta mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="meta-item">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <strong>Author:</strong> {{ $noticeboard->author->name ?? 'Unknown' }}
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar text-success me-2"></i>
                                    <strong>Start Date:</strong> {{ $noticeboard->start_date->format('M d, Y H:i') }}
                                </div>
                                @if($noticeboard->end_date)
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-times text-danger me-2"></i>
                                        <strong>End Date:</strong> {{ $noticeboard->end_date->format('M d, Y H:i') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="meta-item">
                                    <i class="fas fa-building text-info me-2"></i>
                                    <strong>Department:</strong> {{ $noticeboard->department->name ?? 'All Departments' }}
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-users text-warning me-2"></i>
                                    <strong>Target Audience:</strong> {{ ucfirst(str_replace('_', ' ', $noticeboard->target_audience)) }}
                                </div>
                                @if($noticeboard->published_at)
                                    <div class="meta-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Published:</strong> {{ $noticeboard->published_at->format('M d, Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="notice-content mb-4">
                        {!! $noticeboard->content !!}
                    </div>

                    @if($noticeboard->tags->count() > 0)
                        <div class="notice-tags mb-4">
                            <h6><i class="fas fa-tags me-2"></i>Tags:</h6>
                            @foreach($noticeboard->tags as $tag)
                                <span class="tag" style="background-color: {{ $tag->tag_color }}">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($noticeboard->attachments && count($noticeboard->attachments) > 0)
                        <div class="notice-attachments mb-4">
                            <h6><i class="fas fa-paperclip me-2"></i>Attachments:</h6>
                            <div class="row">
                                @foreach($noticeboard->attachments as $attachment)
                                    <div class="col-md-6 mb-2">
                                        <div class="attachment-item">
                                            <i class="fas fa-file me-2"></i>
                                            <a href="{{ Storage::url($attachment['path']) }}" target="_blank" class="text-decoration-none">
                                                {{ $attachment['name'] }}
                                            </a>
                                            <small class="text-muted ms-2">({{ number_format($attachment['size'] / 1024, 2) }} KB)</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="notice-stats">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                                    <h5>{{ $noticeboard->views_count }}</h5>
                                    <p class="text-muted">Views</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <i class="fas fa-comments fa-2x text-success mb-2"></i>
                                    <h5>{{ $noticeboard->comments->count() }}</h5>
                                    <p class="text-muted">Comments</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                                    <h5>{{ $noticeboard->likes->count() }}</h5>
                                    <p class="text-muted">Likes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            @if($noticeboard->comments->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>Comments ({{ $noticeboard->comments->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($noticeboard->comments->where('parent_id', null) as $comment)
                            <div class="comment-item mb-3">
                                <div class="comment-header">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $comment->user->name ?? 'Unknown' }}</strong>
                                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if(!$comment->is_approved)
                                            <span class="badge bg-warning">Pending Approval</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="comment-content">
                                    {{ $comment->content }}
                                </div>
                                
                                <!-- Replies -->
                                @foreach($comment->replies as $reply)
                                    <div class="comment-reply ms-4 mt-2 p-3 bg-light rounded">
                                        <div class="comment-header">
                                            <strong>{{ $reply->user->name ?? 'Unknown' }}</strong>
                                            <small class="text-muted ms-2">{{ $reply->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="comment-content">
                                            {{ $reply->content }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($noticeboard->status !== 'published')
                            <button type="button" class="btn btn-success" onclick="publishNotice({{ $noticeboard->id }})">
                                <i class="fas fa-check me-2"></i>Publish Notice
                            </button>
                        @endif
                        
                        @if($noticeboard->status !== 'archived')
                            <button type="button" class="btn btn-warning" onclick="archiveNotice({{ $noticeboard->id }})">
                                <i class="fas fa-archive me-2"></i>Archive Notice
                            </button>
                        @endif
                        
                        <button type="button" class="btn btn-info" onclick="togglePin({{ $noticeboard->id }})">
                            <i class="fas fa-thumbtack me-2"></i>
                            {{ $noticeboard->is_pinned ? 'Unpin' : 'Pin' }} Notice
                        </button>
                        
                        <button type="button" class="btn btn-warning" onclick="toggleFeature({{ $noticeboard->id }})">
                            <i class="fas fa-star me-2"></i>
                            {{ $noticeboard->is_featured ? 'Unfeature' : 'Feature' }} Notice
                        </button>
                        
                        <a href="{{ route('admin.communications.noticeboard.duplicate', $noticeboard->id) }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-copy me-2"></i>Duplicate Notice
                        </a>
                        
                        <button type="button" class="btn btn-danger" onclick="deleteNotice({{ $noticeboard->id }})">
                            <i class="fas fa-trash me-2"></i>Delete Notice
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notice Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Notice Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <strong>Type:</strong>
                        <span class="badge bg-primary">{{ ucfirst($noticeboard->type) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Priority:</strong>
                        <span class="badge bg-{{ $noticeboard->priority_color }}">{{ ucfirst($noticeboard->priority) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $noticeboard->status === 'published' ? 'success' : ($noticeboard->status === 'draft' ? 'secondary' : 'warning') }}">
                            {{ ucfirst($noticeboard->status) }}
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>Created:</strong>
                        <span>{{ $noticeboard->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Last Updated:</strong>
                        <span>{{ $noticeboard->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                    @if($noticeboard->expires_at)
                        <div class="info-item">
                            <strong>Expires:</strong>
                            <span class="text-danger">{{ $noticeboard->expires_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Days Until Expiry:</strong>
                            <span class="text-{{ $noticeboard->days_until_expiry > 7 ? 'success' : ($noticeboard->days_until_expiry > 3 ? 'warning' : 'danger') }}">
                                {{ $noticeboard->days_until_expiry }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Audience & Visibility -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Audience & Visibility
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <strong>Target Audience:</strong>
                        <span>{{ ucfirst(str_replace('_', ' ', $noticeboard->target_audience)) }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Department:</strong>
                        <span>{{ $noticeboard->department->name ?? 'All Departments' }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Public Notice:</strong>
                        <span class="badge bg-{{ $noticeboard->is_public ? 'success' : 'secondary' }}">
                            {{ $noticeboard->is_public ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>Featured:</strong>
                        <span class="badge bg-{{ $noticeboard->is_featured ? 'warning' : 'secondary' }}">
                            {{ $noticeboard->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>Pinned:</strong>
                        <span class="badge bg-{{ $noticeboard->is_pinned ? 'primary' : 'secondary' }}">
                            {{ $noticeboard->is_pinned ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .notice-meta {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    
    .meta-item {
        margin-bottom: 10px;
        font-size: 0.9rem;
    }
    
    .notice-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #333;
    }
    
    .notice-tags {
        border-top: 1px solid #e9ecef;
        padding-top: 20px;
    }
    
    .tag {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        color: white;
        font-size: 0.8rem;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    
    .notice-attachments {
        border-top: 1px solid #e9ecef;
        padding-top: 20px;
    }
    
    .attachment-item {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }
    
    .notice-stats {
        border-top: 1px solid #e9ecef;
        padding-top: 20px;
    }
    
    .stat-item {
        padding: 15px;
    }
    
    .stat-item h5 {
        margin: 10px 0 5px 0;
        font-weight: bold;
    }
    
    .comment-item {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 15px;
    }
    
    .comment-header {
        margin-bottom: 10px;
    }
    
    .comment-content {
        color: #555;
        line-height: 1.6;
    }
    
    .comment-reply {
        border-left: 3px solid #007bff;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .badge-group .badge {
        margin-left: 5px;
    }
    
    .btn-group .btn {
        margin-left: 5px;
    }
    
    @media (max-width: 768px) {
        .meta-item {
            margin-bottom: 15px;
        }
        
        .stat-item {
            margin-bottom: 20px;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-left: 0;
            margin-bottom: 5px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Quick Actions Functions
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
        if (confirm('Are you sure you want to publish this notice?')) {
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
    }
    
    function archiveNotice(id) {
        if (confirm('Are you sure you want to archive this notice?')) {
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
                    setTimeout(() => window.location.href = '{{ route("admin.communications.noticeboard.index") }}', 1000);
                } else {
                    showNotification('An error occurred', 'error');
                }
            });
        }
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
</script>
@endsection

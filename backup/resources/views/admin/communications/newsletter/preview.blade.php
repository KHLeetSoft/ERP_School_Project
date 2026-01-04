@extends('admin.layout.app')

@section('title', 'Newsletter Preview')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Newsletter Preview</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.newsletter.index') }}">Newsletters</a></li>
                    <li class="breadcrumb-item active">Preview</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.newsletter.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
                <a href="{{ route('admin.communications.newsletter.edit', $newsletter->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-1"></i> Edit Newsletter
                </a>
            </div>
        </div>
    </div>

    <!-- Newsletter Preview -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-eye mr-2"></i>
                        Newsletter Preview
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Newsletter Header -->
                    <div class="newsletter-header mb-4">
                        <h2 class="text-primary">{{ $newsletter->title }}</h2>
                        <p class="text-muted mb-2">
                            <strong>Subject:</strong> {{ $newsletter->subject }}
                        </p>
                        <div class="newsletter-meta">
                            <span class="badge badge-{{ $newsletter->status === 'sent' ? 'success' : ($newsletter->status === 'scheduled' ? 'warning' : 'secondary') }} mr-2">
                                {{ ucfirst($newsletter->status) }}
                            </span>
                            <span class="badge badge-info mr-2">{{ ucfirst($newsletter->category) }}</span>
                            @if($newsletter->is_featured)
                                <span class="badge badge-warning">Featured</span>
                            @endif
                        </div>
                    </div>

                    <!-- Newsletter Content -->
                    <div class="newsletter-content">
                        <div class="content-wrapper">
                            {!! $newsletter->content !!}
                        </div>
                    </div>

                    <!-- Newsletter Footer -->
                    <div class="newsletter-footer mt-4 pt-4 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Tags:</h6>
                                @if($newsletter->tags && is_array($newsletter->tags))
                                    @foreach($newsletter->tags as $tag)
                                        <span class="badge badge-light mr-1">{{ $tag }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No tags</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>Metadata:</h6>
                                @if($newsletter->metadata && is_array($newsletter->metadata))
                                    <ul class="list-unstyled">
                                        @foreach($newsletter->metadata as $key => $value)
                                            <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">No metadata</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Newsletter Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        Newsletter Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <label class="font-weight-bold">Status:</label>
                        <span class="badge badge-{{ $newsletter->status === 'sent' ? 'success' : ($newsletter->status === 'scheduled' ? 'warning' : 'secondary') }} ml-2">
                            {{ ucfirst($newsletter->status) }}
                        </span>
                    </div>

                    <div class="info-item mb-3">
                        <label class="font-weight-bold">Category:</label>
                        <span class="ml-2">{{ ucfirst($newsletter->category) }}</span>
                    </div>

                    <div class="info-item mb-3">
                        <label class="font-weight-bold">Template:</label>
                        <span class="ml-2">
                            @if($newsletter->template)
                                {{ $newsletter->template->name }}
                            @else
                                <span class="text-muted">No template</span>
                            @endif
                        </span>
                    </div>

                    @if($newsletter->scheduled_at)
                        <div class="info-item mb-3">
                            <label class="font-weight-bold">Scheduled For:</label>
                            <span class="ml-2">{{ $newsletter->scheduled_at->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                    @endif

                    @if($newsletter->sent_at)
                        <div class="info-item mb-3">
                            <label class="font-weight-bold">Sent At:</label>
                            <span class="ml-2">{{ $newsletter->sent_at->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                    @endif

                    <div class="info-item mb-3">
                        <label class="font-weight-bold">Created:</label>
                        <span class="ml-2">{{ $newsletter->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="info-item mb-3">
                        <label class="font-weight-bold">Last Updated:</label>
                        <span class="ml-2">{{ $newsletter->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Performance Stats -->
            @if($newsletter->status === 'sent')
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Performance Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <label class="font-weight-bold">Total Subscribers:</label>
                        <span class="ml-2">{{ number_format($newsletter->total_subscribers) }}</span>
                    </div>

                    <div class="stat-item mb-3">
                        <label class="font-weight-bold">Sent Count:</label>
                        <span class="ml-2">{{ number_format($newsletter->sent_count) }}</span>
                    </div>

                    <div class="stat-item mb-3">
                        <label class="font-weight-bold">Opened Count:</label>
                        <span class="ml-2">{{ number_format($newsletter->opened_count) }}</span>
                    </div>

                    <div class="stat-item mb-3">
                        <label class="font-weight-bold">Clicked Count:</label>
                        <span class="ml-2">{{ number_format($newsletter->clicked_count) }}</span>
                    </div>

                    <div class="stat-item mb-3">
                        <label class="font-weight-bold">Bounced Count:</label>
                        <span class="ml-2">{{ number_format($newsletter->bounced_count) }}</span>
                    </div>

                    <div class="stat-item mb-3">
                        <label class="font-weight-bold">Unsubscribed Count:</label>
                        <span class="ml-2">{{ number_format($newsletter->unsubscribed_count) }}</span>
                    </div>

                    @if($newsletter->sent_count > 0)
                        <div class="stat-item mb-3">
                            <label class="font-weight-bold">Open Rate:</label>
                            <span class="ml-2">{{ number_format(($newsletter->opened_count / $newsletter->sent_count) * 100, 1) }}%</span>
                        </div>

                        <div class="stat-item mb-3">
                            <label class="font-weight-bold">Click Rate:</label>
                            <span class="ml-2">{{ number_format(($newsletter->clicked_count / $newsletter->sent_count) * 100, 1) }}%</span>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt mr-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($newsletter->status === 'draft')
                        <a href="{{ route('admin.communications.newsletter.send-now', $newsletter->id) }}" 
                           class="btn btn-success btn-block mb-2"
                           onclick="return confirm('Are you sure you want to send this newsletter now?')">
                            <i class="fas fa-paper-plane mr-1"></i> Send Now
                        </a>
                    @endif

                    @if($newsletter->status === 'scheduled')
                        <a href="{{ route('admin.communications.newsletter.cancel-schedule', $newsletter->id) }}" 
                           class="btn btn-warning btn-block mb-2"
                           onclick="return confirm('Are you sure you want to cancel the scheduled sending?')">
                            <i class="fas fa-clock mr-1"></i> Cancel Schedule
                        </a>
                    @endif

                    <a href="{{ route('admin.communications.newsletter.duplicate', $newsletter->id) }}" 
                       class="btn btn-info btn-block mb-2">
                        <i class="fas fa-copy mr-1"></i> Duplicate
                    </a>

                    <a href="{{ route('admin.communications.newsletter.edit', $newsletter->id) }}" 
                       class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>

                    <form action="{{ route('admin.communications.newsletter.destroy', $newsletter->id) }}" 
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" 
                                onclick="return confirm('Are you sure you want to delete this newsletter?')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.newsletter-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.content-wrapper {
    background: white;
    padding: 30px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.newsletter-header h2 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.newsletter-meta {
    margin-top: 15px;
}

.info-item, .stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.info-item:last-child, .stat-item:last-child {
    border-bottom: none;
}

.newsletter-footer h6 {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 10px;
}

.badge {
    font-size: 12px;
    padding: 6px 12px;
}

.newsletter-content img {
    max-width: 100%;
    height: auto;
}

.newsletter-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
}

.newsletter-content table th,
.newsletter-content table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.newsletter-content table th {
    background-color: #f8f9fa;
    font-weight: bold;
}
</style>
@endsection

@extends('admin.layout.app')

@section('title', 'SMS Messages')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('styles')
<style>
/* General Page Layout */
.page-title-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(90deg, #007bff, #0056b3);
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    color: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    position: sticky;
    top: 70px;
    z-index: 100;
}
.page-title {
    font-weight: 600;
    font-size: 1.4rem;
}

/* Filter Card */
.filter-card {
    background: #fff;
    padding: 1rem;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    border-left: 5px solid #0d6efd;
    transition: all 0.2s ease-in-out;
}
.filter-card:hover {
    transform: translateY(-2px);
}

/* Message Card */
.message-card {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(6px);
    border-radius: 14px;
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: all 0.25s ease-in-out;
}
.message-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.message-card.sent { border-left: 6px solid #28a745; }
.message-card.failed { border-left: 6px solid #dc3545; }
.message-card.draft { border-left: 6px solid #ffc107; }

/* Badges */
.badge {
    font-size: 0.8rem;
    padding: 0.4em 0.65em;
    border-radius: 8px;
    margin-right: 0.35rem;
    font-weight: 500;
    transition: 0.2s;
}
.badge-info { background: linear-gradient(45deg,#0dcaf0,#0d6efd); color: #fff; }
.badge-warning { background: linear-gradient(45deg,#ffc107,#ff9800); color: #fff; }
.badge-secondary { background: linear-gradient(45deg,#6c757d,#495057); color: #fff; }
.badge:hover { opacity: 0.85; }

/* Bulk Actions Bar */
.bulk-actions-bar {
    position: fixed;
    bottom: -100px;
    left: 250px;
    right: 0;
    background: #212529;
    color: #fff;
    padding: 0.9rem 1.5rem;
    box-shadow: 0 -4px 12px rgba(0,0,0,0.25);
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1050;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    transition: bottom 0.3s ease-in-out;
}
.bulk-actions-bar.show {
    bottom: 0;
}
.bulk-actions-bar button {
    margin-left: 0.6rem;
    border-radius: 8px;
    transition: all 0.2s;
}
.bulk-actions-bar button:hover {
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    background: #fff;
    border-radius: 14px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.empty-state img {
    opacity: 0.7;
    transition: 0.3s;
}
.empty-state img:hover {
    transform: scale(1.05);
}
.empty-state h5 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}
</style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="page-title-box">
        <h4 class="page-title">📩 SMS Messages <span class="small">({{ $messages->total() }})</span></h4>
        <a href="{{ route('admin.communications.sms.create') }}" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-3">
            <i class="fas fa-plus"></i> Send New SMS
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-card mb-3">
        <button class="btn btn-link p-0 mb-2 fw-bold text-primary" type="button" data-toggle="collapse" data-target="#filtersCollapse">
            <i class="fas fa-filter"></i> Show / Hide Filters
        </button>
        <div class="collapse show" id="filtersCollapse">
            @include('admin.communications.sms.partials.filters')
        </div>
    </div>

    <!-- Messages List -->
    <div class="row">
        @forelse($messages as $message)
        <div class="col-12">
            <div class="message-card {{ $message->status }}">
                <div class="row align-items-center">
                    <!-- Checkbox -->
                    <div class="col-md-1 text-center">
                        <input type="checkbox" class="message-checkbox form-check-input" value="{{ $message->id }}">
                    </div>
                    <!-- Content -->
                    <div class="col-md-7">
                        <h6 class="mb-2 fw-bold">{{ Str::limit($message->message, 80) }}</h6>
                        <div class="message-preview mb-2">
                            <span class="badge badge-info"><i class="fas fa-info-circle"></i> {{ ucfirst($message->status) }}</span>
                            <span class="badge badge-warning"><i class="fas fa-bolt"></i> {{ ucfirst($message->priority) }}</span>
                            <span class="badge badge-secondary"><i class="fas fa-tag"></i> {{ ucfirst($message->category) }}</span>
                            <span class="text-muted ms-2"><i class="fas fa-users"></i> {{ $message->recipients->count() }}</span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-user"></i> {{ $message->sender->name ?? 'Unknown' }} • 
                            <i class="fas fa-calendar"></i> {{ $message->created_at->format('M d, Y H:i') }}
                        </small>
                    </div>
                    <!-- Actions -->
                    <div class="col-md-4 text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.communications.sms.show', $message->id) }}" class="btn btn-outline-primary rounded" title="View"><i class="fas fa-eye"></i></a>
                            @if($message->status === 'draft')
                            <a href="{{ route('admin.communications.sms.edit', $message->id) }}" class="btn btn-outline-secondary rounded" title="Edit"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-outline-success rounded" onclick="sendNow({{ $message->id }})" title="Send"><i class="fas fa-paper-plane"></i></button>
                            @endif
                            @if($message->canRetry)
                            <button class="btn btn-outline-warning rounded" onclick="retryMessage({{ $message->id }})" title="Retry"><i class="fas fa-redo"></i></button>
                            @endif
                            <button class="btn btn-outline-danger rounded" onclick="deleteMessage({{ $message->id }})" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 empty-state">
            <img src="/images/no-data.svg" alt="No messages" width="140" class="mb-3">
            <h5 class="text-muted">No SMS messages found</h5>
            <p class="text-muted">Try adjusting filters or send a new message.</p>
            <a href="{{ route('admin.communications.sms.create') }}" class="btn btn-primary rounded-pill px-4 shadow"><i class="fas fa-plus"></i> Send New SMS</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($messages->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $messages->appends(request()->query())->links() }}
    </div>
    @endif

    <!-- Bulk Actions Floating Bar -->
    <div class="bulk-actions-bar" id="bulkActionsBar">
        <span><strong id="selectedCount">0</strong> selected</span>
        <div>
            <button class="btn btn-danger btn-sm" onclick="deleteSelected()" title="Delete"><i class="fas fa-trash"></i> Delete</button>
            <button class="btn btn-warning btn-sm" onclick="retrySelected()" title="Retry"><i class="fas fa-redo"></i> Retry</button>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
$(document).ready(function() {
    // Handle checkbox selection
    $('.message-checkbox').change(function() {
        updateBulkActions();
    });

    // Handle select all
    $('#selectAll').change(function() {
        $('.message-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkActions();
    });
});

function updateBulkActions() {
    const selectedCount = $('.message-checkbox:checked').length;
    $('#selectedCount').text(selectedCount);
    
    if (selectedCount > 0) {
        $('#bulkActions').addClass('show');
    } else {
        $('#bulkActions').removeClass('show');
    }
}

function removeFilter(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName);
    window.location = url;
}

function sendNow(messageId) {
    if (confirm('Are you sure you want to send this SMS now?')) {
        $.ajax({
            url: `/admin/communications/sms/${messageId}/send-now`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('SMS sent successfully');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to send SMS');
                }
            },
            error: function() {
                toastr.error('Failed to send SMS');
            }
        });
    }
}

function retryMessage(messageId) {
    if (confirm('Are you sure you want to retry this SMS?')) {
        $.ajax({
            url: `/admin/communications/sms/${messageId}/retry`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('SMS retry successful');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to retry SMS');
                }
            },
            error: function() {
                toastr.error('Failed to retry SMS');
            }
        });
    }
}

function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this SMS? This action cannot be undone.')) {
        $.ajax({
            url: `/admin/communications/sms/${messageId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('SMS deleted successfully');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to delete SMS');
                }
            },
            error: function() {
                toastr.error('Failed to delete SMS');
            }
        });
    }
}

function deleteSelected() {
    const selectedIds = $('.message-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        toastr.warning('Please select messages to delete');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${selectedIds.length} selected messages? This action cannot be undone.`)) {
        $.ajax({
            url: '/admin/communications/sms/bulk-delete',
            type: 'POST',
            data: { ids: selectedIds },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Selected messages deleted successfully');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to delete messages');
                }
            },
            error: function() {
                toastr.error('Failed to delete messages');
            }
        });
    }
}

function retrySelected() {
    const selectedIds = $('.message-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        toastr.warning('Please select messages to retry');
        return;
    }
    
    if (confirm(`Are you sure you want to retry ${selectedIds.length} selected messages?`)) {
        $.ajax({
            url: '/admin/communications/sms/bulk-retry',
            type: 'POST',
            data: { ids: selectedIds },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Selected messages retry initiated');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to retry messages');
                }
            },
            error: function() {
                toastr.error('Failed to retry messages');
            }
        });
    }
}
</script>
@endsection

@extends('admin.layout.app')

@section('title', 'Newsletters')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-newspaper text-primary me-2"></i>
                    Newsletters
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Newsletters</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.newsletter.dashboard') }}" class="btn btn-info me-2">
                    <i class="fas fa-chart-bar me-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.communications.newsletter.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Newsletter
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    @include('admin.communications.newsletter.partials.filters')

    <!-- Newsletters Table -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Newsletter List
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="refreshTable()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportData('csv')">Export as CSV</a></li>
                                <li><a class="dropdown-item" href="#" onclick="exportData('excel')">Export as Excel</a></li>
                                <li><a class="dropdown-item" href="#" onclick="exportData('pdf')">Export as PDF</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($newsletters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="newslettersTable">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Subscribers</th>
                                <th>Performance</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newsletters as $newsletter)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input newsletter-checkbox" value="{{ $newsletter->id }}">
                                </td>
                                <td>
                                    <div class="newsletter-title">
                                        <h6 class="mb-1">
                                            <a href="{{ route('admin.communications.newsletter.show', $newsletter->id) }}" class="text-decoration-none">
                                                {{ $newsletter->title }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">{{ Str::limit($newsletter->subject, 50) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $newsletter->category_badge_class }}">
                                        {{ ucfirst($newsletter->category) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $newsletter->status_badge_class }}">
                                        {{ ucfirst($newsletter->status) }}
                                    </span>
                                    @if($newsletter->is_scheduled)
                                        <br><small class="text-muted">{{ $newsletter->formatted_scheduled_date }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="subscriber-info">
                                        <span class="text-primary">{{ $newsletter->total_subscribers }}</span>
                                        @if($newsletter->sent_count > 0)
                                            <br><small class="text-muted">Sent: {{ $newsletter->sent_count }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($newsletter->sent_count > 0)
                                        <div class="performance-stats">
                                            <div class="stat-row">
                                                <span class="stat-label">Open Rate:</span>
                                                <span class="stat-value text-success">{{ $newsletter->open_rate }}%</span>
                                            </div>
                                            <div class="stat-row">
                                                <span class="stat-label">Click Rate:</span>
                                                <span class="stat-value text-info">{{ $newsletter->click_rate }}%</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Not sent yet</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="created-info">
                                        <div>{{ $newsletter->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $newsletter->created_at->format('g:i A') }}</small>
                                        @if($newsletter->creator)
                                            <br><small class="text-muted">by {{ $newsletter->creator->name }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.communications.newsletter.show', $newsletter->id) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.communications.newsletter.edit', $newsletter->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" 
                                                data-bs-toggle="dropdown" title="More Actions">
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($newsletter->can_be_sent)
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="sendNewsletter({{ $newsletter->id }})">
                                                        <i class="fas fa-paper-plane text-success me-2"></i>Send Now
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="scheduleNewsletter({{ $newsletter->id }})">
                                                        <i class="fas fa-clock text-info me-2"></i>Schedule
                                                    </a>
                                                </li>
                                            @endif
                                            @if($newsletter->is_scheduled)
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="cancelSchedule({{ $newsletter->id }})">
                                                        <i class="fas fa-times text-warning me-2"></i>Cancel Schedule
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.communications.newsletter.preview', $newsletter->id) }}" target="_blank">
                                                    <i class="fas fa-eye text-info me-2"></i>Preview
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="duplicateNewsletter({{ $newsletter->id }})">
                                                    <i class="fas fa-copy text-secondary me-2"></i>Duplicate
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteNewsletter({{ $newsletter->id }})">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="pagination-info">
                        Showing {{ $newsletters->firstItem() }} to {{ $newsletters->lastItem() }} of {{ $newsletters->total() }} results
                    </div>
                    <div class="pagination-links">
                        {{ $newsletters->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">No newsletters found</h4>
                    <p class="text-muted">Get started by creating your first newsletter</p>
                    <a href="{{ route('admin.communications.newsletter.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Newsletter
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="selected-count">0 newsletters selected</span>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="bulkAction('send')">
                                <i class="fas fa-paper-plane me-1"></i>Send
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="bulkAction('schedule')">
                                <i class="fas fa-clock me-1"></i>Schedule
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Newsletter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="scheduleForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scheduled_at" class="form-label">Schedule Date & Time</label>
                        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Newsletter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let selectedNewsletters = [];
let currentNewsletterId = null;

$(document).ready(function() {
    // Initialize select all functionality
    $('#selectAll').change(function() {
        $('.newsletter-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkActionsBar();
    });

    // Individual checkbox change
    $(document).on('change', '.newsletter-checkbox', function() {
        updateBulkActionsBar();
    });

    // Schedule form submission
    $('#scheduleForm').submit(function(e) {
        e.preventDefault();
        if (currentNewsletterId) {
            scheduleNewsletterSubmit(currentNewsletterId);
        }
    });
});

function updateBulkActionsBar() {
    selectedNewsletters = $('.newsletter-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedNewsletters.length > 0) {
        $('.selected-count').text(selectedNewsletters.length + ' newsletter(s) selected');
        $('#bulkActionsBar').show();
    } else {
        $('#bulkActionsBar').hide();
    }
}

function sendNewsletter(id) {
    if (confirm('Are you sure you want to send this newsletter now?')) {
        $.post(`/admin/communications/newsletter/${id}/send-now`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            toastr.success('Newsletter sent successfully!');
            setTimeout(() => location.reload(), 1500);
        })
        .fail(function(xhr) {
            toastr.error('Error sending newsletter: ' + (xhr.responseJSON?.message || 'Unknown error'));
        });
    }
}

function scheduleNewsletter(id) {
    currentNewsletterId = id;
    $('#scheduleModal').modal('show');
}

function scheduleNewsletterSubmit(id) {
    const scheduledAt = $('#scheduled_at').val();
    
    $.post(`/admin/communications/newsletter/${id}/schedule`, {
        _token: '{{ csrf_token() }}',
        scheduled_at: scheduledAt
    })
    .done(function(response) {
        toastr.success('Newsletter scheduled successfully!');
        $('#scheduleModal').modal('hide');
        setTimeout(() => location.reload(), 1500);
    })
    .fail(function(xhr) {
        toastr.error('Error scheduling newsletter: ' + (xhr.responseJSON?.message || 'Unknown error'));
    });
}

function cancelSchedule(id) {
    if (confirm('Are you sure you want to cancel the scheduled newsletter?')) {
        $.post(`/admin/communications/newsletter/${id}/cancel-schedule`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            toastr.success('Newsletter schedule cancelled successfully!');
            setTimeout(() => location.reload(), 1500);
        })
        .fail(function(xhr) {
            toastr.error('Error cancelling schedule: ' + (xhr.responseJSON?.message || 'Unknown error'));
        });
    }
}

function duplicateNewsletter(id) {
    if (confirm('Are you sure you want to duplicate this newsletter?')) {
        $.post(`/admin/communications/newsletter/${id}/duplicate`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            toastr.success('Newsletter duplicated successfully!');
            setTimeout(() => location.reload(), 1500);
        })
        .fail(function(xhr) {
            toastr.error('Error duplicating newsletter: ' + (xhr.responseJSON?.message || 'Unknown error'));
        });
    }
}

function deleteNewsletter(id) {
    if (confirm('Are you sure you want to delete this newsletter? This action cannot be undone.')) {
        $.ajax({
            url: `/admin/communications/newsletter/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            }
        })
        .done(function(response) {
            toastr.success('Newsletter deleted successfully!');
            setTimeout(() => location.reload(), 1500);
        })
        .fail(function(xhr) {
            toastr.error('Error deleting newsletter: ' + (xhr.responseJSON?.message || 'Unknown error'));
        });
    }
}

function bulkAction(action) {
    if (selectedNewsletters.length === 0) {
        toastr.warning('Please select newsletters first');
        return;
    }

    let message = '';
    let confirmMessage = '';

    switch(action) {
        case 'send':
            message = 'Are you sure you want to send the selected newsletters?';
            confirmMessage = 'Newsletters sent successfully!';
            break;
        case 'schedule':
            message = 'Are you sure you want to schedule the selected newsletters?';
            confirmMessage = 'Newsletters scheduled successfully!';
            break;
        case 'delete':
            message = 'Are you sure you want to delete the selected newsletters? This action cannot be undone.';
            confirmMessage = 'Newsletters deleted successfully!';
            break;
    }

    if (confirm(message)) {
        $.post('/admin/communications/newsletter/bulk-action', {
            _token: '{{ csrf_token() }}',
            action: action,
            newsletter_ids: selectedNewsletters
        })
        .done(function(response) {
            toastr.success(confirmMessage);
            setTimeout(() => location.reload(), 1500);
        })
        .fail(function(xhr) {
            toastr.error('Error performing bulk action: ' + (xhr.responseJSON?.message || 'Unknown error'));
        });
    }
}

function refreshTable() {
    location.reload();
}

function exportData(format) {
    // TODO: Implement export functionality
    toastr.info('Export functionality coming soon!');
}
</script>
@endsection

@section('styles')
<style>
.newsletter-title h6 {
    color: #2c3e50;
    font-weight: 600;
}

.newsletter-title small {
    font-size: 0.85rem;
}

.performance-stats .stat-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2px;
}

.performance-stats .stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.performance-stats .stat-value {
    font-weight: 600;
    font-size: 0.8rem;
}

.subscriber-info {
    text-align: center;
}

.created-info {
    font-size: 0.9rem;
}

.bulk-actions-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: white;
    border-top: 1px solid #dee2e6;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
}

.bulk-actions-bar .card {
    border: none;
    border-radius: 0;
}

.selected-count {
    font-weight: 600;
    color: #495057;
}

.pagination-info {
    color: #6c757d;
    font-size: 0.9rem;
}

.pagination-links .pagination {
    margin: 0;
}

.btn-group .dropdown-toggle-split {
    border-left: 1px solid rgba(0,0,0,0.125);
}

.newsletter-checkbox {
    margin: 0;
}

#newslettersTable tbody tr:hover {
    background-color: #f8f9fa;
}

#newslettersTable tbody tr:hover .newsletter-title h6 {
    color: #007bff;
}
</style>
@endsection

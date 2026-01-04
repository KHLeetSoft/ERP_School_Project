@extends('admin.layout.app')

@section('title', 'Inventory Issues')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Inventory Issues</h1>
            <p class="text-muted">Track and manage inventory issues and maintenance requests</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.inventory.issues.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Report New Issue
            </a>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bx bx-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.inventory.issues.export', ['format' => 'xlsx']) }}">Export as Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.inventory.issues.export', ['format' => 'csv']) }}">Export as CSV</a></li>
                </ul>
            </div>
            <a href="{{ route('admin.inventory.issues.import') }}" class="btn btn-info">
                <i class="bx bx-upload"></i> Import
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Issues</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_issues'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-error-circle text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Open Issues</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['open_issues'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-time text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress_issues'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-cog text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Resolved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['resolved_issues'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-check-circle text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Critical</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['critical_issues'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-error text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Overdue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['overdue_issues'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-alarm text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters & Search</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.issues.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by title, description, staff...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $value)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="priority" class="form-label">Priority</label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="">All Priority</option>
                        @foreach($priorities as $key => $value)
                            <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="issue_type" class="form-label">Issue Type</label>
                    <select class="form-select" id="issue_type" name="issue_type">
                        <option value="">All Types</option>
                        @foreach($issueTypes as $key => $value)
                            <option value="{{ $key }}" {{ request('issue_type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <a href="{{ route('admin.inventory.issues.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Issues Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Inventory Issues</h6>
        </div>
        <div class="card-body">
            @if($inventoryIssues->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Issue #</th>
                                <th>Item</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Reported By</th>
                                <th>Issue Date</th>
                                <th>Days Open</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventoryIssues as $issue)
                                <tr class="{{ $issue->is_overdue ? 'table-danger' : '' }}">
                                    <td>
                                        <strong>#{{ $issue->id }}</strong>
                                        @if($issue->is_overdue)
                                            <br><span class="badge bg-danger">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $issue->inventoryItem->name }}</strong>
                                            <br><code>{{ $issue->inventoryItem->sku }}</code>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $issue->title }}</strong>
                                            @if($issue->quantity_affected > 1)
                                                <br><small class="text-muted">Qty: {{ $issue->quantity_affected }}</small>
                                            @endif
                                        </div>
                                        @if($issue->description)
                                            <small class="text-muted">{{ Str::limit($issue->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $issueTypes[$issue->issue_type] }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $priorityColors = [
                                                'low' => 'success',
                                                'medium' => 'warning',
                                                'high' => 'danger',
                                                'critical' => 'dark'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $priorityColors[$issue->priority] }}">
                                            {{ $priorities[$issue->priority] }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'open' => 'warning',
                                                'in_progress' => 'info',
                                                'resolved' => 'success',
                                                'closed' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$issue->status] }}">
                                            {{ $statuses[$issue->status] }}
                                        </span>
                                    </td>
                                    <td>{{ $issue->reported_by ?? 'N/A' }}</td>
                                    <td>{{ $issue->issue_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="{{ $issue->is_overdue ? 'text-danger fw-bold' : '' }}">
                                            {{ $issue->days_open }} days
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.inventory.issues.show', $issue) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('admin.inventory.issues.edit', $issue) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="updateStatus({{ $issue->id }})" title="Update Status">
                                                <i class="bx bx-check"></i>
                                            </button>
                                            <form action="{{ route('admin.inventory.issues.destroy', $issue) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this issue?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $inventoryIssues->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bx bx-error-circle text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">No inventory issues found</h5>
                    <p class="text-muted">Start by reporting your first inventory issue.</p>
                    <a href="{{ route('admin.inventory.issues.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Report New Issue
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Issue Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <input type="hidden" id="issueId" name="issue_id">
                    <div class="mb-3">
                        <label for="statusSelect" class="form-label">Status</label>
                        <select class="form-select" id="statusSelect" name="status" required>
                            @foreach($statuses as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="resolutionNotes" class="form-label">Resolution Notes</label>
                        <textarea class="form-control" id="resolutionNotes" name="resolution_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(issueId) {
    document.getElementById('issueId').value = issueId;
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const issueId = document.getElementById('issueId').value;
    const formData = new FormData(this);
    
    fetch(`/admin/inventory/issues/${issueId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: formData.get('status'),
            resolution_notes: formData.get('resolution_notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
});
</script>
@endsection

@extends('admin.layout.app')

@section('title', 'Inventory Issue Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Issue #{{ $inventoryIssue->id }}</h1>
            <p class="text-muted">Inventory issue details and information</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.inventory.issues.edit', $inventoryIssue) }}" class="btn btn-warning">
                <i class="bx bx-edit"></i> Edit Issue
            </a>
            <a href="{{ route('admin.inventory.issues.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Issues
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Issue Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Issue Title</label>
                                <p class="form-control-plaintext">{{ $inventoryIssue->title }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Issue Type</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-secondary">{{ $inventoryIssue->issue_type }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <p class="form-control-plaintext">{{ $inventoryIssue->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Item Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Inventory Item</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Item Name</label>
                                <p class="form-control-plaintext">{{ $inventoryIssue->inventoryItem->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">SKU</label>
                                <p class="form-control-plaintext"><code>{{ $inventoryIssue->inventoryItem->sku }}</code></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <p class="form-control-plaintext">{{ $inventoryIssue->inventoryItem->category ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Quantity</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $inventoryIssue->inventoryItem->is_low_stock ? 'warning' : 'success' }}">
                                        {{ $inventoryIssue->inventoryItem->quantity }} {{ $inventoryIssue->inventoryItem->unit ?? '' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Priority and Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Priority & Status</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                @php
                                    $priorityColors = [
                                        'low' => 'success',
                                        'medium' => 'warning',
                                        'high' => 'danger',
                                        'critical' => 'dark'
                                    ];
                                @endphp
                                <h4 class="text-{{ $priorityColors[$inventoryIssue->priority] }}">
                                    {{ ucfirst($inventoryIssue->priority) }}
                                </h4>
                                <small class="text-muted">Priority</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                @php
                                    $statusColors = [
                                        'open' => 'warning',
                                        'in_progress' => 'info',
                                        'resolved' => 'success',
                                        'closed' => 'secondary'
                                    ];
                                @endphp
                                <h4 class="text-{{ $statusColors[$inventoryIssue->status] }}">
                                    {{ ucfirst(str_replace('_', ' ', $inventoryIssue->status)) }}
                                </h4>
                                <small class="text-muted">Status</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-info">{{ $inventoryIssue->quantity_affected }}</h4>
                                <small class="text-muted">Quantity Affected</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-primary">{{ $inventoryIssue->days_open }}</h4>
                                <small class="text-muted">Days Open</small>
                            </div>
                        </div>
                    </div>

                    @if($inventoryIssue->is_overdue)
                    <div class="alert alert-danger mt-3">
                        <i class="bx bx-error"></i> <strong>Overdue Alert!</strong>
                        This issue has exceeded its priority timeline and requires immediate attention.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Cost and Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cost & Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estimated Cost</label>
                                <p class="form-control-plaintext">
                                    @if($inventoryIssue->estimated_cost)
                                        ₹{{ number_format($inventoryIssue->estimated_cost, 2) }}
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Issue Date</label>
                                <p class="form-control-plaintext">{{ $inventoryIssue->issue_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Resolved Date</label>
                                <p class="form-control-plaintext">
                                    @if($inventoryIssue->resolved_date)
                                        {{ $inventoryIssue->resolved_date->format('M d, Y') }}
                                    @else
                                        Not resolved yet
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Staff Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reported By</label>
                                <p class="form-control-plaintext">{{ $inventoryIssue->reported_by ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Assigned To</label>
                                <p class="form-control-plaintext">{{ $inventoryIssue->assigned_to ?? 'Not assigned' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Location</label>
                                <p class="form-control-plaintext">{{ $inventoryIssue->location ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resolution Information -->
            @if($inventoryIssue->resolution_notes)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Resolution Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Resolution Notes</label>
                        <p class="form-control-plaintext">{{ $inventoryIssue->resolution_notes }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Attachments -->
            @if($inventoryIssue->attachments && count($inventoryIssue->attachments) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Attachments</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($inventoryIssue->attachments as $attachment)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        @if(str_contains($attachment, '.jpg') || str_contains($attachment, '.jpeg') || str_contains($attachment, '.png') || str_contains($attachment, '.gif'))
                                            <img src="{{ asset('storage/' . $attachment) }}" 
                                                 alt="Attachment" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 150px;">
                                        @else
                                            <i class="bx bx-file text-muted" style="font-size: 3rem;"></i>
                                        @endif
                                        <p class="mt-2 mb-0">
                                            <a href="{{ asset('storage/' . $attachment) }}" 
                                               target="_blank" 
                                               class="text-decoration-none">
                                                {{ basename($attachment) }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.inventory.issues.edit', $inventoryIssue) }}" 
                           class="btn btn-warning">
                            <i class="bx bx-edit"></i> Edit Issue
                        </a>
                        
                        <button type="button" class="btn btn-success" 
                                onclick="updateStatus({{ $inventoryIssue->id }})">
                            <i class="bx bx-check"></i> Update Status
                        </button>
                        
                        <a href="{{ route('admin.inventory.issues.index') }}" 
                           class="btn btn-outline-primary">
                            <i class="bx bx-list-ul"></i> View All Issues
                        </a>
                    </div>
                </div>
            </div>

            <!-- Issue Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Issue Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Created</small><br>
                        <strong>{{ $inventoryIssue->created_at->format('M d, Y \a\t g:i A') }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Last Updated</small><br>
                        <strong>{{ $inventoryIssue->updated_at->format('M d, Y \a\t g:i A') }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Days Since Created</small><br>
                        <strong>{{ $inventoryIssue->created_at->diffInDays(now()) }} days</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Days Open</small><br>
                        <strong class="{{ $inventoryIssue->is_overdue ? 'text-danger' : '' }}">
                            {{ $inventoryIssue->days_open }} days
                        </strong>
                    </div>

                    @if($inventoryIssue->resolved_date)
                    <div class="mb-3">
                        <small class="text-muted">Resolution Time</small><br>
                        <strong>{{ $inventoryIssue->issue_date->diffInDays($inventoryIssue->resolved_date) }} days</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Delete Issue -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Danger Zone</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Once you delete an issue, there is no going back. Please be certain.</p>
                    <form action="{{ route('admin.inventory.issues.destroy', $inventoryIssue) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this issue? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="bx bx-trash"></i> Delete Issue
                        </button>
                    </form>
                </div>
            </div>
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
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
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

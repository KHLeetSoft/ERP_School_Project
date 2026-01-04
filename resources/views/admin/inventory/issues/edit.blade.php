@extends('admin.layout.app')

@section('title', 'Edit Inventory Issue')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Inventory Issue</h1>
            <p class="text-muted">Update inventory issue details</p>
        </div>
        <a href="{{ route('admin.inventory.issues.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Issues
        </a>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Issue Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.inventory.issues.update', $inventoryIssue) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6 mb-3">
                                <label for="inventory_item_id" class="form-label">Inventory Item <span class="text-danger">*</span></label>
                                <select class="form-select @error('inventory_item_id') is-invalid @enderror" 
                                        id="inventory_item_id" name="inventory_item_id" required>
                                    <option value="">Select an item</option>
                                    @foreach($inventoryItems as $item)
                                        <option value="{{ $item->id }}" {{ old('inventory_item_id', $inventoryIssue->inventory_item_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->sku }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventory_item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="issue_type" class="form-label">Issue Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('issue_type') is-invalid @enderror" 
                                        id="issue_type" name="issue_type" required>
                                    <option value="">Select issue type</option>
                                    @foreach($issueTypes as $key => $value)
                                        <option value="{{ $key }}" {{ old('issue_type', $inventoryIssue->issue_type) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('issue_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Issue Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $inventoryIssue->title) }}" 
                                       placeholder="Brief description of the issue" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Detailed description of the issue..." required>{{ old('description', $inventoryIssue->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Priority and Status -->
                        <h6 class="mb-3">Priority & Status</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" name="priority" required>
                                    <option value="">Select priority</option>
                                    @foreach($priorities as $key => $value)
                                        <option value="{{ $key }}" {{ old('priority', $inventoryIssue->priority) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    @foreach($statuses as $key => $value)
                                        <option value="{{ $key }}" {{ old('status', $inventoryIssue->status) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="quantity_affected" class="form-label">Quantity Affected <span class="text-danger">*</span></label>
                                <input type="number" min="1" class="form-control @error('quantity_affected') is-invalid @enderror" 
                                       id="quantity_affected" name="quantity_affected" value="{{ old('quantity_affected', $inventoryIssue->quantity_affected) }}" required>
                                @error('quantity_affected')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Cost and Date -->
                        <h6 class="mb-3">Cost & Date Information</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="estimated_cost" class="form-label">Estimated Cost (₹)</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('estimated_cost') is-invalid @enderror" 
                                       id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $inventoryIssue->estimated_cost) }}"
                                       placeholder="0.00">
                                @error('estimated_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                       id="issue_date" name="issue_date" 
                                       value="{{ old('issue_date', $inventoryIssue->issue_date->format('Y-m-d')) }}" required>
                                @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="resolved_date" class="form-label">Resolved Date</label>
                                <input type="date" class="form-control @error('resolved_date') is-invalid @enderror" 
                                       id="resolved_date" name="resolved_date" 
                                       value="{{ old('resolved_date', $inventoryIssue->resolved_date?->format('Y-m-d')) }}">
                                @error('resolved_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Staff Information -->
                        <h6 class="mb-3">Staff Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reported_by" class="form-label">Reported By</label>
                                <input type="text" class="form-control @error('reported_by') is-invalid @enderror" 
                                       id="reported_by" name="reported_by" value="{{ old('reported_by', $inventoryIssue->reported_by) }}"
                                       placeholder="Staff member name">
                                @error('reported_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="assigned_to" class="form-label">Assigned To</label>
                                <input type="text" class="form-control @error('assigned_to') is-invalid @enderror" 
                                       id="assigned_to" name="assigned_to" value="{{ old('assigned_to', $inventoryIssue->assigned_to) }}"
                                       placeholder="Staff member assigned to fix">
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location', $inventoryIssue->location) }}"
                                       placeholder="Where did the issue occur?">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Resolution Notes -->
                        <h6 class="mb-3">Resolution Information</h6>
                        <div class="mb-3">
                            <label for="resolution_notes" class="form-label">Resolution Notes</label>
                            <textarea class="form-control @error('resolution_notes') is-invalid @enderror" 
                                      id="resolution_notes" name="resolution_notes" rows="3" 
                                      placeholder="Notes about how the issue was resolved...">{{ old('resolution_notes', $inventoryIssue->resolution_notes) }}</textarea>
                            @error('resolution_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Current Attachments -->
                        @if($inventoryIssue->attachments && count($inventoryIssue->attachments) > 0)
                        <h6 class="mb-3">Current Attachments</h6>
                        <div class="mb-3">
                            @foreach($inventoryIssue->attachments as $attachment)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bx bx-paperclip me-2"></i>
                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="text-decoration-none">
                                        {{ basename($attachment) }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- New Attachments -->
                        <h6 class="mb-3">Add New Attachments</h6>
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Upload Files</label>
                            <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" 
                                   id="attachments" name="attachments[]" multiple 
                                   accept="image/*,.pdf,.doc,.docx">
                            <div class="form-text">Supported formats: Images, PDF, DOC, DOCX. Max size: 5MB per file.</div>
                            @error('attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $inventoryIssue->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Issue is open and needs attention)
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.inventory.issues.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Update Issue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Issue Info Card -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Issue Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Issue ID:</strong><br>
                        <span class="text-muted">#{{ $inventoryIssue->id }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        <small class="text-muted">{{ $inventoryIssue->created_at->format('M d, Y \a\t g:i A') }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <small class="text-muted">{{ $inventoryIssue->updated_at->format('M d, Y \a\t g:i A') }}</small>
                    </div>

                    <div class="mb-3">
                        <strong>Days Open:</strong><br>
                        <span class="{{ $inventoryIssue->is_overdue ? 'text-danger fw-bold' : '' }}">
                            {{ $inventoryIssue->days_open }} days
                        </span>
                    </div>

                    @if($inventoryIssue->is_overdue)
                    <div class="alert alert-danger">
                        <i class="bx bx-error"></i> <strong>Overdue!</strong><br>
                        This issue has exceeded its priority timeline.
                    </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.inventory.issues.show', $inventoryIssue) }}" 
                           class="btn btn-info btn-sm w-100">
                            <i class="bx bx-show"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-set resolved date when status changes to resolved/closed
    const statusSelect = document.getElementById('status');
    const resolvedDateInput = document.getElementById('resolved_date');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'resolved' || this.value === 'closed') {
            if (!resolvedDateInput.value) {
                resolvedDateInput.value = new Date().toISOString().split('T')[0];
            }
        }
    });
});
</script>
@endsection

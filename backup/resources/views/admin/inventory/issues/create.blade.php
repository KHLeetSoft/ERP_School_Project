@extends('admin.layout.app')

@section('title', 'Report New Inventory Issue')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Report New Inventory Issue</h1>
            <p class="text-muted">Report a new issue with inventory items</p>
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
                    <form action="{{ route('admin.inventory.issues.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6 mb-3">
                                <label for="inventory_item_id" class="form-label">Inventory Item <span class="text-danger">*</span></label>
                                <select class="form-select @error('inventory_item_id') is-invalid @enderror" 
                                        id="inventory_item_id" name="inventory_item_id" required>
                                    <option value="">Select an item</option>
                                    @foreach($inventoryItems as $item)
                                        <option value="{{ $item->id }}" {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
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
                                        <option value="{{ $key }}" {{ old('issue_type') == $key ? 'selected' : '' }}>
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
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Brief description of the issue" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Detailed description of the issue..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Priority and Status -->
                        <h6 class="mb-3">Priority & Status</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" name="priority" required>
                                    <option value="">Select priority</option>
                                    @foreach($priorities as $key => $value)
                                        <option value="{{ $key }}" {{ old('priority', 'medium') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity_affected" class="form-label">Quantity Affected <span class="text-danger">*</span></label>
                                <input type="number" min="1" class="form-control @error('quantity_affected') is-invalid @enderror" 
                                       id="quantity_affected" name="quantity_affected" value="{{ old('quantity_affected', 1) }}" required>
                                @error('quantity_affected')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Cost and Date -->
                        <h6 class="mb-3">Cost & Date Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estimated_cost" class="form-label">Estimated Cost (₹)</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('estimated_cost') is-invalid @enderror" 
                                       id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost') }}"
                                       placeholder="0.00">
                                @error('estimated_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                       id="issue_date" name="issue_date" 
                                       value="{{ old('issue_date', now()->format('Y-m-d')) }}" 
                                       max="{{ now()->format('Y-m-d') }}" required>
                                @error('issue_date')
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
                                       id="reported_by" name="reported_by" value="{{ old('reported_by') }}"
                                       placeholder="Staff member name">
                                @error('reported_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="assigned_to" class="form-label">Assigned To</label>
                                <input type="text" class="form-control @error('assigned_to') is-invalid @enderror" 
                                       id="assigned_to" name="assigned_to" value="{{ old('assigned_to') }}"
                                       placeholder="Staff member assigned to fix">
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}"
                                       placeholder="Where did the issue occur?">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Attachments -->
                        <h6 class="mb-3">Attachments</h6>
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
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
                                <i class="bx bx-save"></i> Report Issue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Issue Reporting Guidelines</h6>
                </div>
                <div class="card-body">
                    <h6>Priority Levels</h6>
                    <ul class="small">
                        <li><strong>Critical:</strong> Immediate attention required (1 day)</li>
                        <li><strong>High:</strong> Urgent attention required (3 days)</li>
                        <li><strong>Medium:</strong> Normal priority (7 days)</li>
                        <li><strong>Low:</strong> Can be addressed later (14 days)</li>
                    </ul>

                    <h6 class="mt-3">Issue Types</h6>
                    <ul class="small">
                        <li><strong>Damaged:</strong> Physical damage to items</li>
                        <li><strong>Lost:</strong> Items that cannot be located</li>
                        <li><strong>Stolen:</strong> Items that have been stolen</li>
                        <li><strong>Maintenance:</strong> Items requiring maintenance</li>
                        <li><strong>Other:</strong> Any other type of issue</li>
                    </ul>

                    <h6 class="mt-3">Best Practices</h6>
                    <ul class="small">
                        <li>Provide clear, detailed descriptions</li>
                        <li>Include photos when possible</li>
                        <li>Set appropriate priority levels</li>
                        <li>Assign issues to specific staff members</li>
                        <li>Update status regularly</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill current date if not set
    const issueDateInput = document.getElementById('issue_date');
    if (!issueDateInput.value) {
        issueDateInput.value = new Date().toISOString().split('T')[0];
    }

    // Set max date to today
    issueDateInput.max = new Date().toISOString().split('T')[0];
});
</script>
@endsection

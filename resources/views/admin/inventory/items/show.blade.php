@extends('admin.layout.app')

@section('title', 'Inventory Item Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $inventoryItem->name }}</h1>
            <p class="text-muted">Inventory item details and information</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.inventory.items.edit', $inventoryItem) }}" class="btn btn-warning">
                <i class="bx bx-edit"></i> Edit Item
            </a>
            <a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Items
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Item Name</label>
                                <p class="form-control-plaintext">{{ $inventoryItem->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">SKU</label>
                                <p class="form-control-plaintext"><code>{{ $inventoryItem->sku }}</code></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <p class="form-control-plaintext">{{ $inventoryItem->category ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Supplier</label>
                                <p class="form-control-plaintext">{{ $inventoryItem->supplier ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <p class="form-control-plaintext">{{ $inventoryItem->description ?? 'No description provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quantity & Pricing -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quantity & Pricing</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-primary">{{ $inventoryItem->quantity }}</h4>
                                <small class="text-muted">Current Quantity</small>
                                @if($inventoryItem->unit)
                                    <br><small class="text-muted">{{ $inventoryItem->unit }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-warning">{{ $inventoryItem->min_quantity }}</h4>
                                <small class="text-muted">Minimum Quantity</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-success">₹{{ number_format($inventoryItem->price, 2) }}</h4>
                                <small class="text-muted">Unit Price</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-info">₹{{ number_format($inventoryItem->price * $inventoryItem->quantity, 2) }}</h4>
                                <small class="text-muted">Total Value</small>
                            </div>
                        </div>
                    </div>

                    @if($inventoryItem->is_low_stock)
                    <div class="alert alert-warning mt-3">
                        <i class="bx bx-error"></i> <strong>Low Stock Alert!</strong>
                        Current quantity ({{ $inventoryItem->quantity }}) is at or below minimum level ({{ $inventoryItem->min_quantity }}).
                    </div>
                    @endif
                </div>
            </div>

            <!-- Important Dates -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Important Dates</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Purchase Date</label>
                                <p class="form-control-plaintext">
                                    {{ $inventoryItem->purchase_date ? $inventoryItem->purchase_date->format('M d, Y') : 'Not specified' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Expiry Date</label>
                                <p class="form-control-plaintext">
                                    @if($inventoryItem->expiry_date)
                                        <span class="{{ $inventoryItem->expiry_date < now() ? 'text-danger' : ($inventoryItem->expiry_date <= now()->addDays(30) ? 'text-warning' : '') }}">
                                            {{ $inventoryItem->expiry_date->format('M d, Y') }}
                                        </span>
                                        @if($inventoryItem->expiry_date < now())
                                            <span class="badge bg-danger ms-2">Expired</span>
                                        @elseif($inventoryItem->expiry_date <= now()->addDays(30))
                                            <span class="badge bg-warning text-dark ms-2">Expiring Soon</span>
                                        @endif
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Storage Location</label>
                                <p class="form-control-plaintext">{{ $inventoryItem->location ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    @if($inventoryItem->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notes</label>
                                <p class="form-control-plaintext">{{ $inventoryItem->notes ?? 'No additional notes' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Item Image -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Item Image</h6>
                </div>
                <div class="card-body text-center">
                    @if($inventoryItem->image)
                        <img src="{{ asset('storage/' . $inventoryItem->image) }}" 
                             alt="{{ $inventoryItem->name }}" 
                             class="img-fluid rounded" 
                             style="max-height: 300px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                             style="height: 200px;">
                            <div class="text-center">
                                <i class="bx bx-package text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No image available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.inventory.items.edit', $inventoryItem) }}" 
                           class="btn btn-warning">
                            <i class="bx bx-edit"></i> Edit Item
                        </a>
                        
                        <button type="button" class="btn btn-{{ $inventoryItem->is_active ? 'secondary' : 'success' }}" 
                                onclick="toggleStatus({{ $inventoryItem->id }})">
                            <i class="bx bx-{{ $inventoryItem->is_active ? 'pause' : 'play' }}"></i> 
                            {{ $inventoryItem->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                        
                        <a href="{{ route('admin.inventory.items.index') }}" 
                           class="btn btn-outline-primary">
                            <i class="bx bx-list-ul"></i> View All Items
                        </a>
                    </div>
                </div>
            </div>

            <!-- Item Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Item Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Created</small><br>
                        <strong>{{ $inventoryItem->created_at->format('M d, Y \a\t g:i A') }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Last Updated</small><br>
                        <strong>{{ $inventoryItem->updated_at->format('M d, Y \a\t g:i A') }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Days Since Created</small><br>
                        <strong>{{ $inventoryItem->created_at->diffInDays(now()) }} days</strong>
                    </div>

                    @if($inventoryItem->expiry_date)
                    <div class="mb-3">
                        <small class="text-muted">Days Until Expiry</small><br>
                        <strong class="{{ $inventoryItem->expiry_date < now() ? 'text-danger' : ($inventoryItem->expiry_date <= now()->addDays(30) ? 'text-warning' : '') }}">
                            @if($inventoryItem->expiry_date < now())
                                Expired {{ $inventoryItem->expiry_date->diffInDays(now()) }} days ago
                            @else
                                {{ $inventoryItem->expiry_date->diffInDays(now()) }} days
                            @endif
                        </strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Delete Item -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Danger Zone</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Once you delete an item, there is no going back. Please be certain.</p>
                    <form action="{{ route('admin.inventory.items.destroy', $inventoryItem) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="bx bx-trash"></i> Delete Item
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(itemId) {
    fetch(`/admin/inventory/items/${itemId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to update the status
            location.reload();
        } else {
            alert('Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>
@endsection

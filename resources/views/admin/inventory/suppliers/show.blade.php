@extends('admin.layout.app')

@section('title', 'Supplier Details - ' . $supplier->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Supplier Details</h1>
        <div>
            <a href="{{ route('admin.inventory.suppliers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Suppliers
            </a>
            <a href="{{ route('admin.inventory.suppliers.edit', $supplier) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit Supplier
            </a>
        </div>
    </div>

    <!-- Supplier Information -->
    <div class="row">
        <!-- Basic Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($supplier->logo)
                                <img src="{{ Storage::url($supplier->logo) }}" alt="{{ $supplier->name }}" 
                                     class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px;">
                                    <i class="fas fa-truck fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h4 class="text-primary">{{ $supplier->name }}</h4>
                            @if($supplier->company)
                                <h6 class="text-muted">{{ $supplier->company }}</h6>
                            @endif
                            @if($supplier->brand)
                                <p class="mb-1"><strong>Brand:</strong> {{ $supplier->brand }}</p>
                            @endif
                            @if($supplier->contact_person)
                                <p class="mb-1"><strong>Contact Person:</strong> {{ $supplier->contact_person }}</p>
                            @endif
                            
                            <div class="mt-3">
                                <span class="badge badge-{{ $supplier->status_badge }} badge-lg">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                                @php
                                    $verification = $supplier->verification_status;
                                @endphp
                                <span class="badge badge-{{ $verification['badge'] }} badge-lg ml-2">
                                    <i class="fas fa-{{ $verification['status'] === 'verified' ? 'check' : 'exclamation' }}"></i>
                                    {{ $verification['text'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($supplier->email)
                                <p><strong><i class="fas fa-envelope text-muted"></i> Email:</strong> 
                                   <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a></p>
                            @endif
                            @if($supplier->phone)
                                <p><strong><i class="fas fa-phone text-muted"></i> Phone:</strong> 
                                   <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a></p>
                            @endif
                            @if($supplier->mobile)
                                <p><strong><i class="fas fa-mobile-alt text-muted"></i> Mobile:</strong> 
                                   <a href="tel:{{ $supplier->mobile }}">{{ $supplier->mobile }}</a></p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($supplier->website)
                                <p><strong><i class="fas fa-globe text-muted"></i> Website:</strong> 
                                   <a href="{{ $supplier->website }}" target="_blank">{{ $supplier->website }}</a></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Address Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @if($supplier->full_address)
                                <p><strong>Address:</strong> {{ $supplier->full_address }}</p>
                            @else
                                <p class="text-muted">No address information available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Business Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($supplier->gst_number)
                                <p><strong>GST Number:</strong> {{ $supplier->gst_number }}</p>
                            @endif
                            @if($supplier->pan_number)
                                <p><strong>PAN Number:</strong> {{ $supplier->pan_number }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Credit Limit:</strong> {{ $supplier->formatted_credit_limit }}</p>
                            <p><strong>Payment Terms:</strong> {{ $supplier->payment_terms_days }} days</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($supplier->notes)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
                </div>
                <div class="card-body">
                    <p>{{ $supplier->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.inventory.suppliers.edit', $supplier) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Supplier
                        </a>
                        <button type="button" class="btn btn-{{ $supplier->is_verified ? 'warning' : 'success' }} toggle-verification" 
                                data-id="{{ $supplier->id }}">
                            <i class="fas fa-{{ $supplier->is_verified ? 'times' : 'check' }}"></i>
                            {{ $supplier->is_verified ? 'Unverify' : 'Verify' }} Supplier
                        </button>
                        <button type="button" class="btn btn-{{ $supplier->status === 'active' ? 'secondary' : 'success' }} toggle-status" 
                                data-id="{{ $supplier->id }}">
                            <i class="fas fa-{{ $supplier->status === 'active' ? 'pause' : 'play' }}"></i>
                            {{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }} Supplier
                        </button>
                        <form method="POST" action="{{ route('admin.inventory.suppliers.destroy', $supplier) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Delete Supplier
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $supplier->getTotalItems() }}</h4>
                                <small class="text-muted">Items Supplied</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">₹{{ number_format($supplier->getTotalPurchases(), 2) }}</h4>
                            <small class="text-muted">Total Purchases</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            @if($supplier->documents && count($supplier->documents) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Documents</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($supplier->documents as $index => $document)
                            <div class="list-group-item d-flex justify-content-between align-items-center p-2">
                                <div class="small">
                                    <i class="fas fa-file"></i> Document {{ $index + 1 }}
                                </div>
                                <a href="{{ Storage::url($document) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Verification Information -->
            @if($supplier->is_verified)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Verification Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Verified By:</strong> {{ $supplier->verified_by }}</p>
                    <p><strong>Verified On:</strong> {{ $supplier->verified_at ? $supplier->verified_at->format('M d, Y h:i A') : 'N/A' }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Related Items -->
    @if($supplier->inventoryItems->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Supplied Items</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplier->inventoryItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" 
                                                 class="img-thumbnail me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 30px; height: 30px;">
                                                <i class="fas fa-box text-muted" style="font-size: 12px;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $item->name }}</div>
                                            <small class="text-muted">{{ $item->sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>
                                    @if($item->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle verification status
    $('.toggle-verification').click(function() {
        const supplierId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: `{{ url('admin/inventory/suppliers') }}/${supplierId}/toggle-verification`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Reload page to update verification status
                    location.reload();
                }
            },
            error: function() {
                alert('Error updating verification status');
            }
        });
    });

    // Toggle status
    $('.toggle-status').click(function() {
        const supplierId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: `{{ url('admin/inventory/suppliers') }}/${supplierId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Reload page to update status
                    location.reload();
                }
            },
            error: function() {
                alert('Error updating status');
            }
        });
    });
});
</script>
@endpush

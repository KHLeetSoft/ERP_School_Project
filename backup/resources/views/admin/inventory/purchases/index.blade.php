@extends('admin.layout.app')

@section('title', 'Purchase Orders Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Purchase Orders Management</h1>
        <div>
            <a href="{{ route('admin.inventory.purchases.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create Purchase Order
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_purchases'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_purchases'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Received Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['received_purchases'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['total_amount'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.purchases.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by PO number, reference...">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="payment_status" class="form-label">Payment Status</label>
                    <select class="form-control" id="payment_status" name="payment_status">
                        <option value="">All Payment Status</option>
                        @foreach($paymentStatuses as $key => $label)
                            <option value="{{ $key }}" {{ request('payment_status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select class="form-control" id="supplier_id" name="supplier_id">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-1 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.inventory.purchases.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Purchase Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Orders</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="purchasesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Expected Delivery</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Total Amount</th>
                            <th>Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $purchase->purchase_number }}</div>
                                    @if($purchase->reference_number)
                                        <small class="text-muted">Ref: {{ $purchase->reference_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($purchase->supplier->logo)
                                            <img src="{{ Storage::url($purchase->supplier->logo) }}" alt="{{ $purchase->supplier->name }}" 
                                                 class="img-thumbnail me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 30px; height: 30px;">
                                                <i class="fas fa-truck text-muted" style="font-size: 12px;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $purchase->supplier->name }}</div>
                                            @if($purchase->supplier->company)
                                                <small class="text-muted">{{ $purchase->supplier->company }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="font-weight-bold">{{ $purchase->purchase_date->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $purchase->purchase_date->format('h:i A') }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($purchase->expected_delivery_date)
                                        <div class="small">
                                            <div class="font-weight-bold">{{ $purchase->expected_delivery_date->format('M d, Y') }}</div>
                                            @if($purchase->is_overdue)
                                                <div class="text-danger">Overdue by {{ $purchase->days_overdue }} days</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $purchase->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $purchase->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $purchase->payment_status_badge }}">
                                        {{ ucfirst($purchase->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <div class="font-weight-bold">{{ $purchase->formatted_total }}</div>
                                        @if($purchase->balance_amount > 0)
                                            <small class="text-muted">Balance: {{ $purchase->formatted_balance }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <div class="font-weight-bold">{{ $purchase->purchaseItems->count() }}</div>
                                        <small class="text-muted">items</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.inventory.purchases.show', $purchase) }}" 
                                           class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($purchase->status === 'draft')
                                            <a href="{{ route('admin.inventory.purchases.edit', $purchase) }}" 
                                               class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        @if($purchase->status === 'pending')
                                            <button type="button" class="btn btn-success btn-sm approve-purchase" 
                                                    data-id="{{ $purchase->id }}" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        
                                        @if($purchase->status === 'approved')
                                            <button type="button" class="btn btn-primary btn-sm mark-ordered" 
                                                    data-id="{{ $purchase->id }}" title="Mark as Ordered">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                        @endif
                                        
                                        @if(in_array($purchase->status, ['ordered', 'partially_received']))
                                            <button type="button" class="btn btn-success btn-sm mark-received" 
                                                    data-id="{{ $purchase->id }}" title="Mark as Received">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        @endif
                                        
                                        @if(!in_array($purchase->status, ['received', 'completed', 'cancelled']))
                                            <button type="button" class="btn btn-danger btn-sm cancel-purchase" 
                                                    data-id="{{ $purchase->id }}" title="Cancel">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No purchase orders found</h5>
                                    <p class="text-muted">Start by creating your first purchase order.</p>
                                    <a href="{{ route('admin.inventory.purchases.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Purchase Order
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $purchases->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Approve purchase
    $('.approve-purchase').click(function() {
        const purchaseId = $(this).data('id');
        const button = $(this);
        
        if (confirm('Are you sure you want to approve this purchase order?')) {
            $.ajax({
                url: `{{ url('admin/inventory/purchases') }}/${purchaseId}/approve`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error approving purchase order');
                }
            });
        }
    });

    // Mark as ordered
    $('.mark-ordered').click(function() {
        const purchaseId = $(this).data('id');
        const button = $(this);
        
        if (confirm('Are you sure you want to mark this purchase order as ordered?')) {
            $.ajax({
                url: `{{ url('admin/inventory/purchases') }}/${purchaseId}/mark-ordered`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error updating purchase order');
                }
            });
        }
    });

    // Mark as received
    $('.mark-received').click(function() {
        const purchaseId = $(this).data('id');
        const button = $(this);
        
        if (confirm('Are you sure you want to mark this purchase order as received? This will update inventory stock.')) {
            $.ajax({
                url: `{{ url('admin/inventory/purchases') }}/${purchaseId}/mark-received`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error updating purchase order');
                }
            });
        }
    });

    // Cancel purchase
    $('.cancel-purchase').click(function() {
        const purchaseId = $(this).data('id');
        const button = $(this);
        
        if (confirm('Are you sure you want to cancel this purchase order?')) {
            $.ajax({
                url: `{{ url('admin/inventory/purchases') }}/${purchaseId}/cancel`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error cancelling purchase order');
                }
            });
        }
    });
});
</script>
@endpush

@extends('admin.layout.app')

@section('title', 'Purchase Order - ' . $purchase->purchase_number)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Purchase Order Details</h1>
        <div>
            <a href="{{ route('admin.inventory.purchases.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Purchase Orders
            </a>
            @if($purchase->status === 'draft')
                <a href="{{ route('admin.inventory.purchases.edit', $purchase) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edit Purchase Order
                </a>
            @endif
        </div>
    </div>

    <!-- Purchase Order Information -->
    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Purchase Order Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Purchase Number:</strong> {{ $purchase->purchase_number }}</p>
                            @if($purchase->reference_number)
                                <p><strong>Reference Number:</strong> {{ $purchase->reference_number }}</p>
                            @endif
                            <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date->format('M d, Y') }}</p>
                            @if($purchase->expected_delivery_date)
                                <p><strong>Expected Delivery:</strong> {{ $purchase->expected_delivery_date->format('M d, Y') }}</p>
                            @endif
                            @if($purchase->actual_delivery_date)
                                <p><strong>Actual Delivery:</strong> {{ $purchase->actual_delivery_date->format('M d, Y') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <span class="badge badge-{{ $purchase->status_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $purchase->status)) }}
                                </span>
                            </p>
                            <p><strong>Payment Status:</strong> 
                                <span class="badge badge-{{ $purchase->payment_status_badge }}">
                                    {{ ucfirst($purchase->payment_status) }}
                                </span>
                            </p>
                            @if($purchase->payment_method)
                                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $purchase->payment_method)) }}</p>
                            @endif
                            @if($purchase->is_overdue)
                                <p class="text-danger"><strong>Overdue:</strong> {{ $purchase->days_overdue }} days</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Supplier Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            @if($purchase->supplier->logo)
                                <img src="{{ Storage::url($purchase->supplier->logo) }}" alt="{{ $purchase->supplier->name }}" 
                                     class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-truck fa-2x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-10">
                            <h5 class="text-primary">{{ $purchase->supplier->name }}</h5>
                            @if($purchase->supplier->company)
                                <h6 class="text-muted">{{ $purchase->supplier->company }}</h6>
                            @endif
                            @if($purchase->supplier->contact_person)
                                <p class="mb-1"><strong>Contact Person:</strong> {{ $purchase->supplier->contact_person }}</p>
                            @endif
                            @if($purchase->supplier->email)
                                <p class="mb-1"><strong>Email:</strong> 
                                    <a href="mailto:{{ $purchase->supplier->email }}">{{ $purchase->supplier->email }}</a>
                                </p>
                            @endif
                            @if($purchase->supplier->phone)
                                <p class="mb-1"><strong>Phone:</strong> 
                                    <a href="tel:{{ $purchase->supplier->phone }}">{{ $purchase->supplier->phone }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Purchase Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Cost</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
                                    <th>Total Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->purchaseItems as $item)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ $item->item_name }}</div>
                                            @if($item->description)
                                                <small class="text-muted">{{ $item->description }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->item_sku }}</td>
                                        <td>
                                            <div class="text-center">
                                                <div class="font-weight-bold">{{ $item->quantity_ordered }}</div>
                                                <small class="text-muted">{{ $item->unit }}</small>
                                            </div>
                                        </td>
                                        <td class="text-right">{{ $item->formatted_unit_cost }}</td>
                                        <td class="text-right">
                                            @if($item->discount_percentage > 0)
                                                {{ $item->discount_percentage }}%<br>
                                                <small class="text-muted">{{ $item->formatted_discount_amount }}</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($item->tax_percentage > 0)
                                                {{ $item->tax_percentage }}%<br>
                                                <small class="text-muted">{{ $item->formatted_tax_amount }}</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right font-weight-bold">{{ $item->formatted_total_cost }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->receipt_status_badge }}">
                                                {{ $item->receipt_status_text }}
                                            </span>
                                            @if($item->quantity_received > 0)
                                                <br><small class="text-muted">{{ $item->quantity_received }} received</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            @if($purchase->delivery_address || $purchase->billing_address)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Address Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($purchase->delivery_address)
                        <div class="col-md-6">
                            <h6>Delivery Address</h6>
                            <p>{{ $purchase->delivery_address }}</p>
                        </div>
                        @endif
                        @if($purchase->billing_address)
                        <div class="col-md-6">
                            <h6>Billing Address</h6>
                            <p>{{ $purchase->billing_address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes and Terms -->
            @if($purchase->notes || $purchase->terms_conditions)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
                </div>
                <div class="card-body">
                    @if($purchase->notes)
                    <div class="mb-3">
                        <h6>Notes</h6>
                        <p>{{ $purchase->notes }}</p>
                    </div>
                    @endif
                    @if($purchase->terms_conditions)
                    <div>
                        <h6>Terms & Conditions</h6>
                        <p>{{ $purchase->terms_conditions }}</p>
                    </div>
                    @endif
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
                        @if($purchase->status === 'draft')
                            <a href="{{ route('admin.inventory.purchases.edit', $purchase) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Purchase Order
                            </a>
                        @endif
                        
                        @if($purchase->status === 'pending')
                            <button type="button" class="btn btn-success approve-purchase" data-id="{{ $purchase->id }}">
                                <i class="fas fa-check"></i> Approve Purchase Order
                            </button>
                        @endif
                        
                        @if($purchase->status === 'approved')
                            <button type="button" class="btn btn-primary mark-ordered" data-id="{{ $purchase->id }}">
                                <i class="fas fa-shopping-cart"></i> Mark as Ordered
                            </button>
                        @endif
                        
                        @if(in_array($purchase->status, ['ordered', 'partially_received']))
                            <button type="button" class="btn btn-success mark-received" data-id="{{ $purchase->id }}">
                                <i class="fas fa-check-double"></i> Mark as Received
                            </button>
                        @endif
                        
                        @if(!in_array($purchase->status, ['received', 'completed', 'cancelled']))
                            <button type="button" class="btn btn-danger cancel-purchase" data-id="{{ $purchase->id }}">
                                <i class="fas fa-times"></i> Cancel Purchase Order
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Financial Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6"><strong>Subtotal:</strong></div>
                        <div class="col-6 text-right">{{ $purchase->formatted_subtotal }}</div>
                    </div>
                    <div class="row">
                        <div class="col-6"><strong>Tax:</strong></div>
                        <div class="col-6 text-right">₹{{ number_format($purchase->tax_amount, 2) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-6"><strong>Discount:</strong></div>
                        <div class="col-6 text-right">₹{{ number_format($purchase->discount_amount, 2) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-6"><strong>Shipping:</strong></div>
                        <div class="col-6 text-right">₹{{ number_format($purchase->shipping_cost, 2) }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6"><strong>Total Amount:</strong></div>
                        <div class="col-6 text-right font-weight-bold">{{ $purchase->formatted_total }}</div>
                    </div>
                    <div class="row">
                        <div class="col-6"><strong>Paid Amount:</strong></div>
                        <div class="col-6 text-right">{{ $purchase->formatted_paid_amount }}</div>
                    </div>
                    <div class="row">
                        <div class="col-6"><strong>Balance:</strong></div>
                        <div class="col-6 text-right font-weight-bold">{{ $purchase->formatted_balance }}</div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Prepared By:</strong> {{ $purchase->prepared_by }}</p>
                    <p><strong>Created:</strong> {{ $purchase->created_at->format('M d, Y h:i A') }}</p>
                    @if($purchase->approved_by)
                        <p><strong>Approved By:</strong> {{ $purchase->approved_by }}</p>
                        <p><strong>Approved At:</strong> {{ $purchase->approved_at->format('M d, Y h:i A') }}</p>
                    @endif
                    @if($purchase->received_by)
                        <p><strong>Received By:</strong> {{ $purchase->received_by }}</p>
                        <p><strong>Received At:</strong> {{ $purchase->received_at->format('M d, Y h:i A') }}</p>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            @if($purchase->attachments && count($purchase->attachments) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Documents</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($purchase->attachments as $index => $attachment)
                            <div class="list-group-item d-flex justify-content-between align-items-center p-2">
                                <div class="small">
                                    <i class="fas fa-file"></i> Document {{ $index + 1 }}
                                </div>
                                <a href="{{ Storage::url($attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Approve purchase
    $('.approve-purchase').click(function() {
        const purchaseId = $(this).data('id');
        
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
@endsection

@extends('admin.layout.app')

@section('title', 'Edit Purchase Order - ' . $purchase->purchase_number)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Purchase Order</h1>
        <div>
            <a href="{{ route('admin.inventory.purchases.show', $purchase) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Purchase Order
            </a>
        </div>
    </div>

    <!-- Purchase Order Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Order Information</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventory.purchases.update', $purchase) }}" enctype="multipart/form-data" id="purchaseForm">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">Basic Information</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select class="form-control @error('supplier_id') is-invalid @enderror" 
                                id="supplier_id" name="supplier_id" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }} @if($supplier->company) - {{ $supplier->company }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                               id="purchase_date" name="purchase_date" 
                               value="{{ old('purchase_date', $purchase->purchase_date->toDateString()) }}" required>
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
                        <input type="date" class="form-control @error('expected_delivery_date') is-invalid @enderror" 
                               id="expected_delivery_date" name="expected_delivery_date" 
                               value="{{ old('expected_delivery_date', $purchase->expected_delivery_date?->toDateString()) }}">
                        @error('expected_delivery_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $purchase->status) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('payment_status') is-invalid @enderror" 
                                id="payment_status" name="payment_status" required>
                            @foreach($paymentStatuses as $key => $label)
                                <option value="{{ $key }}" {{ old('payment_status', $purchase->payment_status) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-control @error('payment_method') is-invalid @enderror" 
                                id="payment_method" name="payment_method">
                            <option value="">Select Payment Method</option>
                            @foreach($paymentMethods as $key => $label)
                                <option value="{{ $key }}" {{ old('payment_method', $purchase->payment_method) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                               id="reference_number" name="reference_number" 
                               value="{{ old('reference_number', $purchase->reference_number) }}" 
                               placeholder="PO number, invoice number, etc.">
                        @error('reference_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="shipping_cost" class="form-label">Shipping Cost (₹)</label>
                        <input type="number" class="form-control @error('shipping_cost') is-invalid @enderror" 
                               id="shipping_cost" name="shipping_cost" 
                               value="{{ old('shipping_cost', $purchase->shipping_cost) }}" 
                               step="0.01" min="0">
                        @error('shipping_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Address Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">Address Information</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="delivery_address" class="form-label">Delivery Address</label>
                        <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                                  id="delivery_address" name="delivery_address" rows="2" 
                                  placeholder="Delivery address for this purchase">{{ old('delivery_address', $purchase->delivery_address) }}</textarea>
                        @error('delivery_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="billing_address" class="form-label">Billing Address</label>
                        <textarea class="form-control @error('billing_address') is-invalid @enderror" 
                                  id="billing_address" name="billing_address" rows="2" 
                                  placeholder="Billing address for this purchase">{{ old('billing_address', $purchase->billing_address) }}</textarea>
                        @error('billing_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Purchase Items -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">Purchase Items</h6>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="itemsTable">
                        <thead>
                            <tr>
                                <th width="30%">Item</th>
                                <th width="10%">Quantity</th>
                                <th width="12%">Unit Cost</th>
                                <th width="8%">Discount %</th>
                                <th width="8%">Tax %</th>
                                <th width="10%">Total Cost</th>
                                <th width="12%">Expiry Date</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            @foreach($purchase->purchaseItems as $index => $item)
                                <tr id="itemRow_{{ $index }}">
                                    <td>
                                        <select class="form-control item-select" name="items[{{ $index }}][inventory_item_id]" required>
                                            <option value="">Select Item</option>
                                            @foreach($inventoryItems as $inventoryItem)
                                                <option value="{{ $inventoryItem->id }}" 
                                                        data-sku="{{ $inventoryItem->sku }}" 
                                                        data-unit="{{ $inventoryItem->unit }}" 
                                                        data-price="{{ $inventoryItem->price }}"
                                                        {{ $item->inventory_item_id == $inventoryItem->id ? 'selected' : '' }}>
                                                    {{ $inventoryItem->name }} ({{ $inventoryItem->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="items[{{ $index }}][item_sku]" class="item-sku" value="{{ $item->item_sku }}">
                                        <input type="hidden" name="items[{{ $index }}][unit]" class="item-unit" value="{{ $item->unit }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity-input" name="items[{{ $index }}][quantity_ordered]" 
                                               min="1" value="{{ $item->quantity_ordered }}" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control unit-cost-input" name="items[{{ $index }}][unit_cost]" 
                                               step="0.01" min="0" value="{{ $item->unit_cost }}" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control discount-input" name="items[{{ $index }}][discount_percentage]" 
                                               step="0.01" min="0" max="100" value="{{ $item->discount_percentage }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control tax-input" name="items[{{ $index }}][tax_percentage]" 
                                               step="0.01" min="0" max="100" value="{{ $item->tax_percentage }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control total-cost-display" readonly>
                                        <input type="hidden" class="total-cost-input" name="items[{{ $index }}][total_cost]">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="items[{{ $index }}][expiry_date]" 
                                               value="{{ $item->expiry_date?->toDateString() }}"
                                               min="{{ now()->addDay()->toDateString() }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-success btn-sm" id="addItem">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                </div>

                <!-- Totals -->
                <div class="row mb-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6"><strong>Subtotal:</strong></div>
                                    <div class="col-6 text-right" id="subtotal">₹{{ number_format($purchase->subtotal, 2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Tax:</strong></div>
                                    <div class="col-6 text-right" id="taxAmount">₹{{ number_format($purchase->tax_amount, 2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Discount:</strong></div>
                                    <div class="col-6 text-right" id="discountAmount">₹{{ number_format($purchase->discount_amount, 2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Shipping:</strong></div>
                                    <div class="col-6 text-right" id="shippingCost">₹{{ number_format($purchase->shipping_cost, 2) }}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6"><strong>Total:</strong></div>
                                    <div class="col-6 text-right" id="totalAmount">₹{{ number_format($purchase->total_amount, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">Additional Information</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Additional notes about this purchase order">{{ old('notes', $purchase->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                        <textarea class="form-control @error('terms_conditions') is-invalid @enderror" 
                                  id="terms_conditions" name="terms_conditions" rows="3" 
                                  placeholder="Terms and conditions for this purchase order">{{ old('terms_conditions', $purchase->terms_conditions) }}</textarea>
                        @error('terms_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- File Attachments -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">File Attachments</h6>
                    </div>
                </div>

                @if($purchase->attachments && count($purchase->attachments) > 0)
                <div class="row mb-3">
                    <div class="col-12">
                        <h6>Current Documents:</h6>
                        <div class="list-group">
                            @foreach($purchase->attachments as $index => $attachment)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file"></i> Document {{ $index + 1 }}
                                    </div>
                                    <a href="{{ Storage::url($attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="attachments" class="form-label">Add New Documents</label>
                        <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" 
                               id="attachments" name="attachments[]" multiple 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Max size: 5MB per file, Formats: PDF, DOC, DOCX, JPG, PNG</small>
                        @error('attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-group text-right">
                    <a href="{{ route('admin.inventory.purchases.show', $purchase) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Purchase Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let itemIndex = {{ count($purchase->purchaseItems) - 1 }};

    // Add item row
    $('#addItem').click(function() {
        itemIndex++;
        const newRow = `
            <tr id="itemRow_${itemIndex}">
                <td>
                    <select class="form-control item-select" name="items[${itemIndex}][inventory_item_id]" required>
                        <option value="">Select Item</option>
                        @foreach($inventoryItems as $item)
                            <option value="{{ $item->id }}" data-sku="{{ $item->sku }}" data-unit="{{ $item->unit }}" data-price="{{ $item->price }}">
                                {{ $item->name }} ({{ $item->sku }})
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="items[${itemIndex}][item_sku]" class="item-sku">
                    <input type="hidden" name="items[${itemIndex}][unit]" class="item-unit">
                </td>
                <td>
                    <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity_ordered]" 
                           min="1" required>
                </td>
                <td>
                    <input type="number" class="form-control unit-cost-input" name="items[${itemIndex}][unit_cost]" 
                           step="0.01" min="0" required>
                </td>
                <td>
                    <input type="number" class="form-control discount-input" name="items[${itemIndex}][discount_percentage]" 
                           step="0.01" min="0" max="100" value="0">
                </td>
                <td>
                    <input type="number" class="form-control tax-input" name="items[${itemIndex}][tax_percentage]" 
                           step="0.01" min="0" max="100" value="0">
                </td>
                <td>
                    <input type="text" class="form-control total-cost-display" readonly>
                    <input type="hidden" class="total-cost-input" name="items[${itemIndex}][total_cost]">
                </td>
                <td>
                    <input type="date" class="form-control" name="items[${itemIndex}][expiry_date]" 
                           min="{{ now()->addDay()->toDateString() }}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#itemsTableBody').append(newRow);
        updateRemoveButtons();
    });

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        updateRemoveButtons();
        calculateTotals();
    });

    // Update remove buttons visibility
    function updateRemoveButtons() {
        const rows = $('#itemsTableBody tr').length;
        if (rows > 1) {
            $('.remove-item').show();
        } else {
            $('.remove-item').hide();
        }
    }

    // Item selection change
    $(document).on('change', '.item-select', function() {
        const selectedOption = $(this).find('option:selected');
        const row = $(this).closest('tr');
        
        if (selectedOption.val()) {
            row.find('.item-sku').val(selectedOption.data('sku'));
            row.find('.item-unit').val(selectedOption.data('unit'));
            if (!row.find('.unit-cost-input').val()) {
                row.find('.unit-cost-input').val(selectedOption.data('price'));
            }
            calculateItemTotal(row);
        }
    });

    // Calculate item total
    function calculateItemTotal(row) {
        const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
        const unitCost = parseFloat(row.find('.unit-cost-input').val()) || 0;
        const discountPercent = parseFloat(row.find('.discount-input').val()) || 0;
        const taxPercent = parseFloat(row.find('.tax-input').val()) || 0;

        const subtotal = quantity * unitCost;
        const discountAmount = subtotal * (discountPercent / 100);
        const taxableAmount = subtotal - discountAmount;
        const taxAmount = taxableAmount * (taxPercent / 100);
        const totalCost = taxableAmount + taxAmount;

        row.find('.total-cost-display').val('₹' + totalCost.toFixed(2));
        row.find('.total-cost-input').val(totalCost.toFixed(2));
        
        calculateTotals();
    }

    // Calculate totals
    function calculateTotals() {
        let subtotal = 0;
        let totalTax = 0;
        let totalDiscount = 0;

        $('#itemsTableBody tr').each(function() {
            const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
            const unitCost = parseFloat($(this).find('.unit-cost-input').val()) || 0;
            const discountPercent = parseFloat($(this).find('.discount-input').val()) || 0;
            const taxPercent = parseFloat($(this).find('.tax-input').val()) || 0;

            const itemSubtotal = quantity * unitCost;
            const itemDiscount = itemSubtotal * (discountPercent / 100);
            const itemTaxableAmount = itemSubtotal - itemDiscount;
            const itemTax = itemTaxableAmount * (taxPercent / 100);

            subtotal += itemSubtotal;
            totalDiscount += itemDiscount;
            totalTax += itemTax;
        });

        const shippingCost = parseFloat($('#shipping_cost').val()) || 0;
        const totalAmount = subtotal + totalTax + shippingCost - totalDiscount;

        $('#subtotal').text('₹' + subtotal.toFixed(2));
        $('#taxAmount').text('₹' + totalTax.toFixed(2));
        $('#discountAmount').text('₹' + totalDiscount.toFixed(2));
        $('#shippingCost').text('₹' + shippingCost.toFixed(2));
        $('#totalAmount').text('₹' + totalAmount.toFixed(2));
    }

    // Event listeners for calculations
    $(document).on('input', '.quantity-input, .unit-cost-input, .discount-input, .tax-input', function() {
        calculateItemTotal($(this).closest('tr'));
    });

    $(document).on('input', '#shipping_cost', function() {
        calculateTotals();
    });

    // Form validation
    $('#purchaseForm').on('submit', function(e) {
        const itemCount = $('#itemsTableBody tr').length;
        if (itemCount === 0) {
            e.preventDefault();
            alert('Please add at least one item to the purchase order.');
            return false;
        }
    });

    // Initialize calculations for existing items
    $('#itemsTableBody tr').each(function() {
        calculateItemTotal($(this));
    });
    
    updateRemoveButtons();
});
</script>
@endsection

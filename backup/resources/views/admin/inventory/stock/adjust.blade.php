@extends('admin.layout.app')

@section('title', 'Adjust Stock - ' . $item->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Adjust Stock</h1>
        <div>
            <a href="{{ route('admin.inventory.stock.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Stock
            </a>
            <a href="{{ route('admin.inventory.stock.history', $item) }}" class="btn btn-info btn-sm">
                <i class="fas fa-history"></i> View History
            </a>
        </div>
    </div>

    <!-- Item Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Item Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" 
                             class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-box fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ $item->name }}</h5>
                            <p class="text-muted mb-1"><strong>SKU:</strong> {{ $item->sku }}</p>
                            <p class="text-muted mb-1"><strong>Category:</strong> {{ $item->category }}</p>
                            <p class="text-muted mb-1"><strong>Unit:</strong> {{ $item->unit }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h4 class="text-primary mb-0">{{ $item->quantity }}</h4>
                                        <small class="text-muted">Current Stock</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h4 class="text-warning mb-0">{{ $item->min_quantity }}</h4>
                                        <small class="text-muted">Min Quantity</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Stock Adjustment</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventory.stock.adjust.process', $item) }}" id="adjustmentForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="movement_type" class="form-label">Movement Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('movement_type') is-invalid @enderror" 
                                    id="movement_type" name="movement_type" required>
                                <option value="">Select Movement Type</option>
                                @foreach($movementTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('movement_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('movement_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity') }}" 
                                   min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="movement_date" class="form-label">Movement Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('movement_date') is-invalid @enderror" 
                                   id="movement_date" name="movement_date" 
                                   value="{{ old('movement_date', now()->toDateString()) }}" 
                                   max="{{ now()->toDateString() }}" required>
                            @error('movement_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference_type" class="form-label">Reference Type</label>
                            <select class="form-control @error('reference_type') is-invalid @enderror" 
                                    id="reference_type" name="reference_type">
                                <option value="">Select Reference Type</option>
                                @foreach($referenceTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('reference_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('reference_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                   id="reference_number" name="reference_number" 
                                   value="{{ old('reference_number') }}" 
                                   placeholder="Invoice number, PO number, etc.">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit_cost" class="form-label">Unit Cost (₹)</label>
                            <input type="number" class="form-control @error('unit_cost') is-invalid @enderror" 
                                   id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}" 
                                   step="0.01" min="0">
                            @error('unit_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location_from" class="form-label">Location From</label>
                            <input type="text" class="form-control @error('location_from') is-invalid @enderror" 
                                   id="location_from" name="location_from" 
                                   value="{{ old('location_from') }}" 
                                   placeholder="Source location">
                            @error('location_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location_to" class="form-label">Location To</label>
                            <input type="text" class="form-control @error('location_to') is-invalid @enderror" 
                                   id="location_to" name="location_to" 
                                   value="{{ old('location_to') }}" 
                                   placeholder="Destination location">
                            @error('location_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3" 
                              placeholder="Additional notes about this movement">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stock Impact Preview -->
                <div class="alert alert-info" id="stockPreview" style="display: none;">
                    <h6 class="alert-heading">Stock Impact Preview</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Current Stock:</strong> <span id="currentStock">{{ $item->quantity }}</span> {{ $item->unit }}
                        </div>
                        <div class="col-md-4">
                            <strong>Movement:</strong> <span id="movementQuantity" class="font-weight-bold"></span> {{ $item->unit }}
                        </div>
                        <div class="col-md-4">
                            <strong>New Stock:</strong> <span id="newStock" class="font-weight-bold"></span> {{ $item->unit }}
                        </div>
                    </div>
                </div>

                <div class="form-group text-right">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Process Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const currentStock = {{ $item->quantity }};
    
    function updateStockPreview() {
        const movementType = $('#movement_type').val();
        const quantity = parseInt($('#quantity').val()) || 0;
        
        if (movementType && quantity > 0) {
            let newStock;
            let movementText;
            
            if (['in', 'return'].includes(movementType)) {
                newStock = currentStock + quantity;
                movementText = '+' + quantity;
                $('#movementQuantity').removeClass('text-danger').addClass('text-success');
            } else {
                newStock = Math.max(0, currentStock - quantity);
                movementText = '-' + quantity;
                $('#movementQuantity').removeClass('text-success').addClass('text-danger');
            }
            
            $('#movementQuantity').text(movementText);
            $('#newStock').text(newStock);
            $('#stockPreview').show();
            
            // Warning for insufficient stock
            if (['out', 'damage', 'loss', 'transfer'].includes(movementType) && currentStock < quantity) {
                $('#stockPreview').removeClass('alert-info').addClass('alert-warning');
                $('#stockPreview .alert-heading').text('⚠️ Insufficient Stock Warning');
            } else {
                $('#stockPreview').removeClass('alert-warning').addClass('alert-info');
                $('#stockPreview .alert-heading').text('Stock Impact Preview');
            }
        } else {
            $('#stockPreview').hide();
        }
    }
    
    $('#movement_type, #quantity').on('change input', updateStockPreview);
    
    // Form validation
    $('#adjustmentForm').on('submit', function(e) {
        const movementType = $('#movement_type').val();
        const quantity = parseInt($('#quantity').val()) || 0;
        
        if (['out', 'damage', 'loss', 'transfer'].includes(movementType) && currentStock < quantity) {
            e.preventDefault();
            alert('Insufficient stock! Available quantity: ' + currentStock);
            return false;
        }
    });
});
</script>
@endpush

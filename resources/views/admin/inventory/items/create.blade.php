@extends('admin.layout.app')

@section('title', 'Add New Inventory Item')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Add New Inventory Item</h1>
            <p class="text-muted">Create a new inventory item for your school</p>
        </div>
        <a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Items
        </a>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Item Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.inventory.items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                       id="sku" name="sku" value="{{ old('sku') }}" required>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                       id="category" name="category" value="{{ old('category') }}"
                                       list="categoryList">
                                <datalist id="categoryList">
                                    <option value="Electronics">
                                    <option value="Furniture">
                                    <option value="Stationery">
                                    <option value="Sports Equipment">
                                    <option value="Laboratory Equipment">
                                    <option value="Books">
                                    <option value="Cleaning Supplies">
                                    <option value="Maintenance Tools">
                                </datalist>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="supplier" class="form-label">Supplier</label>
                                <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                       id="supplier" name="supplier" value="{{ old('supplier') }}">
                                @error('supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Pricing & Quantity -->
                        <h6 class="mb-3">Pricing & Quantity</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="quantity" class="form-label">Current Quantity <span class="text-danger">*</span></label>
                                <input type="number" min="0" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity', 0) }}" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="min_quantity" class="form-label">Minimum Quantity <span class="text-danger">*</span></label>
                                <input type="number" min="0" 
                                       class="form-control @error('min_quantity') is-invalid @enderror" 
                                       id="min_quantity" name="min_quantity" value="{{ old('min_quantity', 0) }}" required>
                                @error('min_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label">Unit</label>
                                <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit">
                                    <option value="">Select Unit</option>
                                    <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>Pieces</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                    <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram</option>
                                    <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>Liters</option>
                                    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliters</option>
                                    <option value="meters" {{ old('unit') == 'meters' ? 'selected' : '' }}>Meters</option>
                                    <option value="cm" {{ old('unit') == 'cm' ? 'selected' : '' }}>Centimeters</option>
                                    <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>Boxes</option>
                                    <option value="sets" {{ old('unit') == 'sets' ? 'selected' : '' }}>Sets</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Storage Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}"
                                       placeholder="e.g., Room 101, Storage A">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Dates -->
                        <h6 class="mb-3">Important Dates</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase_date" class="form-label">Purchase Date</label>
                                <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                       id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Image Upload -->
                        <h6 class="mb-3">Item Image</h6>
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Any additional information about this item...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Item is available for use)
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.inventory.items.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Create Item
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
                    <h6 class="m-0 font-weight-bold text-primary">Help & Tips</h6>
                </div>
                <div class="card-body">
                    <h6>SKU Guidelines</h6>
                    <ul class="small">
                        <li>Use a unique identifier for each item</li>
                        <li>Include category prefix (e.g., ELEC-001 for electronics)</li>
                        <li>Keep it short and memorable</li>
                    </ul>

                    <h6 class="mt-3">Quantity Management</h6>
                    <ul class="small">
                        <li>Set minimum quantity to get low stock alerts</li>
                        <li>Update quantities when items are used or restocked</li>
                        <li>Use appropriate units for accurate tracking</li>
                    </ul>

                    <h6 class="mt-3">Categories</h6>
                    <ul class="small">
                        <li>Electronics: Computers, tablets, projectors</li>
                        <li>Furniture: Desks, chairs, cabinets</li>
                        <li>Stationery: Pens, papers, notebooks</li>
                        <li>Sports Equipment: Balls, rackets, mats</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate SKU based on name and category
    const nameInput = document.getElementById('name');
    const categoryInput = document.getElementById('category');
    const skuInput = document.getElementById('sku');

    function generateSKU() {
        const name = nameInput.value.trim();
        const category = categoryInput.value.trim();
        
        if (name && category) {
            const categoryPrefix = category.substring(0, 4).toUpperCase();
            const namePrefix = name.substring(0, 3).toUpperCase();
            const timestamp = Date.now().toString().slice(-4);
            
            skuInput.value = `${categoryPrefix}-${namePrefix}-${timestamp}`;
        }
    }

    nameInput.addEventListener('blur', generateSKU);
    categoryInput.addEventListener('blur', generateSKU);

    // Set minimum quantity based on current quantity
    const quantityInput = document.getElementById('quantity');
    const minQuantityInput = document.getElementById('min_quantity');

    quantityInput.addEventListener('input', function() {
        const quantity = parseInt(this.value) || 0;
        if (minQuantityInput.value === '' || parseInt(minQuantityInput.value) > quantity) {
            minQuantityInput.value = Math.max(0, Math.floor(quantity * 0.1)); // 10% of current quantity
        }
    });
});
</script>
@endsection

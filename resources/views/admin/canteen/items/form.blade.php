@csrf
<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" required>
    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
<div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $item->price ?? 0) }}" required>
    @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
<div class="mb-3">
    <label class="form-label">Stock Quantity</label>
    <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $item->stock_quantity ?? 0) }}" required>
    @error('stock_quantity')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description ?? '') }}</textarea>
    @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>


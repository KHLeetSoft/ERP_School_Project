<div class="mb-3">
    <label class="form-label">School Name</label>
    <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $purchase->item_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Users Number</label>
    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $purchase->quantity ?? 1) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $purchase->price ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Vendor</label>
    <input type="text" name="vendor" class="form-control" value="{{ old('vendor', $purchase->vendor ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Purchase Date</label>
    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $purchase->purchase_date ?? now()->toDateString()) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Notes</label>
    <textarea name="notes" class="form-control">{{ old('notes', $purchase->notes ?? '') }}</textarea>
</div>

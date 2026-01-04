@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Item</label>
        <select name="canteen_item_id" class="form-select" required>
            <option value="">Select Item</option>
            @foreach($items as $it)
                <option value="{{ $it->id }}" data-price="{{ $it->price }}" {{ old('canteen_item_id', $sale->canteen_item_id ?? '') == $it->id ? 'selected' : '' }}>{{ $it->name }}</option>
            @endforeach
        </select>
        @error('canteen_item_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Quantity</label>
        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $sale->quantity ?? 1) }}" min="1" required>
        @error('quantity')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Unit Price</label>
        <input type="number" step="0.01" name="unit_price" class="form-control" value="{{ old('unit_price', $sale->unit_price ?? '') }}" required>
        @error('unit_price')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Sold At</label>
        <input type="datetime-local" name="sold_at" class="form-control" value="{{ old('sold_at', isset($sale->sold_at) ? $sale->sold_at->format('Y-m-d\TH:i') : '') }}">
        @error('sold_at')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Buyer Type</label>
        <input type="text" name="buyer_type" class="form-control" value="{{ old('buyer_type', $sale->buyer_type ?? '') }}" placeholder="student/teacher/guest">
    </div>
    <div class="col-md-4">
        <label class="form-label">Buyer ID</label>
        <input type="number" name="buyer_id" class="form-control" value="{{ old('buyer_id', $sale->buyer_id ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $sale->notes ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.querySelector('select[name="canteen_item_id"]');
    const priceInput = document.querySelector('input[name="unit_price"]');
    if (itemSelect && priceInput && !priceInput.value) {
        const setPrice = () => {
            const opt = itemSelect.options[itemSelect.selectedIndex];
            const price = opt ? opt.getAttribute('data-price') : '';
            if (price) priceInput.value = price;
        };
        itemSelect.addEventListener('change', setPrice);
        setPrice();
    }
});
</script>
@endpush



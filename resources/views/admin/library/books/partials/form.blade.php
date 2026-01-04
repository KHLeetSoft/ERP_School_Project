<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Title</label>
        <input type="text" name="title" value="{{ old('title', $book->title ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Author</label>
        <input type="text" name="author" value="{{ old('author', $book->author ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Genre</label>
        <input type="text" name="genre" value="{{ old('genre', $book->genre ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Published Year</label>
        <input type="number" name="published_year" value="{{ old('published_year', $book->published_year ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">ISBN</label>
        <input type="text" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" class="form-control">
    </div>
    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $book->description ?? '') }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">Stock Quantity</label>
        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $book->stock_quantity ?? 0) }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Shelf Location</label>
        <input type="text" name="shelf_location" value="{{ old('shelf_location', $book->shelf_location ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @php($current = old('status', $book->status ?? 'available'))
            <option value="available" {{ $current === 'available' ? 'selected' : '' }}>Available</option>
            <option value="checked_out" {{ $current === 'checked_out' ? 'selected' : '' }}>Checked Out</option>
            <option value="lost" {{ $current === 'lost' ? 'selected' : '' }}>Lost</option>
        </select>
    </div>
</div>



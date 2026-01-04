<div class="row g-3">
	<div class="col-md-6">
		<label class="form-label">Name</label>
		<input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="form-control" required>
		@error('name')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-6">
		<label class="form-label">Slug</label>
		<input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" class="form-control" placeholder="auto-generated if blank">
		@error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-12">
		<label class="form-label">Description</label>
		<textarea name="description" rows="4" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
		@error('description')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-6">
		<label class="form-label">Status</label>
		<select name="status" class="form-select" required>
			<option value="active" {{ old('status', $category->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
			<option value="inactive" {{ old('status', $category->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
		</select>
		@error('status')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
</div>



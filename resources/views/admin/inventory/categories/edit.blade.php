@extends('admin.layout.app')

@section('title', 'Edit Inventory Category')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Edit Category
            </h1>
            <p class="text-muted mb-0">Update category information</p>
        </div>
        <div>
            <a href="{{ route('admin.inventory.categories.show', $category) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
            <a href="{{ route('admin.inventory.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.inventory.categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Category Color <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="color" class="form-control @error('color') is-invalid @enderror" 
                                               id="color" name="color" value="{{ old('color', $category->color) }}" required>
                                        <input type="text" class="form-control" id="color-text" 
                                               value="{{ old('color', $category->color) }}" readonly>
                                    </div>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Icon Class</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $category->icon) }}" 
                                           placeholder="e.g., fas fa-book">
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Font Awesome icon class</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Category
                                </label>
                            </div>
                            <small class="form-text text-muted">Inactive categories won't appear in item creation</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.inventory.categories.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Preview</h5>
                </div>
                <div class="card-body">
                    <div class="category-preview">
                        <div class="d-flex align-items-center mb-3">
                            <div class="category-icon me-3" id="preview-icon">
                                <i class="{{ $category->icon ?? 'fas fa-tag' }}"></i>
                            </div>
                            <div>
                                <h6 class="mb-1" id="preview-name">{{ $category->name }}</h6>
                                <span class="badge badge-{{ $category->is_active ? 'success' : 'secondary' }}" id="preview-status">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <p class="text-muted small mb-0" id="preview-description">
                            {{ $category->description ?: 'Category description will appear here...' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-primary mb-1">{{ $category->items_count ?? 0 }}</h4>
                                <small class="text-muted">Total Items</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-success mb-1">{{ $category->created_at->format('M Y') }}</h4>
                                <small class="text-muted">Created</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Icon Examples</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-4 text-center">
                            <div class="icon-example" data-icon="fas fa-book">
                                <i class="fas fa-book"></i>
                                <small>Book</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="icon-example" data-icon="fas fa-laptop">
                                <i class="fas fa-laptop"></i>
                                <small>Laptop</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="icon-example" data-icon="fas fa-chair">
                                <i class="fas fa-chair"></i>
                                <small>Chair</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="icon-example" data-icon="fas fa-tools">
                                <i class="fas fa-tools"></i>
                                <small>Tools</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="icon-example" data-icon="fas fa-palette">
                                <i class="fas fa-palette"></i>
                                <small>Art</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="icon-example" data-icon="fas fa-dumbbell">
                                <i class="fas fa-dumbbell"></i>
                                <small>Sports</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.category-preview {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 1rem;
    background: #f8f9fc;
}

.category-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.icon-example {
    padding: 0.5rem;
    border: 1px solid #e3e6f0;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.icon-example:hover {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.badge-success {
    background-color: #28a745;
    color: white;
    font-size: 0.75rem;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
    font-size: 0.75rem;
}

.stat-item {
    padding: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color-text');
    const iconInput = document.getElementById('icon');
    const isActiveCheckbox = document.getElementById('is_active');
    
    const previewName = document.getElementById('preview-name');
    const previewDescription = document.getElementById('preview-description');
    const previewIcon = document.getElementById('preview-icon');
    const previewStatus = document.getElementById('preview-status');

    // Update preview
    function updatePreview() {
        previewName.textContent = nameInput.value || 'Category Name';
        previewDescription.textContent = descriptionInput.value || 'Category description will appear here...';
        previewIcon.style.backgroundColor = colorInput.value + '20';
        previewIcon.style.color = colorInput.value;
        previewIcon.innerHTML = `<i class="${iconInput.value || 'fas fa-tag'}"></i>`;
        
        if (isActiveCheckbox.checked) {
            previewStatus.textContent = 'Active';
            previewStatus.className = 'badge badge-success';
        } else {
            previewStatus.textContent = 'Inactive';
            previewStatus.className = 'badge badge-secondary';
        }
    }

    // Event listeners
    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    colorInput.addEventListener('input', function() {
        colorTextInput.value = this.value;
        updatePreview();
    });
    iconInput.addEventListener('input', updatePreview);
    isActiveCheckbox.addEventListener('change', updatePreview);

    // Icon examples click
    document.querySelectorAll('.icon-example').forEach(example => {
        example.addEventListener('click', function() {
            const icon = this.dataset.icon;
            iconInput.value = icon;
            updatePreview();
        });
    });

    // Initial preview
    updatePreview();
});
</script>
@endsection

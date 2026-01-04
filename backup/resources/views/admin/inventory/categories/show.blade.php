@extends('admin.layout.app')

@section('title', 'Category Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tag me-2"></i>{{ $category->name }}
            </h1>
            <p class="text-muted mb-0">Category details and items</p>
        </div>
        <div>
            <a href="{{ route('admin.inventory.categories.edit', $category) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Category
            </a>
            <a href="{{ route('admin.inventory.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Category Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="category-icon me-3" style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                            <i class="{{ $category->icon ?? 'fas fa-tag' }}"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $category->name }}</h4>
                            <span class="badge badge-{{ $category->is_active ? 'success' : 'secondary' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($category->description)
                    <p class="text-muted">{{ $category->description }}</p>
                    @endif
                    
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-primary mb-1">{{ $category->items_count ?? 0 }}</h4>
                                <small class="text-muted">Total Items</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-info mb-1">{{ $category->sort_order }}</h4>
                                <small class="text-muted">Sort Order</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.inventory.categories.edit', $category) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Category
                        </a>
                        
                        <form method="POST" action="{{ route('admin.inventory.categories.toggle-status', $category) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-{{ $category->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} me-2"></i>
                                {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.inventory.categories.destroy', $category) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Items in Category -->
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Items in this Category</h5>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Item
                    </a>
                </div>
                <div class="card-body">
                    @if($category->items && $category->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>SKU</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="item-icon me-2" style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                                    <i class="{{ $category->icon ?? 'fas fa-box' }}"></i>
                                                </div>
                                                {{ $item->name }}
                                            </div>
                                        </td>
                                        <td><code>{{ $item->sku ?? 'N/A' }}</code></td>
                                        <td>
                                            <span class="badge badge-{{ $item->quantity > 10 ? 'success' : ($item->quantity > 0 ? 'warning' : 'danger') }}">
                                                {{ $item->quantity ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }}">
                                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Items Found</h5>
                            <p class="text-muted">This category doesn't have any items yet.</p>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Item
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Category Timeline -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Category Created</h6>
                                <p class="text-muted mb-1">Category was created successfully</p>
                                <small class="text-muted">{{ $category->created_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        
                        @if($category->updated_at != $category->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Category Updated</h6>
                                <p class="text-muted mb-1">Category information was modified</p>
                                <small class="text-muted">{{ $category->updated_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.category-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.item-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.stat-item {
    padding: 0.5rem;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-content {
    background: #f8f9fc;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
}
</style>
@endsection

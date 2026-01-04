@if($category && is_object($category))
<div class="btn-group" role="group">
    <a href="{{ route('admin.accommodation.categories.show', $category) }}" 
       class="btn btn-sm btn-outline-info" title="View Details">
        <i class="bx bx-show"></i>
    </a>
    <a href="{{ route('admin.accommodation.categories.edit', $category) }}" 
       class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bx bx-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-outline-{{ $category->status === 'active' ? 'warning' : 'success' }}" 
            onclick="toggleStatus({{ $category->id }})" title="{{ $category->status === 'active' ? 'Deactivate' : 'Activate' }}">
        <i class="bx bx-toggle-{{ $category->status === 'active' ? 'right' : 'left' }}"></i>
    </button>
    <form method="POST" action="{{ route('admin.accommodation.categories.duplicate', $category) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-info" title="Duplicate" 
                onclick="return confirm('Are you sure you want to duplicate this category?')">
            <i class="bx bx-copy"></i>
        </button>
    </form>
    <button type="button" class="btn btn-sm btn-outline-danger" 
            onclick="deleteCategory({{ $category->id }})" title="Delete">
        <i class="bx bx-trash"></i>
    </button>
</div>
@else
<div class="text-muted">No actions available</div>
@endif

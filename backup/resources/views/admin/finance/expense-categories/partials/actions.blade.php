<a href="{{ route('admin.finance.expense-categories.show', $r->id) }}" class="btn btn-sm " title="View Details">
    <i class="bx bx-show"></i>
</a>
<a href="{{ route('admin.finance.expense-categories.edit', $r->id) }}" class="btn btn-sm " title="Edit Category">
    <i class="bx bxs-edit"></i>
</a>
<form method="POST" action="{{ route('admin.finance.expense-categories.destroy', $r->id) }}" 
      onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');" 
      class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm " title="Delete Category">
        <i class="bx bx-trash"></i>
    </button>
</form> 
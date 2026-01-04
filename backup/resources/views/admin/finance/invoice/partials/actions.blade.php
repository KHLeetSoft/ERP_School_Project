<div class="btn-group" role="group">
    <a href="{{ route('admin.finance.invoice.show', $r->id) }}" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>
    <a href="{{ route('admin.finance.invoice.edit', $r->id) }}" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>
    <form method="POST" action="{{ route('admin.finance.invoice.destroy', $r->id) }}" onsubmit="return confirm('Delete this invoice?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm" title="Delete"><i class="bx bx-trash"></i></button>
    </form>
</div>



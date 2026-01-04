<div class="btn-group" role="group">
    <a href="{{ route('admin.finance.student-payments.show', $r->id) }}" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>
    <a href="{{ route('admin.finance.student-payments.edit', $r->id) }}" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>
    <form method="POST" action="{{ route('admin.finance.student-payments.destroy', $r->id) }}" onsubmit="return confirm('Delete this payment?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm" title="Delete"><i class="bx bx-trash"></i></button>
    </form>
</div>



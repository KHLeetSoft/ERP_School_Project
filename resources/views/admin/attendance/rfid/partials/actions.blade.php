<div class="btn-group" role="group">
    <a href="{{ route('admin.attendance.rfid.edit', $r->id) }}" class="btn btn-sm " title="Edit"><i class="bx bxs-edit"></i></a>
    <form method="POST" action="{{ route('admin.attendance.rfid.destroy', $r->id) }}" onsubmit="return confirm('Delete this record?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm" title="Delete"><i class="bx bx-trash"></i></button>
    </form>
</div>



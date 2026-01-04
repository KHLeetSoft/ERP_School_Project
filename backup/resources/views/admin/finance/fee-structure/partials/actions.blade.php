<div class="btn-group">
    <a href="{{ route('admin.finance.fee-structure.show', $r) }}" class="btn btn-sm btn-light" title="View Details">
        <i class="bx bx-show"></i>
    </a>
    <a href="{{ route('admin.finance.fee-structure.edit', $r) }}" class="btn btn-sm btn-primary" title="Edit">
        <i class="bx bx-edit"></i>
    </a>
    <button type="button" class="btn btn-sm {{ $r->is_active ? 'btn-warning' : 'btn-success' }}" 
            onclick="toggleStatus({{ $r->id }})" title="{{ $r->is_active ? 'Deactivate' : 'Activate' }}">
        <i class="bx {{ $r->is_active ? 'bx-pause' : 'bx-play' }}"></i>
    </button>
    <form method="POST" action="{{ route('admin.finance.fee-structure.destroy', $r) }}" 
          onsubmit="return confirm('Are you sure you want to delete this fee structure?')" 
          style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
            <i class="bx bx-trash"></i>
        </button>
    </form>
</div>

<script>
function toggleStatus(id) {
    if (confirm('Are you sure you want to change the status?')) {
        fetch(`/admin/finance/fee-structure/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the DataTable
                $('#feeStructureTable').DataTable().ajax.reload();
                // Show success message
                alert(data.message);
            } else {
                alert('Error updating status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status');
        });
    }
}
</script>

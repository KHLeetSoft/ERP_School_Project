<div class="btn-group" role="group">
    <a href="{{ route('admin.hr.staff.show', $row->id) }}" class="btn btn-sm btn-outline-info" title="View">
        <i class="bx bx-show"></i>
    </a>
    <a href="{{ route('admin.hr.staff.edit', $row->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bx bx-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-outline-{{ $row->status === 'active' ? 'warning' : 'success' }} toggle-status" 
            data-url="{{ route('admin.hr.staff.toggle-status', $row->id) }}" 
            title="{{ $row->status === 'active' ? 'Deactivate' : 'Activate' }}">
        <i class="bx bx-{{ $row->status === 'active' ? 'pause' : 'play' }}"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-danger delete-staff" 
            data-url="{{ route('admin.hr.staff.destroy', $row->id) }}" title="Delete">
        <i class="bx bx-trash"></i>
    </button>
</div>
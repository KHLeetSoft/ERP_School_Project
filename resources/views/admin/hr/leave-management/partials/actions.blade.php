<div class="btn-group" role="group">
    <a href="{{ route('admin.hr.leave-management.show', $row->id) }}" class="btn btn-sm btn-outline-info" title="View">
        <i class="bx bx-show"></i>
    </a>
    
    @if($row->status === 'pending')
        <a href="{{ route('admin.hr.leave-management.edit', $row->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
            <i class="bx bx-edit"></i>
        </a>
    @endif
    
    @if($row->status === 'pending')
        <button type="button" class="btn btn-sm btn-outline-success change-status"
                data-url="{{ route('admin.hr.leave-management.toggle-status', $row->id) }}"
                data-status="{{ $row->status }}"
                title="Approve">
            <i class="bx bx-check"></i>
        </button>
        
        <button type="button" class="btn btn-sm btn-outline-danger change-status"
                data-url="{{ route('admin.hr.leave-management.toggle-status', $row->id) }}"
                data-status="{{ $row->status }}"
                title="Reject">
            <i class="bx bx-x"></i>
        </button>
    @endif
    
    @if(in_array($row->status, ['pending', 'approved']))
        <button type="button" class="btn btn-sm btn-outline-warning change-status"
                data-url="{{ route('admin.hr.leave-management.toggle-status', $row->id) }}"
                data-status="{{ $row->status }}"
                title="Cancel">
            <i class="bx bx-stop-circle"></i>
        </button>
    @endif
    
    @if($row->status === 'pending')
        <button type="button" class="btn btn-sm btn-outline-danger delete-leave"
                data-url="{{ route('admin.hr.leave-management.destroy', $row->id) }}" 
                title="Delete">
            <i class="bx bx-trash"></i>
        </button>
    @endif
</div>

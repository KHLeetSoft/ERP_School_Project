<div class="btn-group" role="group">
    <a href="{{ route('admin.hr.payroll.show', $row->id) }}" class="btn btn-sm btn-outline-info" title="View">
        <i class="bx bx-show"></i>
    </a>
    <a href="{{ route('admin.hr.payroll.edit', $row->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bx bx-edit"></i>
    </a>
    
    @if($row->status !== 'paid')
        <button type="button" class="btn btn-sm btn-outline-warning toggle-status"
                data-url="{{ route('admin.hr.payroll.toggle-status', $row->id) }}"
                data-status="{{ $row->status }}"
                title="Change Status">
            <i class="bx bx-refresh"></i>
        </button>
    @endif
    
    @if($row->status === 'pending')
        <button type="button" class="btn btn-sm btn-outline-success toggle-status"
                data-url="{{ route('admin.hr.payroll.toggle-status', $row->id) }}"
                data-status="{{ $row->status }}"
                title="Approve">
            <i class="bx bx-check"></i>
        </button>
    @endif
    
    @if($row->status === 'approved')
        <button type="button" class="btn btn-sm btn-outline-success toggle-status"
                data-url="{{ route('admin.hr.payroll.toggle-status', $row->id) }}"
                data-status="{{ $row->status }}"
                title="Mark as Paid">
            <i class="bx bx-dollar"></i>
        </button>
    @endif
    
    @if($row->status !== 'paid')
        <button type="button" class="btn btn-sm btn-outline-danger delete-payroll"
                data-url="{{ route('admin.hr.payroll.destroy', $row->id) }}" 
                title="Delete">
            <i class="bx bx-trash"></i>
        </button>
    @endif
</div>

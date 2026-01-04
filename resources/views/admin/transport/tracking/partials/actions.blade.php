@if($data && is_object($data) && isset($data->id))
    <div class="d-flex gap-1">
        <a href="{{ route('admin.transport.tracking.show', $data->id) }}" 
           class="btn btn-sm btn-outline-primary" 
           title="View Details">
            <i class="fas fa-eye"></i>
        </a>
        
        <a href="{{ route('admin.transport.tracking.edit', $data->id) }}" 
           class="btn btn-sm btn-outline-warning" 
           title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        
        <form method="POST" action="{{ route('admin.transport.tracking.duplicate', $data->id) }}" 
              class="d-inline" 
              onsubmit="return confirm('Are you sure you want to duplicate this tracking record?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-info" title="Duplicate">
                <i class="fas fa-copy"></i>
            </button>
        </form>
        
        <form method="POST" action="{{ route('admin.transport.tracking.destroy', $data->id) }}" 
              class="d-inline" 
              onsubmit="return confirm('Are you sure you want to delete this tracking record?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
@else
    <span class="text-muted">N/A</span>
@endif

@if($data && is_object($data) && isset($data->driver))
    <div class="d-flex align-items-center">
        <div class="avatar avatar-sm bg-success text-white rounded-circle me-2">
            <i class="fas fa-user"></i>
        </div>
        <div>
            <div class="fw-bold">{{ $data->driver->name ?? 'N/A' }}</div>
            @if(isset($data->driver->phone) && $data->driver->phone)
                <small class="text-muted">{{ $data->driver->phone }}</small>
            @endif
        </div>
    </div>
@else
    <span class="text-muted">N/A</span>
@endif

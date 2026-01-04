@if($data && is_object($data) && isset($data->route))
    <div class="d-flex align-items-center">
        <div class="avatar avatar-sm bg-info text-white rounded-circle me-2">
            <i class="fas fa-route"></i>
        </div>
        <div>
            <div class="fw-bold">{{ $data->route->name ?? 'N/A' }}</div>
            @if(isset($data->route->start_point) && $data->route->start_point)
                <small class="text-muted">{{ $data->route->start_point }} → {{ $data->route->end_point ?? 'End' }}</small>
            @endif
        </div>
    </div>
@else
    <span class="text-muted">N/A</span>
@endif

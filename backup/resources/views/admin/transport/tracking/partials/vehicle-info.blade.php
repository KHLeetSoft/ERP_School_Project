@if($data && is_object($data) && isset($data->vehicle))
    <div class="d-flex align-items-center">
        <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2">
            <i class="fas fa-bus"></i>
        </div>
        <div>
            <div class="fw-bold">{{ $data->vehicle->vehicle_number ?? 'N/A' }}</div>
            @if(isset($data->vehicle->model) && $data->vehicle->model)
                <small class="text-muted">{{ $data->vehicle->model }}</small>
            @endif
        </div>
    </div>
@else
    <span class="text-muted">N/A</span>
@endif

@if($category && is_object($category))
<div>
    <div class="mb-1">
        <span class="badge bg-info">{{ $category->capacity }}</span>
        <small class="text-muted d-block">Total Capacity</small>
    </div>
    <div class="mb-1">
        <span class="badge bg-{{ $category->available_rooms > 0 ? 'success' : 'danger' }}">
            {{ $category->available_rooms }}
        </span>
        <small class="text-muted d-block">Available Rooms</small>
    </div>
    <div class="mt-2">
        <div class="progress" style="height: 6px;">
            <div class="progress-bar bg-{{ $category->occupancy_rate > 80 ? 'success' : ($category->occupancy_rate > 50 ? 'warning' : 'danger') }}" 
                 style="width: {{ $category->occupancy_rate }}%"></div>
        </div>
        <small class="text-muted">{{ $category->occupancy_rate }}% occupied</small>
    </div>
</div>
@else
<div class="text-muted">No capacity information</div>
@endif
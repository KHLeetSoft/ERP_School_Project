@if($category && is_object($category))
<div class="d-flex align-items-center">
    <img src="{{ $category->main_image }}" alt="{{ $category->name }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
    <div>
        <div class="fw-bold text-primary">{{ $category->name }}</div>
        <small class="text-muted">{{ Str::limit($category->description ?? 'No description', 40) }}</small>
        <div class="mt-1">
            <small class="text-muted">
                <i class="bx bx-calendar"></i> {{ $category->created_at->format('M d, Y') }}
            </small>
        </div>
    </div>
</div>
@else
<div class="text-muted">No category information</div>
@endif

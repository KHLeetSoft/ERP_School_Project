@if($category && is_object($category))
<span class="badge {{ $category->status_badge_class }} fs-6">
    {{ $category->status_text }}
</span>
@if($category->is_available)
<div class="mt-1">
    <small class="text-success">
        <i class="bx bx-check-circle"></i> Available
    </small>
</div>
@else
<div class="mt-1">
    <small class="text-muted">
        <i class="bx bx-x-circle"></i> Not Available
    </small>
</div>
@endif
@else
<span class="badge bg-secondary">Unknown</span>
@endif

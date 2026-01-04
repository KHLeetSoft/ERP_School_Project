@if($category && is_object($category))
<div>
    <div class="mb-1">
        <strong class="text-success">{{ $category->formatted_monthly_fee }}</strong>
        <small class="text-muted d-block">Monthly Fee</small>
    </div>
    <div class="mb-1">
        <strong class="text-info">{{ $category->formatted_security_deposit }}</strong>
        <small class="text-muted d-block">Security Deposit</small>
    </div>
    @if($category->facilities && count($category->facilities) > 0)
    <div class="mt-2">
        <small class="text-muted">
            <i class="bx bx-check-circle text-success"></i> {{ count($category->facilities) }} facilities
        </small>
    </div>
    @endif
</div>
@else
<div class="text-muted">No pricing information</div>
@endif

{{-- Message Status Partial --}}
<div class="d-flex align-items-center gap-1">
    {{-- Priority Badge --}}
    <span class="badge bg-{{ $message->priority == 'urgent' ? 'danger' : ($message->priority == 'high' ? 'warning' : ($message->priority == 'normal' ? 'primary' : 'success')) }}">
        {{ ucfirst($message->priority) }}
    </span>
    
    {{-- Type Badge --}}
    <span class="badge bg-info">{{ ucfirst($message->type) }}</span>
    
    {{-- Status Indicators --}}
    @if($message->is_starred)
        <i class="fas fa-star text-warning" title="Starred"></i>
    @endif
    
    @if($message->is_important)
        <i class="fas fa-exclamation-triangle text-danger" title="Important"></i>
    @endif
    
    @if($message->is_encrypted)
        <i class="fas fa-lock text-success" title="Encrypted"></i>
    @endif
    
    @if($message->requires_acknowledgment)
        <i class="fas fa-check-double text-info" title="Requires Acknowledgment"></i>
    @endif
    
    {{-- Read/Unread Status --}}
    @if($message->read_at)
        <span class="badge bg-success">Read</span>
    @else
        <span class="badge bg-warning">Unread</span>
    @endif
    
    {{-- Draft Status --}}
    @if($message->status === 'draft')
        <span class="badge bg-secondary">Draft</span>
    @endif
    
    {{-- Expired Status --}}
    @if($message->expires_at && $message->expires_at->isPast())
        <span class="badge bg-danger">Expired</span>
    @endif
</div>




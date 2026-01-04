{{-- Message Actions Partial --}}
<div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ route('admin.messages.show', $message->id) }}">
                <i class="fas fa-eye me-2"></i> View
            </a>
        </li>
        
        @if($message->sender_id == auth()->id())
            {{-- Actions for sent messages --}}
            <li>
                <a class="dropdown-item" href="{{ route('admin.messages.create', ['reply_to' => $message->id]) }}">
                    <i class="fas fa-reply me-2"></i> Reply
                </a>
            </li>
            @if($message->status === 'draft')
                <li>
                    <a class="dropdown-item" href="{{ route('admin.messages.edit', $message->id) }}">
                        <i class="fas fa-edit me-2"></i> Edit Draft
                    </a>
                </li>
            @endif
        @else
            {{-- Actions for received messages --}}
            <li>
                <a class="dropdown-item" href="{{ route('admin.messages.create', ['reply_to' => $message->id]) }}">
                    <i class="fas fa-reply me-2"></i> Reply
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('admin.messages.create', ['forward_from' => $message->id]) }}">
                    <i class="fas fa-share me-2"></i> Forward
                </a>
            </li>
        @endif
        
        <li><hr class="dropdown-divider"></li>
        
        {{-- Message flags --}}
        <li>
            <form action="{{ route('admin.messages.toggle-star', $message->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item">
                    @if($message->is_starred)
                        <i class="fas fa-star text-warning me-2"></i> Remove Star
                    @else
                        <i class="far fa-star me-2"></i> Star Message
                    @endif
                </button>
            </form>
        </li>
        
        <li>
            <form action="{{ route('admin.messages.toggle-important', $message->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item">
                    @if($message->is_important)
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i> Remove Important
                    @else
                        <i class="far fa-exclamation-triangle me-2"></i> Mark Important
                    @endif
                </button>
            </form>
        </li>
        
        @if($message->requires_acknowledgment && !$message->acknowledged_at)
            <li>
                <form action="{{ route('admin.messages.acknowledge', $message->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-check me-2"></i> Acknowledge
                    </button>
                </form>
            </li>
        @endif
        
        <li><hr class="dropdown-divider"></li>
        
        {{-- Delete action --}}
        <li>
            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                    <i class="fas fa-trash me-2"></i> Delete
                </button>
            </form>
        </li>
    </ul>
</div>




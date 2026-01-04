{{-- Message Recipients Partial --}}
<div class="message-recipients">
    {{-- To Recipients --}}
    @if($message->recipients->where('type', 'to')->count() > 0)
        <div class="mb-2">
            <strong>To:</strong>
            @foreach($message->recipients->where('type', 'to') as $recipient)
                <span class="badge bg-primary me-1">
                    @if($recipient->user)
                        {{ $recipient->user->name }}
                        <small class="text-white-50">({{ $recipient->user->email }})</small>
                    @else
                        {{ $recipient->email ?? 'Unknown' }}
                    @endif
                </span>
            @endforeach
        </div>
    @endif
    
    {{-- CC Recipients --}}
    @if($message->recipients->where('type', 'cc')->count() > 0)
        <div class="mb-2">
            <strong>CC:</strong>
            @foreach($message->recipients->where('type', 'cc') as $recipient)
                <span class="badge bg-secondary me-1">
                    @if($recipient->user)
                        {{ $recipient->user->name }}
                        <small class="text-white-50">({{ $recipient->user->email }})</small>
                    @else
                        {{ $recipient->email ?? 'Unknown' }}
                    @endif
                </span>
            @endforeach
        </div>
    @endif
    
    {{-- BCC Recipients --}}
    @if($message->recipients->where('type', 'bcc')->count() > 0)
        <div class="mb-2">
            <strong>BCC:</strong>
            @foreach($message->recipients->where('type', 'bcc') as $recipient)
                <span class="badge bg-dark me-1">
                    @if($recipient->user)
                        {{ $recipient->user->name }}
                        <small class="text-white-50">({{ $recipient->user->email }})</small>
                    @else
                        {{ $recipient->email ?? 'Unknown' }}
                    @endif
                </span>
            @endforeach
        </div>
    @endif
    
    {{-- Department --}}
    @if($message->department)
        <div class="mb-2">
            <strong>Department:</strong>
            <span class="badge bg-info">{{ $message->department->name }}</span>
        </div>
    @endif
    
    {{-- Labels --}}
    @if($message->labels->count() > 0)
        <div class="mb-2">
            <strong>Labels:</strong>
            @foreach($message->labels as $label)
                <span class="badge" style="background-color: {{ $label->color }}; color: white;">
                    {{ $label->name }}
                </span>
            @endforeach
        </div>
    @endif
</div>




@extends('librarian.layout.app')

@section('title', 'Messages / Chat')
@section('page-title', 'Messages / Chat')

@section('content')
<div class="container-fluid">
    <div class="modern-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Conversations</h5>
            <div class="w-25">
                <input type="text" class="form-control" placeholder="Search conversations...">
            </div>
        </div>

        <div class="list-group list-group-flush">
            @forelse($threads as $thread)
                <a href="{{ route('librarian.messages.show', $thread['id']) }}" class="list-group-item list-group-item-action border-0 border-bottom py-3 d-flex align-items-center">
                    <div class="me-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background: rgba(99, 102, 241, 0.1)">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $thread['name'] }}</div>
                        <div class="text-muted small">Last message preview...</div>
                    </div>
                    <div class="text-nowrap small text-muted">2h ago</div>
                </a>
            @empty
                <div class="p-4 text-center text-muted">No conversations yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection



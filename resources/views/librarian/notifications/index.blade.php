@extends('librarian.layout.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h5 class="mb-2 mb-md-0">
            <i class="fas fa-bell me-2"></i>Notifications
        </h5>
        <div class="d-flex gap-2">
            <a href="{{ route('librarian.notifications.overdue-books') }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-exclamation-triangle me-1"></i> Overdue
            </a>
            <a href="{{ route('librarian.notifications.due-today') }}" class="btn btn-outline-warning btn-sm">
                <i class="fas fa-clock me-1"></i> Due Today
            </a>
            <form method="POST" action="{{ route('librarian.notifications.mark-read') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-check me-1"></i> Mark all as read
                </button>
            </form>
        </div>
    </div>

    <div class="modern-card p-3">
        <div class="row g-3 align-items-end mb-3">
            <form method="GET" action="{{ route('librarian.notifications.index') }}" class="row g-3 align-items-end mb-0">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Search</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search notifications...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Type</label>
                    <select class="form-select" name="type" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="danger" {{ request('type')=='danger' ? 'selected' : '' }}>Overdue</option>
                        <option value="warning" {{ request('type')=='warning' ? 'selected' : '' }}>Due Today</option>
                        <option value="info" {{ request('type')=='info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ request('type')=='success' ? 'selected' : '' }}>Success</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Status</label>
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="unread" {{ request('status')=='unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status')=='read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search me-1"></i> Filter</button>
                </div>
            </form>
        </div>

        <div class="list-group list-group-flush">
            @forelse($notifications as $note)
                <div class="list-group-item border-0 border-bottom d-flex align-items-center py-3 {{ $note['is_read'] ? '' : 'bg-light' }}">
                    <div class="me-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background: rgba(0,0,0,0.05)">
                            @if($note->type === 'danger')
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                            @elseif($note->type === 'warning')
                                <i class="fas fa-clock text-warning"></i>
                            @elseif($note->type === 'success')
                                <i class="fas fa-check-circle text-success"></i>
                            @else
                                <i class="fas fa-info-circle text-info"></i>
                            @endif
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold">{{ $note->title }}</span>
                            @unless($note->is_read)
                                <span class="badge rounded-pill bg-primary">New</span>
                            @endunless
                        </div>
                        <div class="text-muted small">{{ $note->message }}</div>
                    </div>
                    <div class="text-nowrap text-muted small ms-3">{{ optional($note->sent_at ?? $note->created_at)->diffForHumans() }}</div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">No notifications</div>
            @endforelse
        </div>

        @if($notifications instanceof \Illuminate\Contracts\Pagination\Paginator || $notifications instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
            <div class="mt-3">
                {{ $notifications->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection



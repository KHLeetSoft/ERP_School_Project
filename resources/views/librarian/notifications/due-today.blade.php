@extends('librarian.layout.app')

@section('title', 'Due Today')
@section('page-title', 'Due Today')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-clock me-2 text-warning"></i>Due Today</h5>
        <a href="{{ route('librarian.notifications.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> All Notifications
        </a>
    </div>

    <div class="modern-card p-3">
        <div class="list-group list-group-flush">
            @forelse($notifications as $note)
                <div class="list-group-item border-0 border-bottom d-flex align-items-center py-3">
                    <div class="me-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background: rgba(245, 158, 11, 0.1)">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $note['title'] }}</div>
                        <div class="text-muted small">{{ $note['message'] }}</div>
                    </div>
                    <div class="text-nowrap text-muted small ms-3">{{ $note['time']->diffForHumans() }}</div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">No books due today</div>
            @endforelse
        </div>
    </div>
@endsection



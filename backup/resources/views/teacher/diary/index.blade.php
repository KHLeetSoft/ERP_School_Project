@extends('teacher.layout.app')

@section('title', 'Diary')
@section('page-title', 'My Diary')
@section('page-description', 'Write and manage your thoughts and notes')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control" placeholder="Search entries..." value="{{ request('q') }}">
            <select name="mood" class="form-select">
                <option value="">All moods</option>
                @foreach(['happy','neutral','sad','excited','tired','stressed','grateful'] as $mood)
                    <option value="{{ $mood }}" @selected(request('mood')===$mood)>{{ ucfirst($mood) }}</option>
                @endforeach
            </select>
            <a href="{{ route('teacher.diary.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Entry
            </a>
        </form>
    </div>
</div>

<div class="row">
    @forelse($entries as $entry)
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">{{ $entry->title ?? 'Untitled' }}</h5>
                        <form method="POST" action="{{ route('teacher.diary.toggle-pin', $entry) }}">
                            @csrf
                            <button class="btn btn-sm {{ $entry->is_pinned ? 'btn-warning' : 'btn-outline-secondary' }}" title="Pin/Unpin">
                                <i class="fas fa-thumbtack"></i>
                            </button>
                        </form>
                    </div>
                    <div class="text-muted small mb-2">
                        <i class="far fa-calendar-alt me-1"></i>{{ $entry->entry_date->format('d M Y') }}
                        @if($entry->mood)
                            <span class="ms-2"><i class="far fa-smile me-1"></i>{{ ucfirst($entry->mood) }}</span>
                        @endif
                    </div>
                    <p class="card-text flex-grow-1">{{ Str::limit(strip_tags($entry->content), 160) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            @if(is_array($entry->tags))
                                @foreach($entry->tags as $tag)
                                    <span class="badge bg-light text-dark border">#{{ $tag }}</span>
                                @endforeach
                            @endif
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('teacher.diary.edit', $entry) }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('teacher.diary.destroy', $entry) }}" onsubmit="return confirm('Delete this entry?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                No diary entries yet. Create your first one!
            </div>
        </div>
    @endforelse
</div>

<div class="mt-3">
    {{ $entries->links() }}
    </div>
@endsection



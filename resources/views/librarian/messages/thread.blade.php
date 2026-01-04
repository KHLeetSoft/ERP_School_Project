@extends('librarian.layout.app')

@section('title', $participant)
@section('page-title', $participant)

@section('content')
<div class="container-fluid">
    <div class="modern-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('librarian.messages.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="border rounded p-3 mb-3" style="height: 50vh; overflow-y: auto; background: #f8fafc;">
            @foreach($messages as $msg)
                <div class="d-flex mb-2 {{ $msg['from'] === 'you' ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="px-3 py-2 rounded {{ $msg['from'] === 'you' ? 'bg-primary text-white' : 'bg-white border' }}" style="max-width: 70%">
                        <div>{{ $msg['text'] }}</div>
                        <div class="small text-muted mt-1" style="opacity: .8">{{ $msg['time']->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('librarian.messages.send', $thread) }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-1"></i> Send
            </button>
        </form>
    </div>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Event #{{ $calendar->id }}</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.academic.calendar.edit', $calendar) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('admin.academic.calendar.destroy', $calendar) }}" method="POST" onsubmit="return confirm('Delete this event?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6"><strong>Title:</strong><div>{{ $calendar->title }}</div></div>
                <div class="col-md-3"><strong>Date:</strong><div>{{ optional($calendar->date)->format('Y-m-d') }}</div></div>
                <div class="col-md-3"><strong>Status:</strong><div>{{ ucfirst($calendar->status) }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Start Time:</strong><div>{{ optional($calendar->start_time)->format('Y-m-d H:i') }}</div></div>
                <div class="col-md-6"><strong>End Time:</strong><div>{{ optional($calendar->end_time)->format('Y-m-d H:i') }}</div></div>
            </div>
            <div class="mb-3"><strong>Description:</strong><div>{{ $calendar->description ?? '-' }}</div></div>
            <a href="{{ route('admin.academic.calendar.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
    </div>
@endsection



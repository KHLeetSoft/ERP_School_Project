@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Edit Calendar Event</h3>
    <form method="POST" action="{{ route('admin.academic.calendar.update', $calendar) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ $calendar->title }}" required></div>
            <div class="col-md-6"><label class="form-label">Date</label><input type="date" class="form-control" name="date" value="{{ optional($calendar->date)->format('Y-m-d') }}" required></div>
            <div class="col-md-6"><label class="form-label">Start Time</label><input type="datetime-local" class="form-control" name="start_time" value="{{ optional($calendar->start_time)->format('Y-m-d\TH:i') }}"></div>
            <div class="col-md-6"><label class="form-label">End Time</label><input type="datetime-local" class="form-control" name="end_time" value="{{ optional($calendar->end_time)->format('Y-m-d\TH:i') }}"></div>
            <div class="col-md-6"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="scheduled" {{ $calendar->status==='scheduled'?'selected':'' }}>Scheduled</option>
                    <option value="completed" {{ $calendar->status==='completed'?'selected':'' }}>Completed</option>
                    <option value="cancelled" {{ $calendar->status==='cancelled'?'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" rows="4" name="description">{{ $calendar->description }}</textarea></div>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.academic.calendar.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
    </div>
@endsection



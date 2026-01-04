@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Edit PTM</h3>
    <form method="POST" action="{{ route('admin.academic.ptm.update', $ptm) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ $ptm->title }}" required></div>
            <div class="col-md-6"><label class="form-label">Date</label><input type="date" class="form-control" name="date" value="{{ optional($ptm->date)->format('Y-m-d') }}" required></div>
            <div class="col-md-6"><label class="form-label">Start Time</label><input type="datetime-local" class="form-control" name="start_time" value="{{ optional($ptm->start_time)->format('Y-m-d\TH:i') }}" required></div>
            <div class="col-md-6"><label class="form-label">End Time</label><input type="datetime-local" class="form-control" name="end_time" value="{{ optional($ptm->end_time)->format('Y-m-d\TH:i') }}" required></div>
            <div class="col-md-6"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="scheduled" {{ $ptm->status==='scheduled'?'selected':'' }}>Scheduled</option>
                    <option value="completed" {{ $ptm->status==='completed'?'selected':'' }}>Completed</option>
                    <option value="cancelled" {{ $ptm->status==='cancelled'?'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" rows="4" name="description">{{ $ptm->description }}</textarea></div>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.academic.ptm.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
    </div>
@endsection



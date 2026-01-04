@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Create PTM</h3>
    <form method="POST" action="{{ route('admin.academic.ptm.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" required></div>
            <div class="col-md-6"><label class="form-label">Date</label><input type="date" class="form-control" name="date" required></div>
            <div class="col-md-6"><label class="form-label">Start Time</label><input type="datetime-local" class="form-control" name="start_time" required></div>
            <div class="col-md-6"><label class="form-label">End Time</label><input type="datetime-local" class="form-control" name="end_time" required></div>
            <div class="col-md-6"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" rows="4" name="description"></textarea></div>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.academic.ptm.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
    </div>
@endsection



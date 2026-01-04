@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Edit Resource Booking</h3>

    <form method="POST" action="{{ route('admin.racademic.esource-bookings.update', $resourceBooking) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Resource Type</label>
                <select name="resource_type" class="form-select" required>
                    <option value="room" {{ $resourceBooking->resource_type === 'room' ? 'selected' : '' }}>Room</option>
                    <option value="equipment" {{ $resourceBooking->resource_type === 'equipment' ? 'selected' : '' }}>Equipment</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Resource Name</label>
                <input type="text" name="resource_name" class="form-control" value="{{ $resourceBooking->resource_name }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Resource ID (optional)</label>
                <input type="number" name="resource_id" class="form-control" value="{{ $resourceBooking->resource_id }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ $resourceBooking->title }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ $resourceBooking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $resourceBooking->status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $resourceBooking->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ $resourceBooking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Start Time</label>
                <input type="datetime-local" name="start_time" class="form-control" value="{{ $resourceBooking->start_time->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">End Time</label>
                <input type="datetime-local" name="end_time" class="form-control" value="{{ $resourceBooking->end_time->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ $resourceBooking->description }}</textarea>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.academic.resource-bookings.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection



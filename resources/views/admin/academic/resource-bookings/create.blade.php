@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Create Resource Booking</h3>

    <form method="POST" action="{{ route('admin.academic.resource-bookings.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Resource Type</label>
                <select name="resource_type" class="form-select" required>
                    <option value="">Select</option>
                    <option value="room">Room</option>
                    <option value="equipment">Equipment</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Resource Name</label>
                <input type="text" name="resource_name" class="form-control" placeholder="e.g., Conference Room A">
            </div>
            <div class="col-md-4">
                <label class="form-label">Resource ID (optional)</label>
                <input type="number" name="resource_id" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Start Time</label>
                <input type="datetime-local" name="start_time" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">End Time</label>
                <input type="datetime-local" name="end_time" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.academic.resource-bookings.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>
@endsection



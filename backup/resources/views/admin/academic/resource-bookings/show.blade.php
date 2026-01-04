@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Booking #{{ $resourceBooking->id }}</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.academic.resource-bookings.edit', $resourceBooking) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('admin.academic.resource-bookings.destroy', $resourceBooking) }}" method="POST" onsubmit="return confirm('Delete this booking?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Title:</strong>
                    <div>{{ $resourceBooking->title }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <div>{{ ucfirst($resourceBooking->status) }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Resource:</strong>
                    <div>{{ $resourceBooking->resource_type }} {{ $resourceBooking->resource_name ? '(' . $resourceBooking->resource_name . ')' : '' }}</div>
                </div>
                <div class="col-md-3">
                    <strong>Start Time:</strong>
                    <div>{{ optional($resourceBooking->start_time)->format('Y-m-d H:i') }}</div>
                </div>
                <div class="col-md-3">
                    <strong>End Time:</strong>
                    <div>{{ optional($resourceBooking->end_time)->format('Y-m-d H:i') }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Booked By (User ID):</strong>
                    <div>{{ $resourceBooking->booked_by ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>School ID:</strong>
                    <div>{{ $resourceBooking->school_id ?? '-' }}</div>
                </div>
            </div>

            @if($resourceBooking->approved_by || $resourceBooking->approved_at)
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Approved By (User ID):</strong>
                    <div>{{ $resourceBooking->approved_by ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <strong>Approved At:</strong>
                    <div>{{ optional($resourceBooking->approved_at)->format('Y-m-d H:i') }}</div>
                </div>
            </div>
            @endif

            @if($resourceBooking->rejection_reason)
            <div class="mb-3">
                <strong>Rejection Reason:</strong>
                <div>{{ $resourceBooking->rejection_reason }}</div>
            </div>
            @endif

            <div class="mb-3">
                <strong>Description:</strong>
                <div>{{ $resourceBooking->description ?? '-' }}</div>
            </div>

            <a href="{{ route('admin.academic.resource-bookings.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4>Transport Assignment Details</h4>
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><strong>Student:</strong> {{ trim(($record->student->first_name.' '.$record->student->last_name)) ?: ($record->student->user->name ?? '-') }}</div>
                <div class="col-md-4"><strong>Class:</strong> {{ $record->schoolClass->name ?? '-' }}</div>
                <div class="col-md-4"><strong>Route:</strong> {{ $record->route->name ?? '-' }}</div>
                <div class="col-md-4"><strong>Vehicle:</strong> {{ $record->vehicle->vehicle_no ?? '-' }}</div>
                <div class="col-md-4"><strong>Pickup:</strong> {{ $record->pickup_point ?? '-' }}</div>
                <div class="col-md-4"><strong>Drop:</strong> {{ $record->drop_point ?? '-' }}</div>
                <div class="col-md-4"><strong>Start Date:</strong> {{ $record->start_date ?? '-' }}</div>
                <div class="col-md-4"><strong>End Date:</strong> {{ $record->end_date ?? '-' }}</div>
                <div class="col-md-4"><strong>Fare:</strong> {{ number_format($record->fare, 2) }}</div>
                <div class="col-md-4"><strong>Status:</strong> {{ $record->status }}</div>
                <div class="col-md-12"><strong>Remarks:</strong> {{ $record->remarks ?? '-' }}</div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.students.transport.edit', $record->id) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('admin.students.transport.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection



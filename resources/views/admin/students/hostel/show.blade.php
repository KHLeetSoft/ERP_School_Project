@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Hostel Assignment Details</h4>
  <div class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4"><strong>Student:</strong> {{ trim(($record->student->first_name.' '.$record->student->last_name)) ?: ($record->student->user->name ?? '-') }}</div>
        <div class="col-md-4"><strong>Hostel:</strong> {{ $record->hostel->name ?? '-' }}</div>
        <div class="col-md-4"><strong>Room:</strong> {{ $record->room->room_no ?? '-' }}</div>
        <div class="col-md-4"><strong>Bed:</strong> {{ $record->bed_no ?? '-' }}</div>
        <div class="col-md-4"><strong>Status:</strong> {{ ucfirst($record->status) }}</div>
        <div class="col-md-4"><strong>Joined:</strong> {{ $record->join_date ?? '-' }}</div>
        <div class="col-md-4"><strong>Left:</strong> {{ $record->leave_date ?? '-' }}</div>
        <div class="col-md-12"><strong>Remarks:</strong> {{ $record->remarks ?? '-' }}</div>
      </div>
      <div class="mt-3">
        <a href="{{ route('admin.students.hostel.edit', $record->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('admin.students.hostel.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </div>
  </div>
</div>
@endsection



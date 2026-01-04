@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Communication Details</h4>
  <div class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4"><strong>Student:</strong> {{ trim(($record->student->first_name.' '.$record->student->last_name)) ?: ($record->student->user->name ?? '-') }}</div>
        <div class="col-md-4"><strong>Class:</strong> {{ $record->schoolClass->name ?? '-' }}</div>
        <div class="col-md-4"><strong>Channel:</strong> {{ ucfirst($record->channel) }}</div>
        <div class="col-md-12"><strong>Subject:</strong> {{ $record->subject ?? '-' }}</div>
        <div class="col-md-12"><strong>Message:</strong><br/> {!! nl2br(e($record->message)) !!}</div>
        <div class="col-md-4"><strong>Status:</strong> {{ ucfirst($record->status) }}</div>
        <div class="col-md-4"><strong>Sent At:</strong> {{ optional($record->sent_at)->format('Y-m-d H:i') ?? '-' }}</div>
      </div>
      <div class="mt-3">
        <a href="{{ route('admin.students.communication.edit', $record->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('admin.students.communication.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </div>
  </div>
</div>
@endsection



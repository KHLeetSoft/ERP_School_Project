@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Parent Details</h4>
  <div class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6"><strong>Parent:</strong> {{ $record->primary_contact_name ?? ($record->user->name ?? '-') }}</div>
        <div class="col-md-6"><strong>Phone:</strong> {{ $record->phone_primary ?? '-' }}</div>
        <div class="col-md-6"><strong>Email:</strong> {{ $record->email_primary ?? '-' }}</div>
        <div class="col-md-12"><strong>Address:</strong> {{ $record->address ?? '-' }}</div>
        <div class="col-md-12"><strong>Students:</strong> {{ $record->students->map(fn($s) => trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student'))->implode(', ') }}</div>
        <div class="col-md-12"><strong>Notes:</strong> {{ $record->notes ?? '-' }}</div>
      </div>
      <div class="mt-3">
        <a href="{{ route('admin.parents.details.edit', $record->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('admin.parents.details.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </div>
  </div>
</div>
@endsection



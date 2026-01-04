@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Portal Access Details</h4>
  <div class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4"><strong>Student:</strong> {{ trim(($record->student->first_name.' '.$record->student->last_name)) ?: ($record->student->user->name ?? '-') }}</div>
        <div class="col-md-4"><strong>Username:</strong> {{ $record->username }}</div>
        <div class="col-md-4"><strong>Email:</strong> {{ $record->email ?? '-' }}</div>
        <div class="col-md-4"><strong>Enabled:</strong> {{ $record->is_enabled ? 'Yes' : 'No' }}</div>
        <div class="col-md-4"><strong>Force Reset:</strong> {{ $record->force_password_reset ? 'Yes' : 'No' }}</div>
        <div class="col-md-4"><strong>Last Login:</strong> {{ optional($record->last_login_at)->format('Y-m-d H:i') ?? '-' }}</div>
        <div class="col-md-12"><strong>Notes:</strong> {{ $record->notes ?? '-' }}</div>
      </div>
      <div class="mt-3">
        <a href="{{ route('admin.students.portal-access.edit', $record->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('admin.students.portal-access.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </div>
  </div>
</div>
@endsection



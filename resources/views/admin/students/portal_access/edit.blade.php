@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Edit Portal Access</h4>
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.students.portal-access.update', $record->id) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-control" required>
              @foreach($students as $s)
                <option value="{{ $s->id }}" {{ $record->student_id == $s->id ? 'selected' : '' }}>{{ trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student #'.$s->id) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="{{ $record->username }}" required />
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $record->email }}" />
          </div>
          <div class="col-md-4">
            <label class="form-label">New Password (optional)</label>
            <input type="password" name="password" class="form-control" />
          </div>
          <div class="col-md-4 form-check mt-4">
            <input type="checkbox" name="is_enabled" class="form-check-input" id="enabledCheck" {{ $record->is_enabled ? 'checked' : '' }}>
            <label for="enabledCheck" class="form-check-label">Enabled</label>
          </div>
          <div class="col-md-4 form-check mt-4">
            <input type="checkbox" name="force_password_reset" class="form-check-input" id="fprCheck" {{ $record->force_password_reset ? 'checked' : '' }}>
            <label for="fprCheck" class="form-check-label">Force password reset on next login</label>
          </div>
          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ $record->notes }}</textarea>
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Update</button>
          <a href="{{ route('admin.students.portal-access.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection



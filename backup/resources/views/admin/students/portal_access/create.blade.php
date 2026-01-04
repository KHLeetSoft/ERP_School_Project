@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Add Portal Access</h4>
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.students.portal-access.store') }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-control" required>
              <option value="">Select</option>
              @foreach($students as $s)
                <option value="{{ $s->id }}">{{ trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student #'.$s->id) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required />
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required />
          </div>
          <div class="col-md-4 form-check mt-4">
            <input type="checkbox" name="is_enabled" class="form-check-input" id="enabledCheck" checked>
            <label for="enabledCheck" class="form-check-label">Enabled</label>
          </div>
          <div class="col-md-4 form-check mt-4">
            <input type="checkbox" name="force_password_reset" class="form-check-input" id="fprCheck">
            <label for="fprCheck" class="form-check-label">Force password reset on next login</label>
          </div>
          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Save</button>
          <a href="{{ route('admin.students.portal-access.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Add Parent Details</h4>
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.parents.details.store') }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Parent User</label>
            <input type="number" name="user_id" class="form-control" placeholder="User ID" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Primary Contact Name</label>
            <input type="text" name="primary_contact_name" class="form-control" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone_primary" class="form-control" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email_primary" class="form-control" />
          </div>
          <div class="col-12">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" />
          </div>
          <div class="col-12">
            <label class="form-label">Link Students</label>
            <select name="student_ids[]" class="form-control" multiple>
              @foreach($students as $s)
                <option value="{{ $s->id }}">{{ trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student #'.$s->id) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Save</button>
          <a href="{{ route('admin.parents.details.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection



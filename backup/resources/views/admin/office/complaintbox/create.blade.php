@extends('admin.layout.app')

@section('title','Add Complaint')

@section('content')
<div class="card">
  <div class="card-header"><h4 class="mb-0">Add Complaint</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.office.complaintbox.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Complain By</label>
        <div class="col-md-10"><input type="text" name="complain_by" class="form-control" value="{{ old('complain_by') }}" required></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Phone</label>
        <div class="col-md-10"><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Purpose</label>
        <div class="col-md-10">
          <select name="purpose" class="form-select" required>
            <option value="">Select Purpose</option>
            <option value="Facility" {{ old('purpose') == 'Facility' ? 'selected' : '' }}>Facility</option>
            <option value="Staff" {{ old('purpose') == 'Staff' ? 'selected' : '' }}>Staff</option>
            <option value="Student" {{ old('purpose') == 'Student' ? 'selected' : '' }}>Student</option>
            <option value="Other" {{ old('purpose') == 'Other' ? 'selected' : '' }}>Other</option>
          </select>
        </div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Date</label>
        <div class="col-md-10"><input type="date" name="date" class="form-control" value="{{ old('date') ?? date('Y-m-d') }}" required></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Note</label>
        <div class="col-md-10"><textarea name="note" rows="3" class="form-control">{{ old('note') }}</textarea></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Attachment</label>
        <div class="col-md-10"><input type="file" name="attachment" class="form-control"></div>
      </div>
      <div class="d-flex justify-content-end">
        <a href="{{ route('admin.office.complaintbox.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection
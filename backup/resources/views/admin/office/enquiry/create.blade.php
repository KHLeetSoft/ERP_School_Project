@extends('admin.layout.app')

@section('title', 'Add Admission Enquiry')

@section('content')
<div class="card">
  <div class="card-header"><h4>Add Admission Enquiry</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.office.enquiry.store') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Student Name</label>
          <input type="text" name="student_name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Parent Name</label>
          <input type="text" name="parent_name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Contact Number</label>
          <input type="text" name="contact_number" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Class</label>
          <input type="text" name="class" class="form-control">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Date</label>
          <input type="date" name="date" class="form-control">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="New">New</option>
            <option value="In Progress">In Progress</option>
            <option value="Converted">Converted</option>
            <option value="Closed">Closed</option>
          </select>
        </div>
        <div class="col-12 mb-3">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-control"></textarea>
        </div>
        <div class="col-12 mb-3">
          <label class="form-label">Note</label>
          <textarea name="note" class="form-control"></textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="{{ route('admin.office.enquiry.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection 
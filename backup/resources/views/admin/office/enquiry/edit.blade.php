@extends('admin.layout.app')

@section('title', 'Edit Admission Enquiry')

@section('content')
<div class="card">
  <div class="card-header"><h4>Edit Admission Enquiry</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.office.enquiry.update', $enquiry->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Student Name</label>
          <input type="text" name="student_name" class="form-control" value="{{ $enquiry->student_name }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Parent Name</label>
          <input type="text" name="parent_name" class="form-control" value="{{ $enquiry->parent_name }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Contact Number</label>
          <input type="text" name="contact_number" class="form-control" value="{{ $enquiry->contact_number }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ $enquiry->email }}">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Class</label>
          <input type="text" name="class" class="form-control" value="{{ $enquiry->class }}">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Date</label>
          <input type="date" name="date" class="form-control" value="{{ optional($enquiry->date)->format('Y-m-d') }}">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            @foreach(['New','In Progress','Converted','Closed'] as $status)
              <option value="{{ $status }}" @selected($enquiry->status === $status)>{{ $status }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 mb-3">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-control">{{ $enquiry->address }}</textarea>
        </div>
        <div class="col-12 mb-3">
          <label class="form-label">Note</label>
          <textarea name="note" class="form-control">{{ $enquiry->note }}</textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ route('admin.office.enquiry.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection 
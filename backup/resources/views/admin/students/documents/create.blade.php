@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header"><h4 class="mb-0">Upload Document</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.students.documents.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Student</label>
          <select name="student_id" class="form-select" required>
            <option value="">Select Student</option>
            @foreach($students as $s)
              <option value="{{ $s->id }}">{{ trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? '')) ?: ($s->user->name ?? 'Student') }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Document File</label>
          <input type="file" name="file" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Type</label>
          <input type="text" name="document_type" class="form-control" placeholder="e.g., ID Card, Certificate">
        </div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Issued Date</label>
          <input type="date" name="issued_date" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Expiry Date</label>
          <input type="date" name="expiry_date" class="form-control">
        </div>
        <div class="col-md-12">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.students.documents.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection



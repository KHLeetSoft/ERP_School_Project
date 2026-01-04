@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header"><h4 class="mb-0">Edit Document</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.students.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Student</label>
          <select name="student_id" class="form-select" required>
            @foreach($students as $s)
              <option value="{{ $s->id }}" {{ $s->id == $document->student_id ? 'selected' : '' }}>{{ trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? '')) ?: ($s->user->name ?? 'Student') }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Replace File (optional)</label>
          <input type="file" name="file" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" value="{{ $document->title }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Type</label>
          <input type="text" name="document_type" class="form-control" value="{{ $document->document_type }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            @foreach(['active','inactive'] as $st)
              <option value="{{ $st }}" {{ $document->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Issued Date</label>
          <input type="date" name="issued_date" class="form-control" value="{{ optional($document->issued_date)->format('Y-m-d') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Expiry Date</label>
          <input type="date" name="expiry_date" class="form-control" value="{{ optional($document->expiry_date)->format('Y-m-d') }}">
        </div>
        <div class="col-md-12">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="3">{{ $document->notes }}</textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.students.documents.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection



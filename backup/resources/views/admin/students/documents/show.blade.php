@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Document Details</h4>
    <a href="{{ route('admin.students.documents.index') }}" class="btn btn-sm btn-secondary">Back</a>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Student</label>
        <div class="form-control bg-light">{{ trim(($document->student->first_name ?? '') . ' ' . ($document->student->last_name ?? '')) ?: ($document->student->user->name ?? '-') }}</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Title</label>
        <div class="form-control bg-light">{{ $document->title ?? '-' }}</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Type</label>
        <div class="form-control bg-light">{{ $document->document_type ?? '-' }}</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Issued Date</label>
        <div class="form-control bg-light">{{ optional($document->issued_date)->format('Y-m-d') }}</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Expiry Date</label>
        <div class="form-control bg-light">{{ optional($document->expiry_date)->format('Y-m-d') }}</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">File</label>
        <div class="form-control bg-light">
          @if($document->file_path)
            <a target="_blank" href="{{ Storage::url($document->file_path) }}">View / Download</a>
          @else
            -
          @endif
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Status</label>
        <div class="form-control bg-light">{{ ucfirst($document->status ?? 'active') }}</div>
      </div>
      <div class="col-md-12">
        <label class="form-label">Notes</label>
        <div class="form-control bg-light">{{ $document->notes ?? '-' }}</div>
      </div>
    </div>
  </div>
</div>
@endsection



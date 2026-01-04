@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Promotion Details</h4>
    <div>
      <a href="{{ route('admin.students.promotions.edit', $promotion->id) }}" class="btn btn-sm btn-primary me-2">Edit</a>
      <a href="{{ route('admin.students.promotions.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Student</label>
        <div class="form-control bg-light">{{ trim(($promotion->student->first_name ?? '') . ' ' . ($promotion->student->last_name ?? '')) ?: ($promotion->student->user->name ?? '-') }}</div>
      </div>
      <div class="col-md-3">
        <label class="form-label">From Class</label>
        <div class="form-control bg-light">{{ $promotion->fromClass->name ?? '-' }}</div>
      </div>
      <div class="col-md-3">
        <label class="form-label">From Section</label>
        <div class="form-control bg-light">{{ $promotion->fromSection->name ?? '-' }}</div>
      </div>
      <div class="col-md-3">
        <label class="form-label">To Class</label>
        <div class="form-control bg-light">{{ $promotion->toClass->name ?? '-' }}</div>
      </div>
      <div class="col-md-3">
        <label class="form-label">To Section</label>
        <div class="form-control bg-light">{{ $promotion->toSection->name ?? '-' }}</div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Promoted At</label>
        <div class="form-control bg-light">{{ optional($promotion->promoted_at)->format('Y-m-d') }}</div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <div class="form-control bg-light">{{ ucfirst($promotion->status) }}</div>
      </div>
      <div class="col-md-12">
        <label class="form-label">Remarks</label>
        <div class="form-control bg-light">{{ $promotion->remarks ?? '-' }}</div>
      </div>
    </div>
  </div>
</div>
@endsection



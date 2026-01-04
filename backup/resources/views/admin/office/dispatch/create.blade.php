@extends('admin.layout.app')

@section('title','Add Postal Dispatch')

@section('content')
<div class="card">
  <div class="card-header"><h4 class="mb-0">Add Postal Dispatch</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.office.dispatch.store') }}" method="POST">
      @csrf
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">To Title</label>
        <div class="col-md-10"><input type="text" name="to_title" class="form-control" required></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Reference No</label>
        <div class="col-md-10"><input type="text" name="reference_no" class="form-control"></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Address</label>
        <div class="col-md-10"><textarea name="address" class="form-control" rows="2"></textarea></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">From Title</label>
        <div class="col-md-10"><input type="text" name="from_title" class="form-control"></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Date</label>
        <div class="col-md-10"><input type="date" name="date" class="form-control"></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Note</label>
        <div class="col-md-10"><textarea name="note" rows="3" class="form-control"></textarea></div>
      </div>
      <div class="d-flex justify-content-end">
        <a href="{{ route('admin.office.dispatch.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection 
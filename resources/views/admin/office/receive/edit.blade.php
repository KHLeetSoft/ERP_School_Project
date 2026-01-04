@extends('admin.layout.app')

@section('title','Edit Postal Receive')

@section('content')
<div class="card">
  <div class="card-header"><h4 class="mb-0">Edit Postal Receive</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.office.receive.update', $item->id) }}" method="POST">
      @csrf @method('PUT')
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">From Title</label>
        <div class="col-md-10"><input type="text" name="from_title" class="form-control" value="{{ old('from_title',$item->from_title) }}" required></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Reference No</label>
        <div class="col-md-10"><input type="text" name="reference_no" class="form-control" value="{{ old('reference_no',$item->reference_no) }}"></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Address</label>
        <div class="col-md-10"><textarea name="address" class="form-control" rows="2">{{ old('address',$item->address) }}</textarea></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">To Title</label>
        <div class="col-md-10"><input type="text" name="to_title" class="form-control" value="{{ old('to_title',$item->to_title) }}"></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Date</label>
        <div class="col-md-10"><input type="date" name="date" class="form-control" value="{{ old('date',$item->date?->format('Y-m-d')) }}"></div>
      </div>
      <div class="row mb-3">
        <label class="col-md-2 col-form-label">Note</label>
        <div class="col-md-10"><textarea name="note" rows="3" class="form-control">{{ old('note',$item->note) }}</textarea></div>
      </div>
      <div class="d-flex justify-content-end">
        <a href="{{ route('admin.office.receive.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>
@endsection
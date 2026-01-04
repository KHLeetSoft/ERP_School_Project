@extends('admin.layout.app')

@section('title','Edit Visitor')

@section('content')
<div class="card">
  <div class="card-header"><h4>Edit Visitor</h4></div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.office.visitors.update',$visitor->id) }}">
      @csrf @method('PUT')
      <div class="row">
        <div class="col-md-6 mb-3"><label class="form-label">Visitor Name</label><input name="visitor_name" class="form-control" value="{{ $visitor->visitor_name }}" required></div>
        <div class="col-md-6 mb-3"><label class="form-label">Purpose</label><input name="purpose" class="form-control" value="{{ $visitor->purpose }}"></div>
        <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input name="phone" class="form-control" value="{{ $visitor->phone }}"></div>
        <div class="col-md-3 mb-3"><label class="form-label">Date</label><input type="date" name="date" class="form-control" value="{{ optional($visitor->date)->format('Y-m-d') }}"></div>
        <div class="col-md-3 mb-3"><label class="form-label">In Time</label><input type="time" name="in_time" class="form-control" value="{{ $visitor->in_time }}"></div>
        <div class="col-md-3 mb-3"><label class="form-label">Out Time</label><input type="time" name="out_time" class="form-control" value="{{ $visitor->out_time }}"></div>
        <div class="col-12 mb-3"><label class="form-label">Note</label><textarea name="note" class="form-control">{{ $visitor->note }}</textarea></div>
      </div>
      <button class="btn btn-primary">Update</button>
      <a href="{{ route('admin.office.visitors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
@endsection 
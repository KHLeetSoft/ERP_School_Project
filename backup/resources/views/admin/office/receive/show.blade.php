@extends('admin.layout.app')

@section('title','Postal Receive Details')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Postal Receive Details</h4>
    <a href="{{ route('admin.office.receive.index') }}" class="btn btn-secondary btn-sm">Back</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr><th>From Title</th><td>{{ $item->from_title }}</td></tr>
      <tr><th>Reference No</th><td>{{ $item->reference_no }}</td></tr>
      <tr><th>Address</th><td>{{ $item->address }}</td></tr>
      <tr><th>To Title</th><td>{{ $item->to_title }}</td></tr>
      <tr><th>Date</th><td>{{ $item->date }}</td></tr>
      <tr><th>Note</th><td>{{ $item->note }}</td></tr>
    </table>
  </div>
</div>
@endsection
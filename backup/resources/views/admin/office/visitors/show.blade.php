@extends('admin.layout.app')

@section('title','Visitor Details')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between"><h4>Visitor Details</h4><a href="{{ route('admin.office.visitors.edit',$visitor->id) }}" class="btn btn-primary btn-sm">Edit</a></div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr><th>Name</th><td>{{ $visitor->visitor_name }}</td></tr>
      <tr><th>Purpose</th><td>{{ $visitor->purpose }}</td></tr>
      <tr><th>Phone</th><td>{{ $visitor->phone }}</td></tr>
      <tr><th>Date</th><td>{{ $visitor->date }}</td></tr>
      <tr><th>In Time</th><td>{{ $visitor->in_time }}</td></tr>
      <tr><th>Out Time</th><td>{{ $visitor->out_time }}</td></tr>
      <tr><th>Note</th><td>{{ $visitor->note }}</td></tr>
    </table>
  </div>
</div>
@endsection 
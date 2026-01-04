@extends('admin.layout.app')

@section('title','Enquiry Dashboard')

@section('content')
<div class="row g-3">
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5>Total Enquiries</h5>
        <h2>{{ $total }}</h2>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5>Converted</h5>
        <h2>{{ $converted }}</h2>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5>In Progress</h5>
        <h2>{{ $inProgress }}</h2>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5>Closed</h5>
        <h2>{{ $closed }}</h2>
      </div>
    </div>
  </div>
</div>
@endsection 
@extends('admin.layout.app')

@section('title', 'Enquiry Details')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Enquiry Details</h4>
    <a href="{{ route('admin.office.enquiry.edit', $enquiry->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr><th>Student</th><td>{{ $enquiry->student_name }}</td></tr>
      <tr><th>Parent</th><td>{{ $enquiry->parent_name }}</td></tr>
      <tr><th>Contact</th><td>{{ $enquiry->contact_number }}</td></tr>
      <tr><th>Email</th><td>{{ $enquiry->email }}</td></tr>
      <tr><th>Class</th><td>{{ $enquiry->class }}</td></tr>
      <tr><th>Status</th><td>{{ $enquiry->status }}</td></tr>
      <tr><th>Date</th><td>{{ $enquiry->date }}</td></tr>
      <tr><th>Address</th><td>{{ $enquiry->address }}</td></tr>
      <tr><th>Note</th><td>{{ $enquiry->note }}</td></tr>
    </table>
  </div>
</div>
@endsection 
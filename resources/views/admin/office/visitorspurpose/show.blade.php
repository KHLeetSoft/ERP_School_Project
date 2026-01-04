@extends('admin.layout.app')

@section('title','Visitor Purpose Details')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h4>Visitor Purpose Details</h4>
    <a href="{{ route('admin.office.visitorspurpose.edit', $purpose->id) }}" class="btn btn-primary btn-sm">Edit</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th width="200">Name</th>
        <td>{{ $purpose->name }}</td>
      </tr>
      <tr>
        <th>Description</th>
        <td>{{ $purpose->description }}</td>
      </tr>
      <tr>
        <th>Status</th>
        <td>
          @if($purpose->status)
            <span class="badge bg-success">Active</span>
          @else
            <span class="badge bg-danger">Inactive</span>
          @endif
        </td>
      </tr>
      <tr>
        <th>Created At</th>
        <td>{{ $purpose->created_at->format('Y-m-d H:i:s') }}</td>
      </tr>
      <tr>
        <th>Updated At</th>
        <td>{{ $purpose->updated_at->format('Y-m-d H:i:s') }}</td>
      </tr>
    </table>
    <div class="mt-3">
      <a href="{{ route('admin.office.visitorspurpose.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
  </div>
</div>
@endsection
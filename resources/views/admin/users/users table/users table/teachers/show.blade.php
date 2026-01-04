@extends('admin.layout.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Teacher Details</h4>
    </div>
    <div class="card-body">
        <p><strong>Name:</strong> {{ $teacher->name }}</p>
        <p><strong>Email:</strong> {{ $teacher->email }}</p>
        <p><strong>Status:</strong> 
            @if($teacher->status)
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-danger">Inactive</span>
            @endif
        </p>
        <a href="{{ route('admin.users.teachers.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>
</div>
@endsection

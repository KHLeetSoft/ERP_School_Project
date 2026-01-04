@extends('admin.layout.app')

@section('content')
<div class="card">
    <div class="card-header"><h4>Edit Teacher</h4></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.teachers.update', $teacher->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $teacher->name }}" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $teacher->email }}" required>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ $teacher->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$teacher->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
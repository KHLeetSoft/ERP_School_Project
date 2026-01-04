@extends('admin.layout.app')

@section('content')
<div class="card">
    <div class="card-header"><h4>Add Teacher</h4></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.teachers.store') }}">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection
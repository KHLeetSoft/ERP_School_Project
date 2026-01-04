@extends('superadmin.app')

@section('content')
    <div class="container mt-4">
        <h2>Create New Admin</h2>

        <form action="{{ route('superadmin.admins.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Role</label>
                <input type="text" name="role" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Admin</button>
        </form>
    </div>
@endsection

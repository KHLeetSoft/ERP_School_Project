@extends('superadmin.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h4>Edit Admin</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.admins.update', $admin->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="admin" {{ $admin->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="superadmin" {{ $admin->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>New Password (Optional)</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">Leave blank to keep existing password</small>
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection

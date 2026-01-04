@extends('superadmin.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Admin Details</h4>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $admin->name }}</p>
            <p><strong>Email:</strong> {{ $admin->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($admin->role) }}</p>
            <p><strong>School Assigned:</strong> 
                {{ $admin->managedSchool->name ?? 'Not Assigned' }}
            </p>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('superadmin.admins.edit', $admin->id) }}" class="btn btn-primary btn-sm">Edit</a>
            <a href="{{ route('superadmin.admins.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>
</div>
@endsection

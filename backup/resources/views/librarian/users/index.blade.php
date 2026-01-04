@extends('librarian.layout.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-user-graduate fa-2x text-primary"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                    <p class="text-muted mb-0">Total Students</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
                    <p class="text-muted mb-0">Total Teachers</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="modern-card p-4">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-users fa-2x text-info"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    <p class="text-muted mb-0">Total Users</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="modern-card p-4 mb-4">
    <form method="GET" action="{{ route('librarian.users') }}" class="row g-3">
        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                <option value="Student" {{ request('role') == 'Student' ? 'selected' : '' }}>Students</option>
                <option value="Teacher" {{ request('role') == 'Teacher' ? 'selected' : '' }}>Teachers</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i>Search
            </button>
        </div>
    </form>
</div>

<!-- Users List -->
<div class="modern-card p-4">
    <h5 class="mb-3">Users List</h5>
    @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    @if($user->employee_id)
                                        <small class="text-muted">ID: {{ $user->employee_id }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleName = $user->role_id == 6 ? 'Student' : ($user->role_id == 3 ? 'Teacher' : 'Unknown');
                                $roleColor = $user->role_id == 6 ? 'primary' : 'success';
                            @endphp
                            <span class="badge bg-{{ $roleColor }}">
                                {{ $roleName }}
                            </span>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $user->status ? 'success' : 'danger' }}">
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('librarian.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No users found</h5>
            <p class="text-muted">Try adjusting your search criteria.</p>
        </div>
    @endif
</div>
@endsection

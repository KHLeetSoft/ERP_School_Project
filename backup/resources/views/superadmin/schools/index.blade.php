
@extends('superadmin.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Schools Management</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add New School
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Logo</th>
                                    <th>School Name</th>
                                    <th>Admin</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schools as $school)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($school->logo)
                                            <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" class="img-thumbnail" style="width: 40px; height: 40px;">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 4px;">
                                                <i class="bx bxs-school text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $school->name }}</strong>
                                        @if($school->address)
                                            <br><small class="text-muted">{{ Str::limit($school->address, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($school->admin)
                                            {{ $school->admin->name }}
                                            <br><small class="text-muted">{{ $school->admin->email }}</small>
                                        @else
                                            <span class="text-danger">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $school->email ?: 'N/A' }}</td>
                                    <td>{{ $school->phone ?: 'N/A' }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                   data-school-id="{{ $school->id }}" 
                                                   {{ $school->status ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('superadmin.schools.show', $school) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('superadmin.schools.edit', $school) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete-school" 
                                                    data-school-id="{{ $school->id }}" 
                                                    data-school-name="{{ $school->name }}" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bx bxs-school display-4 text-muted"></i>
                                        <p class="mt-2">No schools found. <a href="{{ route('superadmin.schools.create') }}">Create your first school</a></p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                  @if (method_exists($schools, 'hasPages') && $schools->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $schools->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete school <strong id="schoolName"></strong>?</p>
                <p class="text-danger">This action cannot be undone and will also remove all associated users.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete School</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Status toggle
    $('.status-toggle').change(function() {
        const schoolId = $(this).data('school-id');
        const isChecked = $(this).is(':checked');
        
        $.ajax({
            url: `/superadmin/schools/${schoolId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('Failed to update status');
                // Revert toggle
                $(this).prop('checked', !isChecked);
            }
        });
    });

    // Delete confirmation
    $('.delete-school').click(function() {
        const schoolId = $(this).data('school-id');
        const schoolName = $(this).data('school-name');
        
        $('#schoolName').text(schoolName);
        $('#deleteForm').attr('action', `/superadmin/schools/${schoolId}`);
        $('#deleteModal').modal('show');
    });
});
</script>
@endsection

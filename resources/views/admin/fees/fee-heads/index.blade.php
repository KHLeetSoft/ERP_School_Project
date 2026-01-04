@extends('admin.layout.app')

@section('title', 'Fee Heads')
@section('page-title', 'Fee Heads Management')
@section('page-description', 'Manage fee heads for different types of fees')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Fee Heads
                </h5>
                <div class="card-tools">
                    <a href="{{ route('admin.fees.fee-heads.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Fee Head
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Frequency</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feeHeads as $feeHead)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $feeHead->name }}</strong>
                                            @if($feeHead->description)
                                                <br><small class="text-muted">{{ Str::limit($feeHead->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $feeHead->code }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $feeHead->type === 'mandatory' ? 'primary' : 'secondary' }}">
                                            {{ ucfirst($feeHead->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst($feeHead->frequency) }}
                                        </span>
                                    </td>
                                    <td>{{ $feeHead->sort_order }}</td>
                                    <td>
                                        @if($feeHead->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.fees.fee-heads.show', $feeHead) }}" 
                                               class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.fees.fee-heads.edit', $feeHead) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.fees.fee-heads.toggle-status', $feeHead) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-{{ $feeHead->is_active ? 'warning' : 'success' }}"
                                                        title="{{ $feeHead->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $feeHead->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.fees.fee-heads.destroy', $feeHead) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this fee head?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No fee heads found.</p>
                                        <a href="{{ route('admin.fees.fee-heads.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Add First Fee Head
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($feeHeads->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $feeHeads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

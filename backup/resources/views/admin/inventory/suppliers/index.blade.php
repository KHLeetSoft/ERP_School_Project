@extends('admin.layout.app')

@section('title', 'Suppliers Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Suppliers Management</h1>
        <div>
            <a href="{{ route('admin.inventory.suppliers.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Supplier
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Suppliers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_suppliers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Suppliers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_suppliers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Verified Suppliers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['verified_suppliers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Credit Limit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['total_credit_limit'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.suppliers.index') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by name, company, brand, contact...">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="verification_status" class="form-label">Verification</label>
                    <select class="form-control" id="verification_status" name="verification_status">
                        <option value="">All Suppliers</option>
                        <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ request('verification_status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.inventory.suppliers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Suppliers List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="suppliersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Company/Brand</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Credit Limit</th>
                            <th>Status</th>
                            <th>Verification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($supplier->logo)
                                            <img src="{{ Storage::url($supplier->logo) }}" alt="{{ $supplier->name }}" 
                                                 class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-truck text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $supplier->name }}</div>
                                            @if($supplier->contact_person)
                                                <small class="text-muted">{{ $supplier->contact_person }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($supplier->company)
                                            <div class="font-weight-bold">{{ $supplier->company }}</div>
                                        @endif
                                        @if($supplier->brand)
                                            <small class="text-muted">Brand: {{ $supplier->brand }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        @if($supplier->email)
                                            <div><i class="fas fa-envelope text-muted"></i> {{ $supplier->email }}</div>
                                        @endif
                                        @if($supplier->phone)
                                            <div><i class="fas fa-phone text-muted"></i> {{ $supplier->phone }}</div>
                                        @endif
                                        @if($supplier->mobile)
                                            <div><i class="fas fa-mobile-alt text-muted"></i> {{ $supplier->mobile }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        @if($supplier->city)
                                            <div>{{ $supplier->city }}</div>
                                        @endif
                                        @if($supplier->state)
                                            <div class="text-muted">{{ $supplier->state }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <div class="font-weight-bold">{{ $supplier->formatted_credit_limit }}</div>
                                        <small class="text-muted">{{ $supplier->payment_terms_days }} days</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $supplier->status_badge }}">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $verification = $supplier->verification_status;
                                    @endphp
                                    <span class="badge badge-{{ $verification['badge'] }}">
                                        <i class="fas fa-{{ $verification['status'] === 'verified' ? 'check' : 'exclamation' }}"></i>
                                        {{ $verification['text'] }}
                                    </span>
                                    @if($verification['date'])
                                        <br><small class="text-muted">{{ $verification['date'] }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.inventory.suppliers.show', $supplier) }}" 
                                           class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.inventory.suppliers.edit', $supplier) }}" 
                                           class="btn btn-primary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-{{ $supplier->is_verified ? 'warning' : 'success' }} btn-sm toggle-verification" 
                                                data-id="{{ $supplier->id }}" title="{{ $supplier->is_verified ? 'Unverify' : 'Verify' }}">
                                            <i class="fas fa-{{ $supplier->is_verified ? 'times' : 'check' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-{{ $supplier->status === 'active' ? 'secondary' : 'success' }} btn-sm toggle-status" 
                                                data-id="{{ $supplier->id }}" title="{{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $supplier->status === 'active' ? 'pause' : 'play' }}"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.inventory.suppliers.destroy', $supplier) }}" 
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No suppliers found</h5>
                                    <p class="text-muted">Start by adding your first supplier.</p>
                                    <a href="{{ route('admin.inventory.suppliers.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Supplier
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $suppliers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle verification status
    $('.toggle-verification').click(function() {
        const supplierId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: `{{ url('admin/inventory/suppliers') }}/${supplierId}/toggle-verification`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Reload page to update verification status
                    location.reload();
                }
            },
            error: function() {
                alert('Error updating verification status');
            }
        });
    });

    // Toggle status
    $('.toggle-status').click(function() {
        const supplierId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: `{{ url('admin/inventory/suppliers') }}/${supplierId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Reload page to update status
                    location.reload();
                }
            },
            error: function() {
                alert('Error updating status');
            }
        });
    });
});
</script>
@endpush

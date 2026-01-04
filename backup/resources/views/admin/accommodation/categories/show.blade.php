@extends('admin.layout.app')

@section('title', 'Hostel Category Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Hostel Category Details</h1>
            <p class="mb-0">View detailed information about the hostel category</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.hostel.categories.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Categories
            </a>
            <a href="{{ route('admin.hostel.categories.edit', $category) }}" class="btn btn-primary">
                <i class="bx bx-edit"></i> Edit Category
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Category Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-primary">{{ $category->name }}</h4>
                            <p class="text-muted">{{ $category->description }}</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Pricing Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Monthly Fee:</strong> {{ $category->formatted_monthly_fee }}</li>
                                        <li><strong>Security Deposit:</strong> {{ $category->formatted_security_deposit }}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Capacity Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Total Capacity:</strong> {{ $category->capacity }} rooms</li>
                                        <li><strong>Available Rooms:</strong> {{ $category->available_rooms }} rooms</li>
                                        <li><strong>Occupancy Rate:</strong> {{ $category->occupancy_rate }}%</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="{{ $category->main_image }}" alt="{{ $category->name }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                            <div>
                                <span class="badge {{ $category->status_badge_class }} fs-6">{{ $category->status_text }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facilities Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Facilities & Amenities</h6>
                </div>
                <div class="card-body">
                    @if($category->facilities && count($category->facilities) > 0)
                    <div class="row">
                        @foreach($category->facilities as $facility)
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                <span>{{ $facility }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">No facilities listed.</p>
                    @endif
                </div>
            </div>

            <!-- Rules Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rules & Regulations</h6>
                </div>
                <div class="card-body">
                    @if($category->rules && count($category->rules) > 0)
                    <div class="row">
                        @foreach($category->rules as $rule)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle text-info me-2"></i>
                                <span>{{ $rule }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">No rules specified.</p>
                    @endif
                </div>
            </div>

            <!-- Additional Images -->
            @if($category->images && count($category->images) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Additional Images</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($category->images as $image)
                        <div class="col-md-3 mb-3">
                            <img src="{{ asset('storage/' . $image) }}" alt="Category image" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.hostel.categories.edit', $category) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit Category
                        </a>
                        <button type="button" class="btn btn-{{ $category->status === 'active' ? 'warning' : 'success' }}" onclick="toggleStatus({{ $category->id }})">
                            <i class="bx bx-toggle-{{ $category->status === 'active' ? 'right' : 'left' }}"></i> 
                            {{ $category->status === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                        <a href="{{ route('admin.hostel.categories.duplicate', $category) }}" class="btn btn-info">
                            <i class="bx bx-copy"></i> Duplicate Category
                        </a>
                        <button type="button" class="btn btn-danger" onclick="deleteCategory({{ $category->id }})">
                            <i class="bx bx-trash"></i> Delete Category
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Occupancy Rate</span>
                            <span class="fw-bold">{{ $category->occupancy_rate }}%</span>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-{{ $category->occupancy_rate > 80 ? 'success' : ($category->occupancy_rate > 50 ? 'warning' : 'danger') }}" 
                                 style="width: {{ $category->occupancy_rate }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Available Rooms</span>
                            <span class="fw-bold text-success">{{ $category->available_rooms }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Occupied Rooms</span>
                            <span class="fw-bold text-danger">{{ $category->capacity - $category->available_rooms }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Total Capacity</span>
                            <span class="fw-bold text-info">{{ $category->capacity }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        <small class="text-muted">{{ $category->created_at->format('M d, Y \a\t h:i A') }}</small>
                    </div>
                    
                    @if($category->createdBy)
                    <div class="mb-3">
                        <strong>Created By:</strong><br>
                        <small class="text-muted">{{ $category->createdBy->name }}</small>
                    </div>
                    @endif
                    
                    @if($category->updated_at != $category->created_at)
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <small class="text-muted">{{ $category->updated_at->format('M d, Y \a\t h:i A') }}</small>
                    </div>
                    @endif
                    
                    @if($category->updatedBy)
                    <div class="mb-3">
                        <strong>Updated By:</strong><br>
                        <small class="text-muted">{{ $category->updatedBy->name }}</small>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <strong>School:</strong><br>
                        <small class="text-muted">{{ $category->school->name ?? 'N/A' }}</small>
                    </div>
                </div>
            </div>

            <!-- Related Information -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Total Rooms:</strong> {{ $category->rooms->count() }}<br>
                        <small class="text-muted">Number of rooms in this category</small>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Active Allocations:</strong> {{ $category->allocations->where('status', 'active')->count() }}<br>
                        <small class="text-muted">Current active room allocations</small>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Total Allocations:</strong> {{ $category->allocations->count() }}<br>
                        <small class="text-muted">All time room allocations</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle status function
function toggleStatus(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This will change the status of this category.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/hostel/categories/${id}/toggle-status`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('Success!', response.message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        }
    });
}

// Delete category function
function deleteCategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! This will also delete all related rooms and allocations.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/hostel/categories/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('Deleted!', 'Category has been deleted.', 'success').then(() => {
                        window.location.href = "{{ route('admin.hostel.categories.index') }}";
                    });
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        }
    });
}
</script>
@endpush

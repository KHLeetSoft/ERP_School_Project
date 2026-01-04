@extends('admin.layout.app')

@section('title', 'Edit Hostel Category')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Hostel Category</h1>
            <p class="mb-0">Update hostel accommodation category information</p>
        </div>
        <div>
            <a href="{{ route('admin.accommodation.categories.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.accommodation.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="maintenance" {{ old('status', $category->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="monthly_fee" class="form-label">Monthly Fee (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                           id="monthly_fee" name="monthly_fee" value="{{ old('monthly_fee', $category->monthly_fee) }}" 
                                           min="0" step="0.01" required>
                                    @error('monthly_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="security_deposit" class="form-label">Security Deposit (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('security_deposit') is-invalid @enderror" 
                                           id="security_deposit" name="security_deposit" value="{{ old('security_deposit', $category->security_deposit) }}" 
                                           min="0" step="0.01" required>
                                    @error('security_deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Total Capacity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" value="{{ old('capacity', $category->capacity) }}" 
                                           min="0" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="facilities" class="form-label">Facilities</label>
                            <div class="row">
                                <div class="col-md-6">
                                    @php
                                        $selectedFacilities = old('facilities', $category->facilities ?? []);
                                    @endphp
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="WiFi" id="facility_wifi" 
                                               {{ in_array('WiFi', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_wifi">WiFi</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Air Conditioning" id="facility_ac" 
                                               {{ in_array('Air Conditioning', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_ac">Air Conditioning</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Fan" id="facility_fan" 
                                               {{ in_array('Fan', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_fan">Fan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Study Desk" id="facility_desk" 
                                               {{ in_array('Study Desk', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_desk">Study Desk</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Wardrobe" id="facility_wardrobe" 
                                               {{ in_array('Wardrobe', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_wardrobe">Wardrobe</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Bed" id="facility_bed" 
                                               {{ in_array('Bed', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_bed">Bed</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Chair" id="facility_chair" 
                                               {{ in_array('Chair', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_chair">Chair</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Bookshelf" id="facility_bookshelf" 
                                               {{ in_array('Bookshelf', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_bookshelf">Bookshelf</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Mirror" id="facility_mirror" 
                                               {{ in_array('Mirror', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_mirror">Mirror</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Balcony" id="facility_balcony" 
                                               {{ in_array('Balcony', $selectedFacilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility_balcony">Balcony</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="rules" class="form-label">Rules</label>
                            <div class="row">
                                <div class="col-md-6">
                                    @php
                                        $selectedRules = old('rules', $category->rules ?? []);
                                    @endphp
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="No smoking" id="rule_smoking" 
                                               {{ in_array('No smoking', $selectedRules) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rule_smoking">No smoking</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="No alcohol" id="rule_alcohol" 
                                               {{ in_array('No alcohol', $selectedRules) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rule_alcohol">No alcohol</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="Quiet hours 10 PM - 6 AM" id="rule_quiet" 
                                               {{ in_array('Quiet hours 10 PM - 6 AM', $selectedRules) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rule_quiet">Quiet hours 10 PM - 6 AM</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="No pets allowed" id="rule_pets" 
                                               {{ in_array('No pets allowed', $selectedRules) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rule_pets">No pets allowed</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="No cooking in rooms" id="rule_cooking" 
                                               {{ in_array('No cooking in rooms', $selectedRules) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rule_cooking">No cooking in rooms</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="Visitors allowed till 8 PM" id="rule_visitors" 
                                               {{ in_array('Visitors allowed till 8 PM', $selectedRules) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rule_visitors">Visitors allowed till 8 PM</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="Keep room clean" id="rule_clean" 
                                               {{ in_array('Keep room clean', $selectedRules) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rule_clean">Keep room clean</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="Respect other residents" id="rule_respect" 
                                               {{ in_array('Respect other residents', $selectedRules) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rule_respect">Respect other residents</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Main Image</label>
                            @if($category->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="Current image" class="img-thumbnail" width="200">
                                <div class="form-text">Current image</div>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a new main image (max 2MB)</div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Additional Images</label>
                            @if($category->images && count($category->images) > 0)
                            <div class="mb-2">
                                <div class="row">
                                    @foreach($category->images as $image)
                                    <div class="col-md-2 mb-2">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Additional image" class="img-thumbnail" width="100">
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-text">Current additional images</div>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                   id="images" name="images[]" accept="image/*" multiple>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload new additional images (max 5 images, 2MB each)</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.accommodation.categories.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ $category->main_image }}" alt="{{ $category->name }}" class="rounded mb-3" width="120" height="120">
                        <h5>{{ $category->name }}</h5>
                        <p class="text-muted">{{ $category->description }}</p>
                        <span class="badge {{ $category->status_badge_class }}">{{ $category->status_text }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-primary">{{ $category->formatted_monthly_fee }}</h6>
                                <small class="text-muted">Monthly Fee</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success">{{ $category->formatted_security_deposit }}</h6>
                            <small class="text-muted">Security Deposit</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-info">{{ $category->capacity }}</h6>
                                <small class="text-muted">Total Capacity</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning">{{ $category->available_rooms }}</h6>
                            <small class="text-muted">Available Rooms</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.accommodation.categories.show', $category) }}" class="btn btn-outline-info">
                            <i class="bx bx-show"></i> View Details
                        </a>
                        <button type="button" class="btn btn-outline-warning" onclick="toggleStatus({{ $category->id }})">
                            <i class="bx bx-toggle-{{ $category->status === 'active' ? 'right' : 'left' }}"></i> 
                            {{ $category->status === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                        <a href="{{ route('admin.accommodation.categories.duplicate', $category) }}" class="btn btn-outline-success">
                            <i class="bx bx-copy"></i> Duplicate
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteCategory({{ $category->id }})">
                            <i class="bx bx-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview functionality
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').remove();
                $('#image').after('<div id="imagePreview" class="mt-2"><img src="' + e.target.result + '" class="img-thumbnail" width="200"></div>');
            };
            reader.readAsDataURL(file);
        }
    });

    // Multiple images preview
    $('#images').change(function() {
        const files = this.files;
        $('#imagesPreview').remove();
        if (files.length > 0) {
            let preview = '<div id="imagesPreview" class="mt-2"><h6>Preview:</h6><div class="row">';
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview += '<div class="col-md-4 mb-2"><img src="' + e.target.result + '" class="img-thumbnail" width="100"></div>';
                    if (i === files.length - 1) {
                        preview += '</div></div>';
                        $('#images').after(preview);
                    }
                };
                reader.readAsDataURL(files[i]);
            }
        }
    });

    // Form validation
    $('form').submit(function(e) {
        const monthlyFee = parseFloat($('#monthly_fee').val());
        const securityDeposit = parseFloat($('#security_deposit').val());
        const capacity = parseInt($('#capacity').val());

        if (monthlyFee < 0) {
            e.preventDefault();
            Swal.fire('Error', 'Monthly fee cannot be negative.', 'error');
            return false;
        }

        if (securityDeposit < 0) {
            e.preventDefault();
            Swal.fire('Error', 'Security deposit cannot be negative.', 'error');
            return false;
        }

        if (capacity < 0) {
            e.preventDefault();
            Swal.fire('Error', 'Capacity cannot be negative.', 'error');
            return false;
        }
    });
});

// Toggle status function
function toggleStatus(id) {
    $.ajax({
        url: `/admin/accommodation/categories/${id}/toggle-status`,
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

// Delete category function
function deleteCategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/accommodation/categories/${id}`,
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

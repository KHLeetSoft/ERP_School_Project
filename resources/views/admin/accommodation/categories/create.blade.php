@extends('admin.layout.app')

@section('title', 'Create Hostel Category')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create Hostel Category</h1>
            <p class="mb-0">Add a new hostel accommodation category</p>
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
                    <form action="{{ route('admin.accommodation.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="monthly_fee" class="form-label">Monthly Fee (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                           id="monthly_fee" name="monthly_fee" value="{{ old('monthly_fee') }}" 
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
                                           id="security_deposit" name="security_deposit" value="{{ old('security_deposit') }}" 
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
                                           id="capacity" name="capacity" value="{{ old('capacity') }}" 
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="WiFi" id="facility_wifi">
                                        <label class="form-check-label" for="facility_wifi">WiFi</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Air Conditioning" id="facility_ac">
                                        <label class="form-check-label" for="facility_ac">Air Conditioning</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Fan" id="facility_fan">
                                        <label class="form-check-label" for="facility_fan">Fan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Study Desk" id="facility_desk">
                                        <label class="form-check-label" for="facility_desk">Study Desk</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Wardrobe" id="facility_wardrobe">
                                        <label class="form-check-label" for="facility_wardrobe">Wardrobe</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Bed" id="facility_bed">
                                        <label class="form-check-label" for="facility_bed">Bed</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Chair" id="facility_chair">
                                        <label class="form-check-label" for="facility_chair">Chair</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Bookshelf" id="facility_bookshelf">
                                        <label class="form-check-label" for="facility_bookshelf">Bookshelf</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Mirror" id="facility_mirror">
                                        <label class="form-check-label" for="facility_mirror">Mirror</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" value="Balcony" id="facility_balcony">
                                        <label class="form-check-label" for="facility_balcony">Balcony</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="rules" class="form-label">Rules</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="No smoking" id="rule_smoking">
                                        <label class="form-check-label" for="rule_smoking">No smoking</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="No alcohol" id="rule_alcohol">
                                        <label class="form-check-label" for="rule_alcohol">No alcohol</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="Quiet hours 10 PM - 6 AM" id="rule_quiet">
                                        <label class="form-check-label" for="rule_quiet">Quiet hours 10 PM - 6 AM</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="No pets allowed" id="rule_pets">
                                        <label class="form-check-label" for="rule_pets">No pets allowed</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="No cooking in rooms" id="rule_cooking">
                                        <label class="form-check-label" for="rule_cooking">No cooking in rooms</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="Visitors allowed till 8 PM" id="rule_visitors">
                                        <label class="form-check-label" for="rule_visitors">Visitors allowed till 8 PM</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="Keep room clean" id="rule_clean">
                                        <label class="form-check-label" for="rule_clean">Keep room clean</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rules[]" value="Respect other residents" id="rule_respect">
                                        <label class="form-check-label" for="rule_respect">Respect other residents</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Main Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a main image for this category (max 2MB)</div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Additional Images</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                   id="images" name="images[]" accept="image/*" multiple>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload additional images (max 5 images, 2MB each)</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.accommodation.categories.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">School Information</h6>
                </div>
                <div class="card-body">
                    @if($school)
                    <div class="text-center">
                        <img src="{{ $school->logo ? asset('storage/' . $school->logo) : asset('images/default-school.png') }}" 
                             alt="{{ $school->name }}" class="rounded mb-3" width="80" height="80">
                        <h5>{{ $school->name }}</h5>
                        <p class="text-muted">{{ $school->address }}</p>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="bx bx-info-circle"></i> No school information available.
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            <small>Provide clear and accurate pricing information</small>
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            <small>Set realistic capacity based on available space</small>
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            <small>List all available facilities and amenities</small>
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            <small>Define clear rules and regulations</small>
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            <small>Upload high-quality images for better presentation</small>
                        </li>
                    </ul>
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
</script>
@endpush

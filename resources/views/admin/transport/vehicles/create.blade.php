@extends('admin.layout.app')

@section('title', 'Add New Vehicle')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">🚗 Add New Vehicle</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.vehicles.index') }}">Transport Vehicles</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.transport.vehicles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Vehicles
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Vehicle Form -->
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>
                        Vehicle Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.transport.vehicles.store') }}" method="POST" enctype="multipart/form-data" id="vehicleForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_number" class="form-label fw-bold">Vehicle Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                       id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number') }}" 
                                       placeholder="e.g., V001" required>
                                @error('vehicle_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="registration_number" class="form-label fw-bold">Registration Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                       id="registration_number" name="registration_number" value="{{ old('registration_number') }}" 
                                       placeholder="e.g., MH12AB1234" required>
                                @error('registration_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="vehicle_type" class="form-label fw-bold">Vehicle Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" required>
                                    <option value="">Select Type</option>
                                    @foreach($vehicleTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('vehicle_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="brand" class="form-label fw-bold">Brand <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                       id="brand" name="brand" value="{{ old('brand') }}" 
                                       placeholder="e.g., Tata, Ashok Leyland" required>
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="model" class="form-label fw-bold">Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                       id="model" name="model" value="{{ old('model') }}" 
                                       placeholder="e.g., 407, Starbus" required>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="year_of_manufacture" class="form-label fw-bold">Year of Manufacture <span class="text-danger">*</span></label>
                                <select class="form-select @error('year_of_manufacture') is-invalid @enderror" id="year_of_manufacture" name="year_of_manufacture" required>
                                    <option value="">Select Year</option>
                                    @for($year = date('Y') + 1; $year >= 1990; $year--)
                                        <option value="{{ $year }}" {{ old('year_of_manufacture') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                                @error('year_of_manufacture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="seating_capacity" class="form-label fw-bold">Seating Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('seating_capacity') is-invalid @enderror" 
                                       id="seating_capacity" name="seating_capacity" value="{{ old('seating_capacity') }}" 
                                       placeholder="e.g., 35" min="1" max="100" required>
                                @error('seating_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Fuel & Performance -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-gas-pump me-2"></i>Fuel & Performance
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fuel_type" class="form-label fw-bold">Fuel Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('fuel_type') is-invalid @enderror" id="fuel_type" name="fuel_type" required>
                                    <option value="">Select Fuel Type</option>
                                    @foreach($fuelTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('fuel_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('fuel_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fuel_efficiency" class="form-label fw-bold">Fuel Efficiency (km/l)</label>
                                <input type="number" class="form-control @error('fuel_efficiency') is-invalid @enderror" 
                                       id="fuel_efficiency" name="fuel_efficiency" value="{{ old('fuel_efficiency') }}" 
                                       placeholder="e.g., 8.5" min="0" step="0.1">
                                @error('fuel_efficiency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-file-alt me-2"></i>Documents & Certificates
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="insurance_number" class="form-label fw-bold">Insurance Number</label>
                                <input type="text" class="form-control @error('insurance_number') is-invalid @enderror" 
                                       id="insurance_number" name="insurance_number" value="{{ old('insurance_number') }}" 
                                       placeholder="Insurance policy number">
                                @error('insurance_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="insurance_expiry" class="form-label fw-bold">Insurance Expiry Date</label>
                                <input type="date" class="form-control @error('insurance_expiry') is-invalid @enderror" 
                                       id="insurance_expiry" name="insurance_expiry" value="{{ old('insurance_expiry') }}">
                                @error('insurance_expiry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="permit_number" class="form-label fw-bold">Permit Number</label>
                                <input type="text" class="form-control @error('permit_number') is-invalid @enderror" 
                                       id="permit_number" name="permit_number" value="{{ old('permit_number') }}" 
                                       placeholder="Transport permit number">
                                @error('permit_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="permit_expiry" class="form-label fw-bold">Permit Expiry Date</label>
                                <input type="date" class="form-control @error('permit_expiry') is-invalid @enderror" 
                                       id="permit_expiry" name="permit_expiry" value="{{ old('permit_expiry') }}">
                                @error('permit_expiry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fitness_certificate_number" class="form-label fw-bold">Fitness Certificate Number</label>
                                <input type="text" class="form-control @error('fitness_certificate_number') is-invalid @enderror" 
                                       id="fitness_certificate_number" name="fitness_certificate_number" value="{{ old('fitness_certificate_number') }}" 
                                       placeholder="Fitness certificate number">
                                @error('fitness_certificate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fitness_expiry" class="form-label fw-bold">Fitness Expiry Date</label>
                                <input type="date" class="form-control @error('fitness_expiry') is-invalid @enderror" 
                                       id="fitness_expiry" name="fitness_expiry" value="{{ old('fitness_expiry') }}">
                                @error('fitness_expiry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="puc_certificate_number" class="form-label fw-bold">PUC Certificate Number</label>
                                <input type="text" class="form-control @error('puc_certificate_number') is-invalid @enderror" 
                                       id="puc_certificate_number" name="puc_certificate_number" value="{{ old('puc_certificate_number') }}" 
                                       placeholder="PUC certificate number">
                                @error('puc_certificate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="puc_expiry" class="form-label fw-bold">PUC Expiry Date</label>
                                <input type="date" class="form-control @error('puc_expiry') is-invalid @enderror" 
                                       id="puc_expiry" name="puc_expiry" value="{{ old('puc_expiry') }}">
                                @error('puc_expiry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Staff Assignment -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-users me-2"></i>Staff Assignment
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="driver_id" class="form-label fw-bold">Driver</label>
                                <select class="form-select @error('driver_id') is-invalid @enderror" id="driver_id" name="driver_id">
                                    <option value="">Select Driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->name }} ({{ $driver->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="conductor_id" class="form-label fw-bold">Conductor</label>
                                <select class="form-select @error('conductor_id') is-invalid @enderror" id="conductor_id" name="conductor_id">
                                    <option value="">Select Conductor</option>
                                    @foreach($conductors as $conductor)
                                        <option value="{{ $conductor->id }}" {{ old('conductor_id') == $conductor->id ? 'selected' : '' }}>
                                            {{ $conductor->name }} ({{ $conductor->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('conductor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="assigned_route_id" class="form-label fw-bold">Assigned Route</label>
                                <select class="form-select @error('assigned_route_id') is-invalid @enderror" id="assigned_route_id" name="assigned_route_id">
                                    <option value="">Select Route</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}" {{ old('assigned_route_id') == $route->id ? 'selected' : '' }}>
                                            {{ $route->route_name }} ({{ $route->route_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_route_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-plus-circle me-2"></i>Additional Information
                                </h6>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Additional details about the vehicle...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="images" class="form-label fw-bold">Vehicle Images</label>
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                       id="images" name="images[]" multiple accept="image/*">
                                <small class="form-text text-muted">You can select multiple images. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB each.</small>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.transport.vehicles.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Vehicle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Help Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>Quick Help
                    </h6>
                </div>
                <div class="card-body">
                    <div class="help-item mb-3">
                        <h6 class="text-primary">Vehicle Number</h6>
                        <p class="text-muted small">Unique identifier for your vehicle (e.g., V001, V002)</p>
                    </div>
                    <div class="help-item mb-3">
                        <h6 class="text-primary">Registration Number</h6>
                        <p class="text-muted small">Official vehicle registration from RTO (e.g., MH12AB1234)</p>
                    </div>
                    <div class="help-item mb-3">
                        <h6 class="text-primary">Documents</h6>
                        <p class="text-muted small">All documents should have future expiry dates</p>
                    </div>
                    <div class="help-item">
                        <h6 class="text-primary">Images</h6>
                        <p class="text-muted small">Upload clear images of the vehicle for identification</p>
                    </div>
                </div>
            </div>

            <!-- Form Preview -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>Form Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div id="formPreview">
                        <p class="text-muted text-center">Fill the form to see preview</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#vehicleForm').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            toastr.error('Please fill all required fields');
        }
    });

    // Real-time form preview
    function updatePreview() {
        const vehicleNumber = $('#vehicle_number').val() || 'V001';
        const brand = $('#brand').val() || 'Brand';
        const model = $('#model').val() || 'Model';
        const vehicleType = $('#vehicle_type option:selected').text() || 'Type';
        const seatingCapacity = $('#seating_capacity').val() || '0';
        
        const preview = `
            <div class="text-center">
                <div class="vehicle-preview mb-3">
                    <i class="fas fa-bus fa-3x text-primary"></i>
                </div>
                <h6 class="fw-bold">${brand} ${model}</h6>
                <p class="text-muted mb-1">${vehicleType}</p>
                <p class="text-muted mb-1">#${vehicleNumber}</p>
                <p class="text-muted mb-0">${seatingCapacity} seats</p>
            </div>
        `;
        
        $('#formPreview').html(preview);
    }

    // Update preview on input change
    $('#vehicle_number, #brand, #model, #vehicle_type, #seating_capacity').on('input change', updatePreview);
    
    // Initial preview
    updatePreview();
});
</script>
@endsection

@section('styles')
<style>
.card {
    border-radius: 12px;
    border: none;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    transform: translateY(-1px);
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.help-item h6 {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.help-item p {
    font-size: 0.8rem;
    line-height: 1.4;
}

.vehicle-preview {
    padding: 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.text-primary {
    color: #007bff !important;
}

.bg-primary {
    background-color: #007bff !important;
}

.bg-info {
    background-color: #17a2b8 !important;
}
</style>
@endsection

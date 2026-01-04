@extends('admin.layout.app')

@section('title', 'Create Transport Assignment')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.hero-header {
    background: var(--primary-gradient);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 15px;
    position: relative;
    overflow: hidden;
}

.hero-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 200"><path d="M0,100 C150,200 350,0 500,100 C650,200 850,0 1000,100 L1000,00 L0,0 Z" style="fill:rgba(255,255,255,0.1)"></path></svg>');
    background-size: cover;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.form-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.form-body {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
}

.input-group-text {
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 10px 0 0 10px;
}

.btn {
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.btn-success {
    background: var(--success-gradient);
    color: white;
}

.btn-outline-secondary {
    border: 2px solid #e2e8f0;
    color: #718096;
}

.section-divider {
    border: none;
    height: 2px;
    background: var(--primary-gradient);
    margin: 2rem 0;
    border-radius: 1px;
}

.info-box {
    background: linear-gradient(135deg, #e6fffa 0%, #f0fff4 100%);
    border: 2px solid #38b2ac;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.required {
    color: #e53e3e;
}

.floating-label {
    position: relative;
}

.floating-label .form-control {
    padding-top: 1.5rem;
    padding-bottom: 0.5rem;
}

.floating-label .form-label {
    position: absolute;
    top: 0;
    left: 1rem;
    padding: 0.25rem 0.5rem;
    background: white;
    color: #718096;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    z-index: 2;
}

.form-control:focus + .form-label,
.form-control:not(:placeholder-shown) + .form-label {
    top: -0.5rem;
    font-size: 0.75rem;
    color: #667eea;
}

.shift-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 0.5rem;
}

.shift-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.shift-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
}

.shift-card.active {
    border-color: #667eea;
    background: var(--primary-gradient);
    color: white;
}

.shift-icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.submit-section {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 1.5rem 2rem;
    border-top: 1px solid #e2e8f0;
    text-align: center;
}

.loading-spinner {
    display: none;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.select2-container--default .select2-selection--single {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    height: 48px;
    padding: 0.75rem 1rem;
    background: #f8fafc;
}

.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Hero Header -->
    <div class="hero-header">
        <div class="hero-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1><i class="fas fa-plus-circle me-3"></i>Create New Transport Assignment</h1>
                    <p class="mb-0 opacity-90">Assign vehicles and routes to create efficient transport schedules</p>
                    <nav aria-label="breadcrumb" class="mt-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.transport.assign.index') }}" class="text-white-50"><i class="fas fa-route me-1"></i>Assignments</a></li>
                            <li class="breadcrumb-item active text-white">Create New</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('admin.transport.assign.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <form id="assignmentForm" class="needs-validation" novalidate>
                @csrf
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Assignment Details</h4>
                    </div>
                    
                    <div class="form-body">
                        <!-- Info Box -->
                        <div class="info-box">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-info me-2"></i>
                                <span><strong>Note:</strong> All required fields must be filled. The system will check for conflicts automatically.</span>
                            </div>
                        </div>

                        <!-- Vehicle & Route Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-bus me-1"></i>Vehicle <span class="required">*</span>
                                    </label>
                                    <select name="vehicle_id" id="vehicle_id" class="form-select" required>
                                        <option value="">Select Vehicle</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" 
                                                    data-capacity="{{ $vehicle->capacity ?? 'N/A' }}"
                                                    data-type="{{ $vehicle->vehicle_type ?? 'N/A' }}">
                                                {{ $vehicle->vehicle_number }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a vehicle.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-route me-1"></i>Route <span class="required">*</span>
                                    </label>
                                    <select name="route_id" id="route_id" class="form-select" required>
                                        <option value="">Select Route</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}"
                                                    data-distance="{{ $route->distance ?? 'N/A' }}"
                                                    data-duration="{{ $route->estimated_duration ?? 'N/A' }}">
                                                {{ $route->route_name }} ({{ $route->route_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a route.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Staff Assignment -->
                        <hr class="section-divider">
                        <h5 class="mb-3"><i class="fas fa-users me-2"></i>Staff Assignment</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user-tie me-1"></i>Driver
                                    </label>
                                    <select name="driver_id" id="driver_id" class="form-select">
                                        <option value="">Select Driver</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}">
                                                {{ $driver->name }} ({{ $driver->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user me-1"></i>Conductor
                                    </label>
                                    <select name="conductor_id" id="conductor_id" class="form-select">
                                        <option value="">Select Conductor</option>
                                        @foreach($conductors as $conductor)
                                            <option value="{{ $conductor->id }}">
                                                {{ $conductor->name }} ({{ $conductor->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Details -->
                        <hr class="section-divider">
                        <h5 class="mb-3"><i class="fas fa-clock me-2"></i>Schedule Details</h5>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Assignment Date <span class="required">*</span>
                                    </label>
                                    <input type="date" name="assignment_date" id="assignment_date" 
                                           class="form-control" required min="{{ date('Y-m-d') }}">
                                    <div class="invalid-feedback">Please select an assignment date.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-play me-1"></i>Start Time <span class="required">*</span>
                                    </label>
                                    <input type="time" name="start_time" id="start_time" 
                                           class="form-control" required>
                                    <div class="invalid-feedback">Please select start time.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-stop me-1"></i>End Time <span class="required">*</span>
                                    </label>
                                    <input type="time" name="end_time" id="end_time" 
                                           class="form-control" required>
                                    <div class="invalid-feedback">Please select end time.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Shift Type Selection -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sun me-1"></i>Shift Type <span class="required">*</span>
                            </label>
                            <input type="hidden" name="shift_type" id="shift_type" required>
                            <div class="shift-cards">
                                <div class="shift-card" data-shift="morning">
                                    <div class="shift-icon"><i class="fas fa-sunrise"></i></div>
                                    <div class="fw-bold">Morning</div>
                                    <small>6:00 AM - 12:00 PM</small>
                                </div>
                                <div class="shift-card" data-shift="afternoon">
                                    <div class="shift-icon"><i class="fas fa-sun"></i></div>
                                    <div class="fw-bold">Afternoon</div>
                                    <small>12:00 PM - 6:00 PM</small>
                                </div>
                                <div class="shift-card" data-shift="evening">
                                    <div class="shift-icon"><i class="fas fa-sunset"></i></div>
                                    <div class="fw-bold">Evening</div>
                                    <small>6:00 PM - 12:00 AM</small>
                                </div>
                                <div class="shift-card" data-shift="night">
                                    <div class="shift-icon"><i class="fas fa-moon"></i></div>
                                    <div class="fw-bold">Night</div>
                                    <small>12:00 AM - 6:00 AM</small>
                                </div>
                                <div class="shift-card" data-shift="full_day">
                                    <div class="shift-icon"><i class="fas fa-clock"></i></div>
                                    <div class="fw-bold">Full Day</div>
                                    <small>24 Hours</small>
                                </div>
                            </div>
                            <div class="invalid-feedback">Please select a shift type.</div>
                        </div>

                        <!-- Status & Notes -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-flag me-1"></i>Status <span class="required">*</span>
                                    </label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="pending">Pending</option>
                                        <option value="active">Active</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="delayed">Delayed</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a status.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-sticky-note me-1"></i>Notes
                                    </label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" 
                                              placeholder="Additional notes or instructions..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="history.back()">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <span class="loading-spinner me-2"></span>
                                <i class="fas fa-save me-2"></i>Create Assignment
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#vehicle_id, #route_id, #driver_id, #conductor_id').select2({
        theme: 'default',
        placeholder: function() {
            return $(this).data('placeholder');
        }
    });

    // Initialize date picker
    flatpickr("#assignment_date", {
        minDate: "today",
        dateFormat: "Y-m-d",
        allowInput: true
    });

    // Shift type selection
    $('.shift-card').on('click', function() {
        $('.shift-card').removeClass('active');
        $(this).addClass('active');
        $('#shift_type').val($(this).data('shift'));
        
        // Auto-fill times based on shift
        const shift = $(this).data('shift');
        const times = {
            'morning': { start: '06:00', end: '12:00' },
            'afternoon': { start: '12:00', end: '18:00' },
            'evening': { start: '18:00', end: '23:59' },
            'night': { start: '00:00', end: '06:00' },
            'full_day': { start: '00:00', end: '23:59' }
        };
        
        if (times[shift]) {
            $('#start_time').val(times[shift].start);
            $('#end_time').val(times[shift].end);
        }
    });

    // Form validation and submission
    $('#assignmentForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        // Check if shift type is selected
        if (!$('#shift_type').val()) {
            Swal.fire('Warning!', 'Please select a shift type.', 'warning');
            return;
        }

        // Show loading state
        const submitBtn = $('#submitBtn');
        const spinner = submitBtn.find('.loading-spinner');
        
        submitBtn.prop('disabled', true);
        spinner.show();
        
        // Submit form via AJAX
        $.ajax({
            url: '{{ route("admin.transport.assign.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("admin.transport.assign.index") }}';
                    });
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                let message = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('\n');
                }
                Swal.fire('Error!', message, 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                spinner.hide();
            }
        });
    });

    // Real-time validation
    $('input, select, textarea').on('blur change', function() {
        if ($(this).is(':invalid')) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Time validation
    $('#start_time, #end_time').on('change', function() {
        const startTime = $('#start_time').val();
        const endTime = $('#end_time').val();
        
        if (startTime && endTime && startTime >= endTime) {
            $('#end_time')[0].setCustomValidity('End time must be after start time');
            $('#end_time').addClass('is-invalid');
        } else {
            $('#end_time')[0].setCustomValidity('');
            $('#end_time').removeClass('is-invalid');
        }
    });
});
</script>
@endsection

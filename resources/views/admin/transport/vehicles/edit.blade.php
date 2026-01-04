@extends('admin.layout.app')

@section('title', 'Edit Transport Vehicle')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #06b6d4;
        --secondary-color: #6b7280;
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #6366f1 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .breadcrumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin: 1rem 0 0 0;
    }

    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: white;
    }

    .form-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .form-section:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .form-section h5 {
        color: var(--primary-color);
        font-size: 1.25rem;
        font-weight: 600;
        border-bottom: 3px solid var(--primary-color);
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section h5 i {
        background: var(--primary-color);
        color: white;
        padding: 0.5rem;
        border-radius: 8px;
        font-size: 1rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .required-field::after {
        content: " *";
        color: var(--danger-color);
        font-weight: 700;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        background: white;
        outline: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--danger-color);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .help-text {
        font-size: 0.875rem;
        color: var(--secondary-color);
        margin-top: 0.5rem;
        font-style: italic;
    }

    .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #6366f1 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-hover) 0%, #5855eb 100%);
        color: white;
    }

    .btn-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #0891b2 100%);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--secondary-color) 0%, #4b5563 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
    }

    .quick-actions {
        position: sticky;
        top: 2rem;
    }

    .vehicle-preview {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 16px;
        border: 2px solid var(--border-color);
    }

    .vehicle-icon {
        font-size: 4rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .vehicle-info h6 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .vehicle-info p {
        color: var(--secondary-color);
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
    }

    .status-maintenance {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        color: white;
    }

    .status-repair {
        background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        color: white;
    }

    .status-inactive {
        background: linear-gradient(135deg, var(--secondary-color) 0%, #4b5563 100%);
        color: white;
    }

    .form-help {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #f59e0b;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .form-help h5 {
        color: #92400e;
        border-bottom-color: #f59e0b;
    }

    .form-help h5 i {
        background: #f59e0b;
    }

    .form-help p {
        color: #78350f;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-help strong {
        color: #451a03;
    }

    .image-preview {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .preview-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 3px solid var(--border-color);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .preview-image:hover {
        transform: scale(1.05);
        border-color: var(--primary-color);
        box-shadow: var(--shadow-lg);
    }

    .file-upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background: #fafafa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: var(--primary-color);
        background: #f0f9ff;
    }

    .file-upload-area i {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .file-upload-text {
        color: var(--secondary-color);
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .file-upload-hint {
        color: var(--secondary-color);
        font-size: 0.875rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--secondary-color);
        font-size: 0.875rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .form-section {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .quick-actions {
            position: static;
            margin-top: 2rem;
        }
    }

    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 0.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .btn:disabled .loading-spinner {
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Enhanced Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1><i class="fas fa-edit me-3"></i>Edit Transport Vehicle</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home me-1"></i>Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.transport.vehicles.index') }}"><i class="fas fa-truck me-1"></i>Transport Vehicles</a></li>
                        <li class="breadcrumb-item active"><i class="fas fa-edit me-1"></i>Edit Vehicle</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.transport.vehicles.show', $vehicle->id) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> View Details
                </a>
                <a href="{{ route('admin.transport.vehicles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $vehicle->id }}</div>
            <div class="stat-label">Vehicle ID</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $vehicle->seating_capacity ?? 'N/A' }}</div>
            <div class="stat-label">Seating Capacity</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $vehicle->year_of_manufacture ?? 'N/A' }}</div>
            <div class="stat-label">Manufacture Year</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ ucfirst($vehicle->status) }}</div>
            <div class="stat-label">Current Status</div>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.transport.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data" id="editVehicleForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Left Column - Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="form-section">
                    <h5><i class="fas fa-info-circle"></i>Basic Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="vehicle_number" class="form-label required-field">Vehicle Number</label>
                            <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                   id="vehicle_number" name="vehicle_number" 
                                   value="{{ old('vehicle_number', $vehicle->vehicle_number) }}" required>
                            @error('vehicle_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Unique identifier for the vehicle</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="registration_number" class="form-label required-field">Registration Number</label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                   id="registration_number" name="registration_number" 
                                   value="{{ old('registration_number', $vehicle->registration_number) }}" required>
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Official registration number</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="vehicle_type" class="form-label required-field">Vehicle Type</label>
                            <select class="form-select @error('vehicle_type') is-invalid @enderror" 
                                    id="vehicle_type" name="vehicle_type" required>
                                <option value="">Select Vehicle Type</option>
                                @foreach($vehicleTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('vehicle_type', $vehicle->vehicle_type) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="brand" class="form-label required-field">Brand</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                   id="brand" name="brand" 
                                   value="{{ old('brand', $vehicle->brand) }}" required>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="model" class="form-label required-field">Model</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                   id="model" name="model" 
                                   value="{{ old('model', $vehicle->model) }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="year_of_manufacture" class="form-label required-field">Year of Manufacture</label>
                            <input type="number" class="form-control @error('year_of_manufacture') is-invalid @enderror" 
                                   id="year_of_manufacture" name="year_of_manufacture" 
                                   value="{{ old('year_of_manufacture', $vehicle->year_of_manufacture) }}" 
                                   min="1900" max="{{ date('Y') + 1 }}" required>
                            @error('year_of_manufacture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="seating_capacity" class="form-label required-field">Seating Capacity</label>
                            <input type="number" class="form-control @error('seating_capacity') is-invalid @enderror" 
                                   id="seating_capacity" name="seating_capacity" 
                                   value="{{ old('seating_capacity', $vehicle->seating_capacity) }}" 
                                   min="1" max="100" required>
                            @error('seating_capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fuel & Performance -->
                <div class="form-section">
                    <h5><i class="fas fa-gas-pump"></i>Fuel & Performance</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fuel_type" class="form-label required-field">Fuel Type</label>
                            <select class="form-select @error('fuel_type') is-invalid @enderror" 
                                    id="fuel_type" name="fuel_type" required>
                                <option value="">Select Fuel Type</option>
                                @foreach($fuelTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('fuel_type', $vehicle->fuel_type) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fuel_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="fuel_efficiency" class="form-label">Fuel Efficiency (km/l)</label>
                            <input type="number" class="form-control @error('fuel_efficiency') is-invalid @enderror" 
                                   id="fuel_efficiency" name="fuel_efficiency" 
                                   value="{{ old('fuel_efficiency', $vehicle->fuel_efficiency) }}" 
                                   step="0.1" min="0">
                            @error('fuel_efficiency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Average fuel consumption</div>
                        </div>
                    </div>
                </div>

                <!-- Documents & Certificates -->
                <div class="form-section">
                    <h5><i class="fas fa-file-alt"></i>Documents & Certificates</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="insurance_number" class="form-label">Insurance Number</label>
                            <input type="text" class="form-control @error('insurance_number') is-invalid @enderror" 
                                   id="insurance_number" name="insurance_number" 
                                   value="{{ old('insurance_number', $vehicle->insurance_number) }}">
                            @error('insurance_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="insurance_expiry" class="form-label">Insurance Expiry Date</label>
                            <input type="text" class="form-control flatpickr @error('insurance_expiry') is-invalid @enderror" 
                                   id="insurance_expiry" name="insurance_expiry" 
                                   value="{{ old('insurance_expiry', $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('Y-m-d') : '') }}">
                            @error('insurance_expiry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="permit_number" class="form-label">Permit Number</label>
                            <input type="text" class="form-control @error('permit_number') is-invalid @enderror" 
                                   id="permit_number" name="permit_number" 
                                   value="{{ old('permit_number', $vehicle->permit_number) }}">
                            @error('permit_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="permit_expiry" class="form-label">Permit Expiry Date</label>
                            <input type="text" class="form-control flatpickr @error('permit_expiry') is-invalid @enderror" 
                                   id="permit_expiry" name="permit_expiry" 
                                   value="{{ old('permit_expiry', $vehicle->permit_expiry ? $vehicle->permit_expiry->format('Y-m-d') : '') }}">
                            @error('permit_expiry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="fitness_certificate_number" class="form-label">Fitness Certificate Number</label>
                            <input type="text" class="form-control @error('fitness_certificate_number') is-invalid @enderror" 
                                   id="fitness_certificate_number" name="fitness_certificate_number" 
                                   value="{{ old('fitness_certificate_number', $vehicle->fitness_certificate_number) }}">
                            @error('fitness_certificate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="fitness_expiry" class="form-label">Fitness Expiry Date</label>
                            <input type="text" class="form-control flatpickr @error('fitness_expiry') is-invalid @enderror" 
                                   id="fitness_expiry" name="fitness_expiry" 
                                   value="{{ old('fitness_expiry', $vehicle->fitness_expiry ? $vehicle->fitness_expiry->format('Y-m-d') : '') }}">
                            @error('fitness_expiry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="puc_certificate_number" class="form-label">PUC Certificate Number</label>
                            <input type="text" class="form-control @error('puc_certificate_number') is-invalid @enderror" 
                                   id="puc_certificate_number" name="puc_certificate_number" 
                                   value="{{ old('puc_certificate_number', $vehicle->puc_certificate_number) }}">
                            @error('puc_certificate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="puc_expiry" class="form-label">PUC Expiry Date</label>
                            <input type="text" class="form-control flatpickr @error('puc_expiry') is-invalid @enderror" 
                                   id="puc_expiry" name="puc_expiry" 
                                   value="{{ old('puc_expiry', $vehicle->puc_expiry ? $vehicle->puc_expiry->format('Y-m-d') : '') }}">
                            @error('puc_expiry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Staff Assignment -->
                <div class="form-section">
                    <h5><i class="fas fa-users"></i>Staff Assignment</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="driver_id" class="form-label">Driver</label>
                            <select class="form-select @error('driver_id') is-invalid @enderror" 
                                    id="driver_id" name="driver_id">
                                <option value="">Select Driver</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id', $vehicle->driver_id) == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }} ({{ $driver->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="conductor_id" class="form-label">Conductor</label>
                            <select class="form-select @error('conductor_id') is-invalid @enderror" 
                                    id="conductor_id" name="conductor_id">
                                <option value="">Select Conductor</option>
                                @foreach($conductors as $conductor)
                                    <option value="{{ $conductor->id }}" {{ old('conductor_id', $vehicle->conductor_id) == $conductor->id ? 'selected' : '' }}>
                                        {{ $conductor->name }} ({{ $conductor->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('conductor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="assigned_route_id" class="form-label">Assigned Route</label>
                            <select class="form-select @error('assigned_route_id') is-invalid @enderror" 
                                    id="assigned_route_id" name="assigned_route_id">
                                <option value="">Select Route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}" {{ old('assigned_route_id', $vehicle->assigned_route_id) == $route->id ? 'selected' : '' }}>
                                        {{ $route->route_name }} ({{ $route->route_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_route_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-section">
                    <h5><i class="fas fa-plus-circle"></i>Additional Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label required-field">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">Select Status</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $vehicle->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Enter additional details about the vehicle...">{{ old('description', $vehicle->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text">Additional details about the vehicle</div>
                        </div>
                        
                        <div class="col-12">
                            <label for="images" class="form-label">Vehicle Images</label>
                            <div class="file-upload-area" onclick="document.getElementById('images').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div class="file-upload-text">Click to upload images</div>
                                <div class="file-upload-hint">JPEG, PNG, JPG, GIF - Max 2MB each</div>
                            </div>
                            <input type="file" class="d-none" id="images" name="images[]" multiple accept="image/*">
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if($vehicle->images && count($vehicle->images) > 0)
                                <div class="mt-3">
                                    <label class="form-label">Current Images:</label>
                                    <div class="image-preview">
                                        @foreach($vehicle->images as $image)
                                            <img src="{{ $image }}" alt="Vehicle Image" class="preview-image" 
                                                 onclick="openImageModal('{{ $image }}')">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="form-section quick-actions">
                    <h5><i class="fas fa-bolt"></i>Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="updateBtn">
                            <i class="fas fa-save"></i>Update Vehicle
                            <div class="loading-spinner"></div>
                        </button>
                        <a href="{{ route('admin.transport.vehicles.show', $vehicle->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i>View Details
                        </a>
                        <button type="button" class="btn btn-warning" onclick="duplicateVehicle()">
                            <i class="fas fa-copy"></i>Duplicate Vehicle
                        </button>
                        <button type="button" class="btn btn-danger" onclick="deleteVehicle()">
                            <i class="fas fa-trash"></i>Delete Vehicle
                        </button>
                    </div>
                </div>

                <!-- Vehicle Preview -->
                <div class="form-section">
                    <h5><i class="fas fa-eye"></i>Vehicle Preview</h5>
                    <div class="vehicle-preview">
                        <div class="vehicle-icon">
                            <i class="fas fa-{{ $vehicle->vehicle_type === 'bus' ? 'bus' : ($vehicle->vehicle_type === 'car' ? 'car' : 'truck') }}"></i>
                        </div>
                        <div class="vehicle-info">
                            <h6>{{ $vehicle->vehicle_number }}</h6>
                            <p>{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                            <span class="status-badge status-{{ $vehicle->status }}">
                                {{ ucfirst($vehicle->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Form Help -->
                <div class="form-section form-help">
                    <h5><i class="fas fa-question-circle"></i>Form Help</h5>
                    <div>
                        <p><strong>Required Fields:</strong> Marked with *</p>
                        <p><strong>Vehicle Number:</strong> Must be unique</p>
                        <p><strong>Registration:</strong> Official vehicle registration</p>
                        <p><strong>Documents:</strong> Keep expiry dates updated</p>
                        <p><strong>Images:</strong> Max 2MB per image</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vehicle Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Vehicle Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function() {
    // Initialize date pickers
    $('.flatpickr').flatpickr({
        dateFormat: 'Y-m-d',
        minDate: 'today',
        theme: 'light'
    });

    // Form validation
    $('#editVehicleForm').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $('.required-field').each(function() {
            const fieldName = $(this).text().replace(' *', '');
            const field = $('#' + fieldName.toLowerCase().replace(/\s+/g, '_'));
            
            if (!field.val()) {
                field.addClass('is-invalid');
                isValid = false;
            } else {
                field.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            toastr.error('Please fill in all required fields');
            return;
        }

        // Show loading state
        $('#updateBtn').prop('disabled', true);
        $('#updateBtn .loading-spinner').show();
    });

    // Real-time validation
    $('input, select, textarea').on('blur', function() {
        if ($(this).hasClass('required-field') || $(this).attr('required')) {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        }
    });

    // File upload preview
    $('#images').on('change', function() {
        const files = this.files;
        if (files.length > 0) {
            // Show selected files count
            toastr.info(`Selected ${files.length} image(s)`);
        }
    });
});

function duplicateVehicle() {
    if (confirm('Are you sure you want to duplicate this vehicle?')) {
        $.post(`/admin/transport/vehicles/${vehicleId}/duplicate`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                setTimeout(() => {
                    window.location.href = '{{ route("admin.transport.vehicles.index") }}';
                }, 1000);
            } else {
                toastr.error(response.message);
            }
        })
        .fail(function() {
            toastr.error('Error duplicating vehicle');
        });
    }
}

function deleteVehicle() {
    if (confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) {
        $.ajax({
            url: `/admin/transport/vehicles/${vehicleId}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success('Vehicle deleted successfully');
                setTimeout(() => {
                    window.location.href = '{{ route("admin.transport.vehicles.index") }}';
                }, 1000);
            },
            error: function() {
                toastr.error('Error deleting vehicle');
            }
        });
    }
}

function openImageModal(imageSrc) {
    $('#modalImage').attr('src', imageSrc);
    $('#imageModal').modal('show');
}

// Set vehicle ID for functions
const vehicleId = {{ $vehicle->id }};
</script>
@endsection

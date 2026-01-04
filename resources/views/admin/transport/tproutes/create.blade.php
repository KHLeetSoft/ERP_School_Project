@extends('admin.layout.app')

@section('title', 'Add Transport Route')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Add Transport Route</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transport.tproutes.index') }}">Transport Routes</a></li>
                    <li class="breadcrumb-item active">Add Route</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.transport.tproutes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Routes
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Route Form -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-route mr-2"></i>
                        Route Information
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.transport.tproutes.store') }}" method="POST" id="routeForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Route Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Enter route name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Route Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}" 
                                           placeholder="e.g., R001" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_location">Start Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('start_location') is-invalid @enderror" 
                                           id="start_location" name="start_location" value="{{ old('start_location') }}" 
                                           placeholder="Enter start location" required>
                                    @error('start_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_location">End Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('end_location') is-invalid @enderror" 
                                           id="end_location" name="end_location" value="{{ old('end_location') }}" 
                                           placeholder="Enter end location" required>
                                    @error('end_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fare">Fare (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('fare') is-invalid @enderror" 
                                           id="fare" name="fare" value="{{ old('fare') }}" 
                                           placeholder="0.00" step="0.01" min="0" required>
                                    @error('fare')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select status</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Enter route description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Create Route
                            </button>
                            <a href="{{ route('admin.transport.tproutes.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Route Preview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-eye mr-2"></i>
                        Route Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div id="routePreview">
                        <div class="text-center text-muted">
                            <i class="fas fa-route fa-3x mb-3"></i>
                            <p>Fill in the form to see a preview of your route</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Quick Tips
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Use descriptive route names for easy identification
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Route codes should be unique and memorable
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Set appropriate fares based on distance and demand
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Provide clear start and end locations
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Live preview functionality
    function updatePreview() {
        const name = $('#name').val() || 'Route Name';
        const code = $('#code').val() || 'R001';
        const startLocation = $('#start_location').val() || 'Start Location';
        const endLocation = $('#end_location').val() || 'End Location';
        const fare = $('#fare').val() || '0.00';
        const status = $('#status option:selected').text() || 'Status';
        const description = $('#description').val() || 'No description provided';

        const preview = `
            <div class="route-preview">
                <div class="preview-header mb-3">
                    <h6 class="text-primary mb-1">${name}</h6>
                    <span class="badge badge-secondary">${code}</span>
                </div>
                
                <div class="preview-location mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-map-marker-alt text-success mr-2"></i>
                        <span><strong>From:</strong> ${startLocation}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                        <span><strong>To:</strong> ${endLocation}</span>
                    </div>
                </div>
                
                <div class="preview-details mb-3">
                    <div class="row">
                        <div class="col-6">
                            <div class="detail-item">
                                <i class="fas fa-money-bill text-info mr-2"></i>
                                <span><strong>Fare:</strong> ₹${fare}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item">
                                <i class="fas fa-toggle-on text-success mr-2"></i>
                                <span><strong>Status:</strong> ${status}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="preview-description">
                    <p class="mb-1"><strong>Description:</strong></p>
                    <p class="text-muted small">${description}</p>
                </div>
            </div>
        `;

        $('#routePreview').html(preview);
    }

    // Update preview on input change
    $('#name, #code, #start_location, #end_location, #fare, #status, #description').on('input change', updatePreview);

    // Form validation
    $('#routeForm').on('submit', function(e) {
        const requiredFields = ['name', 'code', 'start_location', 'end_location', 'fare', 'status'];
        let isValid = true;

        requiredFields.forEach(field => {
            const value = $(`#${field}`).val();
            if (!value) {
                $(`#${field}`).addClass('is-invalid');
                isValid = false;
            } else {
                $(`#${field}`).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            toastr.error('Please fill in all required fields');
        }
    });

    // Auto-generate route code if empty
    $('#name').on('blur', function() {
        if (!$('#code').val()) {
            const name = $(this).val();
            if (name) {
                const code = 'R' + Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                $('#code').val(code);
                updatePreview();
            }
        }
    });
});
</script>
@endsection

@section('styles')
<style>
.route-preview {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.preview-header h6 {
    margin: 0;
    font-weight: 600;
}

.preview-location .detail-item {
    margin-bottom: 8px;
}

.preview-details .detail-item {
    margin-bottom: 10px;
}

.preview-description {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0;
    padding: 20px;
}

.card-title {
    margin: 0;
    color: #2d3748;
    font-weight: 600;
}

.form-group label {
    font-weight: 500;
    color: #4a5568;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.page-header {
    background: white;
    padding: 20px 0;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>
@endsection

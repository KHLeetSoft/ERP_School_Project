@extends('superadmin.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bx bx-plus me-2"></i>Add New Payment Gateway
                    </h1>
                    <p class="text-muted mb-0">Configure a new payment gateway for your system</p>
                </div>
                <a href="{{ route('superadmin.payment.gateways.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-2"></i>Back to Gateways
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bx bx-credit-card me-2"></i>Gateway Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.payment.gateways.store') }}" method="POST" id="gatewayForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold">Gateway Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="provider" class="form-label fw-bold">Provider <span class="text-danger">*</span></label>
                                <select class="form-select @error('provider') is-invalid @enderror" 
                                        id="provider" name="provider" required>
                                    <option value="">Select Provider</option>
                                    <option value="razorpay" {{ old('provider') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                    <option value="payu" {{ old('provider') == 'payu' ? 'selected' : '' }}>PayU</option>
                                    <option value="stripe" {{ old('provider') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                    <option value="paypal" {{ old('provider') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="square" {{ old('provider') == 'square' ? 'selected' : '' }}>Square</option>
                                </select>
                                @error('provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mode" class="form-label fw-bold">Mode <span class="text-danger">*</span></label>
                                <select class="form-select @error('mode') is-invalid @enderror" 
                                        id="mode" name="mode" required>
                                    <option value="">Select Mode</option>
                                    <option value="sandbox" {{ old('mode') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                    <option value="live" {{ old('mode') == 'live' ? 'selected' : '' }}>Live (Production)</option>
                                </select>
                                @error('mode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="currency" class="form-label fw-bold">Currency <span class="text-danger">*</span></label>
                                <select class="form-select @error('currency') is-invalid @enderror" 
                                        id="currency" name="currency" required>
                                    <option value="">Select Currency</option>
                                    <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR (Indian Rupee)</option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                    <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (British Pound)</option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="api_key" class="form-label fw-bold">API Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" 
                                       id="api_key" name="api_key" value="{{ old('api_key') }}" required>
                                @error('api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Enter your gateway API key</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="api_secret" class="form-label fw-bold">API Secret <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('api_secret') is-invalid @enderror" 
                                       id="api_secret" name="api_secret" required>
                                @error('api_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Enter your gateway API secret</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="webhook_url" class="form-label fw-bold">Webhook URL</label>
                                <input type="url" class="form-control @error('webhook_url') is-invalid @enderror" 
                                       id="webhook_url" name="webhook_url" value="{{ old('webhook_url') }}">
                                @error('webhook_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">URL for receiving payment notifications</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="commission_rate" class="form-label fw-bold">Commission Rate (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" max="100" 
                                       class="form-control @error('commission_rate') is-invalid @enderror" 
                                       id="commission_rate" name="commission_rate" 
                                       value="{{ old('commission_rate', 0) }}" required>
                                @error('commission_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Commission percentage (0-100)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">
                                        Active Gateway
                                    </label>
                                    <small class="form-text text-muted d-block">Enable this gateway for payments</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Assign to Schools</label>
                                <div class="row">
                                    @foreach($schools as $school)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="school_{{ $school->id }}" name="schools[]" 
                                                       value="{{ $school->id }}"
                                                       {{ in_array($school->id, old('schools', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="school_{{ $school->id }}">
                                                    {{ $school->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('schools')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('superadmin.payment.gateways.index') }}" class="btn btn-secondary">
                                <i class="bx bx-x me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-2"></i>Create Gateway
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bx bx-info-circle me-2"></i>Gateway Information
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Supported Providers:</h6>
                    <ul class="list-unstyled">
                        <li><i class="bx bx-check text-success me-2"></i>Razorpay</li>
                        <li><i class="bx bx-check text-success me-2"></i>PayU</li>
                        <li><i class="bx bx-check text-success me-2"></i>Stripe</li>
                        <li><i class="bx bx-check text-success me-2"></i>PayPal</li>
                        <li><i class="bx bx-check text-success me-2"></i>Square</li>
                    </ul>
                    
                    <hr>
                    
                    <h6 class="fw-bold">Security Notes:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="bx bx-lock text-warning me-2"></i>API keys are encrypted</li>
                        <li><i class="bx bx-shield text-warning me-2"></i>Use sandbox mode for testing</li>
                        <li><i class="bx bx-key text-warning me-2"></i>Keep credentials secure</li>
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
    // Form validation
    $('#gatewayForm').on('submit', function(e) {
        var isValid = true;
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Validate required fields
        $('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">This field is required.</div>');
                isValid = false;
            }
        });
        
        // Validate commission rate
        var commissionRate = parseFloat($('#commission_rate').val());
        if (commissionRate < 0 || commissionRate > 100) {
            $('#commission_rate').addClass('is-invalid');
            $('#commission_rate').after('<div class="invalid-feedback">Commission rate must be between 0 and 100.</div>');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showAlert('Please fix the errors below', 'error');
        }
    });
    
    // Provider change event
    $('#provider').on('change', function() {
        var provider = $(this).val();
        var currencySelect = $('#currency');
        
        // Set default currency based on provider
        if (provider === 'razorpay' || provider === 'payu') {
            currencySelect.val('INR');
        } else if (provider === 'stripe' || provider === 'square') {
            currencySelect.val('USD');
        }
    });
});

function showAlert(message, type) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>';
    
    $('.container-fluid').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection

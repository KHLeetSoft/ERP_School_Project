@extends('superadmin.app')

@section('title', 'Payment Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-credit-card fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">Payment Settings</h1>
                            <p class="text-muted mb-0">Payment gateways, subscription plans, and billing configuration</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-end">
                        <a href="{{ route('superadmin.settings.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-2"></i>Back to Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-credit-card me-2 text-primary"></i>Payment Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.payment.update') }}" method="POST">
                                @csrf
                                
                                <!-- Payment Gateway -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-cog me-2"></i>Payment Gateway
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="default_payment_gateway" class="form-label fw-bold">Default Gateway</label>
                                        <select class="form-select @error('default_payment_gateway') is-invalid @enderror" 
                                                id="default_payment_gateway" name="default_payment_gateway" required>
                                            <option value="stripe" {{ ($settings['default_payment_gateway'] ?? 'stripe') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                            <option value="razorpay" {{ ($settings['default_payment_gateway'] ?? '') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                            <option value="paypal" {{ ($settings['default_payment_gateway'] ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                        </select>
                                        @error('default_payment_gateway')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="currency_code" class="form-label fw-bold">Currency Code</label>
                                        <select class="form-select @error('currency_code') is-invalid @enderror" 
                                                id="currency_code" name="currency_code" required>
                                            <option value="USD" {{ ($settings['currency_code'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ ($settings['currency_code'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            <option value="GBP" {{ ($settings['currency_code'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                            <option value="INR" {{ ($settings['currency_code'] ?? '') == 'INR' ? 'selected' : '' }}>INR</option>
                                        </select>
                                        @error('currency_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Stripe Configuration -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-credit-card me-2"></i>Stripe Configuration
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="stripe_webhook_secret" class="form-label fw-bold">Webhook Secret</label>
                                        <input type="password" class="form-control @error('stripe_webhook_secret') is-invalid @enderror" 
                                               id="stripe_webhook_secret" name="stripe_webhook_secret" 
                                               value="{{ $settings['stripe_webhook_secret'] ?? '' }}">
                                        @error('stripe_webhook_secret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- PayPal Configuration -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-credit-card me-2"></i>PayPal Configuration
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paypal_client_id" class="form-label fw-bold">Client ID</label>
                                        <input type="text" class="form-control @error('paypal_client_id') is-invalid @enderror" 
                                               id="paypal_client_id" name="paypal_client_id" 
                                               value="{{ $settings['paypal_client_id'] ?? '' }}">
                                        @error('paypal_client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paypal_client_secret" class="form-label fw-bold">Client Secret</label>
                                        <input type="password" class="form-control @error('paypal_client_secret') is-invalid @enderror" 
                                               id="paypal_client_secret" name="paypal_client_secret" 
                                               value="{{ $settings['paypal_client_secret'] ?? '' }}">
                                        @error('paypal_client_secret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tax Settings -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-calculator me-2"></i>Tax Settings
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tax_rate" class="form-label fw-bold">Tax Rate (%)</label>
                                        <input type="number" class="form-control @error('tax_rate') is-invalid @enderror" 
                                               id="tax_rate" name="tax_rate" 
                                               value="{{ $settings['tax_rate'] ?? '0' }}" 
                                               min="0" max="100" step="0.01">
                                        @error('tax_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bx bx-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-check-circle me-2 text-success"></i>Payment Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-status">
                                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                                    <span>Default Gateway</span>
                                    <span class="badge badge-primary">{{ $settings['default_payment_gateway'] ?? 'stripe' }}</span>
                                </div>
                                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                                    <span>Currency</span>
                                    <span class="badge badge-info">{{ $settings['currency_code'] ?? 'USD' }}</span>
                                </div>
                                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                                    <span>Tax Rate</span>
                                    <span class="badge badge-warning">{{ $settings['tax_rate'] ?? '0' }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.settings-icon {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
}

.form-label {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e1e5e9;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.status-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.status-item:last-child {
    border-bottom: none;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}
</style>
@endsection

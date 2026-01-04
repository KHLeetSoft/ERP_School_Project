@extends('admin.layout.app')

@section('title', 'Add Payment Gateway')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Payment Gateway</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment.gateways.store') }}" method="POST" id="gateway-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gateway_name" class="form-label">Gateway Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gateway_name') is-invalid @enderror" id="gateway_name" name="gateway_name" required>
                                        <option value="">Select Gateway Type</option>
                                        @foreach($gatewayTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('gateway_name') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('gateway_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" name="display_name" value="{{ old('display_name') }}" required>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_test_mode" name="is_test_mode" {{ old('is_test_mode') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_test_mode">
                                            Test Mode
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">API Credentials <span class="text-danger">*</span></label>
                            <div id="api-credentials">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="api_credentials[api_key]" placeholder="API Key" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="api_credentials[api_secret]" placeholder="API Secret" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="api_credentials[merchant_id]" placeholder="Merchant ID">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control mb-2" name="api_credentials[webhook_secret]" placeholder="Webhook Secret">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supported Payment Methods <span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach($paymentMethods as $key => $value)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="supported_payment_methods[]" value="{{ $key }}" id="method_{{ $key }}">
                                            <label class="form-check-label" for="method_{{ $key }}">
                                                {{ $value }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="transaction_fee_percentage" class="form-label">Transaction Fee (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" max="100" class="form-control @error('transaction_fee_percentage') is-invalid @enderror" id="transaction_fee_percentage" name="transaction_fee_percentage" value="{{ old('transaction_fee_percentage', 0) }}" required>
                                    @error('transaction_fee_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="minimum_amount" class="form-label">Minimum Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('minimum_amount') is-invalid @enderror" id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount', 0) }}" required>
                                    @error('minimum_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="maximum_amount" class="form-label">Maximum Amount (₹)</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('maximum_amount') is-invalid @enderror" id="maximum_amount" name="maximum_amount" value="{{ old('maximum_amount') }}">
                                    @error('maximum_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="webhook_url" class="form-label">Webhook URL</label>
                                    <input type="url" class="form-control @error('webhook_url') is-invalid @enderror" id="webhook_url" name="webhook_url" value="{{ old('webhook_url') }}">
                                    @error('webhook_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="return_url" class="form-label">Return URL</label>
                                    <input type="url" class="form-control @error('return_url') is-invalid @enderror" id="return_url" name="return_url" value="{{ old('return_url') }}">
                                    @error('return_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cancel_url" class="form-label">Cancel URL</label>
                                    <input type="url" class="form-control @error('cancel_url') is-invalid @enderror" id="cancel_url" name="cancel_url" value="{{ old('cancel_url') }}">
                                    @error('cancel_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.payment.gateways.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Gateway</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Gateway-specific API credential fields
    $('#gateway_name').change(function() {
        var gateway = $(this).val();
        var credentialsHtml = '';
        
        switch(gateway) {
            case 'razorpay':
                credentialsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="api_credentials[key_id]" placeholder="Key ID" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="api_credentials[key_secret]" placeholder="Key Secret" required>
                        </div>
                    </div>
                `;
                break;
            case 'paytm':
                credentialsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="api_credentials[merchant_id]" placeholder="Merchant ID" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="api_credentials[merchant_key]" placeholder="Merchant Key" required>
                        </div>
                    </div>
                `;
                break;
            case 'stripe':
                credentialsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="api_credentials[publishable_key]" placeholder="Publishable Key" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="api_credentials[secret_key]" placeholder="Secret Key" required>
                        </div>
                    </div>
                `;
                break;
            default:
                credentialsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="api_credentials[api_key]" placeholder="API Key" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="api_credentials[api_secret]" placeholder="API Secret" required>
                        </div>
                    </div>
                `;
        }
        
        $('#api-credentials').html(credentialsHtml);
    });
});
</script>
@endsection

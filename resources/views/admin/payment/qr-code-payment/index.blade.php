@extends('admin.layout.app')

@section('title', 'QR Code Payment')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payment.school-qr-codes.index') }}">QR Codes</a></li>
                        <li class="breadcrumb-item active">Payment</li>
                    </ol>
                </div>
                <h4 class="page-title">QR Code Payment</h4>
            </div>
        </div>
    </div>

    <!-- Current Status Alert -->
    @if($school->needsPaymentForQrCodes())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-alert-circle me-2"></i>
                    <strong>Payment Required!</strong> You have reached the free QR code limit (3 codes). 
                    Please make a payment to continue generating more QR codes.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Current Status Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Current QR Code Status</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary">{{ $school->qr_code_limit }}</h3>
                                <p class="text-muted mb-0">Current Limit</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success">{{ $school->qr_codes_generated }}</h3>
                                <p class="text-muted mb-0">Generated</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info">{{ $school->getRemainingQrCodes() }}</h3>
                                <p class="text-muted mb-0">Remaining</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                @if($school->needsPaymentForQrCodes())
                                    <span class="badge bg-warning fs-6">Payment Required</span>
                                @elseif($school->qr_limit_paid)
                                    <span class="badge bg-success fs-6">Paid</span>
                                @else
                                    <span class="badge bg-info fs-6">Free</span>
                                @endif
                                <p class="text-muted mb-0 mt-1">Status</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-credit-card font-size-48 text-primary mb-3"></i>
                    <h5>Purchase QR Codes</h5>
                    <p class="text-muted">Buy additional QR codes to increase your limit.</p>
                    <a href="{{ route('admin.payment.qr-code-payment.history') }}" class="btn btn-outline-primary">
                        View Payment History
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Purchase QR Codes</h4>
                </div>
                <div class="card-body">
                    <form id="paymentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="qr_codes_count" class="form-label">Number of QR Codes <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="qr_codes_count" name="qr_codes_count" 
                                           min="1" max="1000" value="10" required>
                                    <div class="form-text">Minimum 1, Maximum 1000 QR codes</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="razorpay">Razorpay</option>
                                        <option value="stripe">Stripe</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="upi">UPI</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cash">Cash (Demo)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mb-3">
                            <button type="button" class="btn btn-outline-primary" id="calculateBtn">
                                <i class="mdi mdi-calculator me-1"></i> Calculate Price
                            </button>
                        </div>

                        <!-- Pricing Display -->
                        <div id="pricingDisplay" class="d-none">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">Pricing Details</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>QR Codes:</strong> <span id="pricing-qr-codes"></span></p>
                                        <p class="mb-1"><strong>Price per QR Code:</strong> ₹<span id="pricing-price-per"></span></p>
                                        <p class="mb-1"><strong>Discount:</strong> <span id="pricing-discount"></span>%</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Discount Amount:</strong> ₹<span id="pricing-discount-amount"></span></p>
                                        <p class="mb-1"><strong>Total Price:</strong> ₹<span id="pricing-total"></span></p>
                                        <p class="mb-0"><strong>Tier:</strong> <span id="pricing-tier"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="payBtn" disabled>
                                <i class="mdi mdi-credit-card me-1"></i> Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pricing Tiers -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="header-title">Pricing Tiers</h5>
                </div>
                <div class="card-body">
                    @foreach($pricingTiers as $tier)
                        <div class="pricing-tier mb-3 p-3 border rounded">
                            <h6 class="text-primary">{{ $tier->name }}</h6>
                            <p class="text-muted small mb-2">{{ $tier->description }}</p>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Range:</small><br>
                                    <strong>{{ $tier->min_qr_codes }}{{ $tier->max_qr_codes ? '-' . $tier->max_qr_codes : '+' }} QR codes</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Price:</small><br>
                                    <strong>₹{{ $tier->price_per_qr_code }}/QR</strong>
                                </div>
                            </div>
                            @if($tier->discount_percentage > 0)
                                <div class="mt-2">
                                    <span class="badge bg-success">{{ $tier->discount_percentage }}% Discount</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    @if($recentPayments->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Recent Payments</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>QR Codes</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_id }}</td>
                                            <td>{{ $payment->qr_codes_purchased }}</td>
                                            <td>₹{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                            <td>
                                                @if($payment->status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Calculate pricing
    $('#calculateBtn').click(function() {
        var qrCodesCount = $('#qr_codes_count').val();
        
        if (!qrCodesCount || qrCodesCount < 1) {
            toastr.error('Please enter a valid number of QR codes.');
            return;
        }

        $.ajax({
            url: "{{ route('admin.payment.qr-code-payment.calculate') }}",
            type: 'POST',
            data: {
                qr_codes_count: qrCodesCount,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#pricing-qr-codes').text(response.pricing.qr_codes_count);
                    $('#pricing-price-per').text(response.pricing.price_per_qr_code);
                    $('#pricing-discount').text(response.pricing.discount_percentage);
                    $('#pricing-discount-amount').text(response.pricing.discount_amount.toFixed(2));
                    $('#pricing-total').text(response.pricing.total_price.toFixed(2));
                    $('#pricing-tier').text(response.pricing.tier_name);
                    
                    $('#pricingDisplay').removeClass('d-none');
                    $('#payBtn').prop('disabled', false);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                }
            }
        });
    });

    // Submit payment form
    $('#paymentForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('admin.payment.qr-code-payment.create') }}",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        toastr.success(response.message);
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else if (xhr.responseJSON.error) {
                    toastr.error(xhr.responseJSON.error);
                }
            }
        });
    });
});
</script>
@endsection

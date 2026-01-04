@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bx bx-package me-2"></i>Select Payment Plan
                    </h1>
                    <p class="text-muted mb-0">Choose the best plan for your school</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-4">
            <select class="form-select" id="priceTypeFilter">
                <option value="">All Price Types</option>
                <option value="fixed">Fixed Price</option>
                <option value="recurring">Recurring</option>
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="gatewayFilter">
                <option value="">All Gateways</option>
                @foreach($gateways as $gateway)
                    <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-outline-secondary" onclick="filterPlans()">
                <i class="bx bx-filter me-2"></i>Filter Plans
            </button>
        </div>
    </div>

    <!-- Plans Grid -->
    <div class="row" id="plansContainer">
        @foreach($plans as $plan)
        <div class="col-lg-4 col-md-6 mb-4 plan-card" 
             data-price-type="{{ $plan->price_type }}" 
             data-gateway-id="{{ $plan->gateway_id }}">
            <div class="card shadow h-100 plan-card-inner">
                <div class="card-header text-center bg-primary text-white">
                    <h4 class="mb-0">{{ $plan->name }}</h4>
                    <p class="mb-0 text-white-50">{{ $plan->description }}</p>
                </div>
                <div class="card-body text-center">
                    <div class="pricing mb-4">
                        @if($plan->price_type === 'fixed')
                            <h2 class="text-primary">₹{{ number_format($plan->price) }}</h2>
                            <p class="text-muted">One-time payment</p>
                        @else
                            <h2 class="text-primary">₹{{ number_format($plan->price) }}</h2>
                            <p class="text-muted">Per {{ $plan->billing_cycle }}</p>
                        @endif
                    </div>
                    
                    <div class="features mb-4">
                        <h6 class="fw-bold mb-3">Features:</h6>
                        <ul class="list-unstyled">
                            @foreach($plan->features as $feature)
                                <li class="mb-2">
                                    <i class="bx bx-check text-success me-2"></i>{{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="gateway-info mb-4">
                        <small class="text-muted">
                            <i class="bx bx-credit-card me-1"></i>
                            {{ $plan->gateway->name }} ({{ $plan->gateway->provider }})
                        </small>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.payment.plan-selection.show', $plan->id) }}" 
                       class="btn btn-primary w-100">
                        <i class="bx bx-eye me-2"></i>View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Plans Message -->
    <div class="row d-none" id="noPlansMessage">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="bx bx-package display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No plans available</h4>
                    <p class="text-muted">Please contact administrator to set up payment plans.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Filter plans
    $('#priceTypeFilter, #gatewayFilter').on('change', function() {
        filterPlans();
    });
});

function filterPlans() {
    var priceType = $('#priceTypeFilter').val();
    var gatewayId = $('#gatewayFilter').val();
    
    $('.plan-card').each(function() {
        var card = $(this);
        var cardPriceType = card.data('price-type');
        var cardGatewayId = card.data('gateway-id');
        
        var showCard = true;
        
        if (priceType && cardPriceType !== priceType) {
            showCard = false;
        }
        
        if (gatewayId && cardGatewayId != gatewayId) {
            showCard = false;
        }
        
        if (showCard) {
            card.show();
        } else {
            card.hide();
        }
    });
    
    // Check if any plans are visible
    var visiblePlans = $('.plan-card:visible').length;
    if (visiblePlans === 0) {
        $('#noPlansMessage').removeClass('d-none');
    } else {
        $('#noPlansMessage').addClass('d-none');
    }
}
</script>

<style>
.plan-card-inner {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.plan-card-inner:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.pricing h2 {
    font-size: 2.5rem;
    font-weight: bold;
}

.features ul li {
    text-align: left;
}
</style>
@endsection

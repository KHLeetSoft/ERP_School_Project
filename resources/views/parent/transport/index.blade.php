@extends('parent.layout.app')

@section('title', 'Transport Information')

@section('content')
<div class="page-header">
    <h1 class="page-title">Transport Information</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Transport</li>
        </ol>
    </nav>
</div>

<div class="row">
    @forelse($children as $child)
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $child->first_name }} {{ $child->last_name }}</h5>
                            <p class="text-muted mb-1">{{ $child->schoolClass->name ?? 'N/A' }} - {{ $child->section->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Transport Details -->
                    <div class="mb-3">
                        <h6 class="mb-2">Transport Details</h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Route</small>
                                <div class="fw-bold">Route A - Downtown</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Bus Number</small>
                                <div class="fw-bold">BUS-001</div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <small class="text-muted">Driver</small>
                                <div class="fw-bold">John Smith</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Contact</small>
                                <div class="fw-bold">+91 98765 43210</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pickup & Drop Times -->
                    <div class="mb-3">
                        <h6 class="mb-2">Schedule</h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Pickup Time</small>
                                <div class="fw-bold text-success">7:30 AM</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Drop Time</small>
                                <div class="fw-bold text-info">3:45 PM</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Route Stops -->
                    <div class="mb-3">
                        <h6 class="mb-2">Route Stops</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-1">
                                <small class="text-success">✓ Main Gate (7:30 AM)</small>
                            </div>
                            <div class="list-group-item px-0 py-1">
                                <small class="text-info">→ Central Park (7:45 AM)</small>
                            </div>
                            <div class="list-group-item px-0 py-1">
                                <small class="text-info">→ Downtown Mall (8:00 AM)</small>
                            </div>
                            <div class="list-group-item px-0 py-1">
                                <small class="text-success">✓ School (8:15 AM)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('parent.transport.tracking') }}" class="btn btn-primary">
                            <i class="fas fa-map-marker-alt me-2"></i>Live Tracking
                        </a>
                        <button class="btn btn-outline-info" onclick="contactDriver()">
                            <i class="fas fa-phone me-2"></i>Contact Driver
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-bus fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">No Children Found</h4>
                    <p class="text-muted">You don't have any children registered in the system yet.</p>
                    <a href="{{ route('parent.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Transport Overview -->
@if($children->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-route me-2"></i>Transport Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                                <i class="fas fa-bus"></i>
                            </div>
                            <div class="stats-number">3</div>
                            <div class="stats-label">Active Routes</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stats-number">95%</div>
                            <div class="stats-label">On-Time Performance</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stats-number">45</div>
                            <div class="stats-label">Total Students</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stats-number">2</div>
                            <div class="stats-label">Delays Today</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Contact Driver Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Contact Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <h5>John Smith</h5>
                    <p class="text-muted">Bus Driver - Route A</p>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <a href="tel:+919876543210" class="btn btn-success w-100">
                            <i class="fas fa-phone me-2"></i>Call Driver
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="sms:+919876543210" class="btn btn-info w-100">
                            <i class="fas fa-sms me-2"></i>Send SMS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function contactDriver() {
        new bootstrap.Modal(document.getElementById('contactModal')).show();
    }
</script>
@endpush

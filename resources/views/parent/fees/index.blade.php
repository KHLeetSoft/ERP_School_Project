@extends('parent.layout.app')

@section('title', 'Fee Management')

@section('content')
<div class="page-header">
    <h1 class="page-title">Fee Management</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Fee Management</li>
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
                    
                    <!-- Fee Status -->
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-success mb-1">₹15,000</div>
                                <small class="text-muted">Paid</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-warning mb-1">₹5,000</div>
                                <small class="text-muted">Due</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h4 text-info mb-1">₹20,000</div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    
                    <!-- Fee Breakdown -->
                    <div class="mb-3">
                        <h6 class="mb-2">Fee Breakdown</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between">
                                    <span>Tuition Fee</span>
                                    <span class="text-success">₹12,000</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between">
                                    <span>Transport Fee</span>
                                    <span class="text-success">₹2,000</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between">
                                    <span>Library Fee</span>
                                    <span class="text-success">₹1,000</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between">
                                    <span>Exam Fee</span>
                                    <span class="text-warning">₹5,000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('parent.fees.child', $child) }}" class="btn btn-primary">
                            <i class="fas fa-credit-card me-2"></i>View Fee Details
                        </a>
                        <button class="btn btn-outline-success" onclick="makePayment({{ $child->id }})">
                            <i class="fas fa-payment me-2"></i>Pay Online
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-credit-card fa-4x text-muted mb-4"></i>
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

<!-- Payment Summary -->
@if($children->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie me-2"></i>Payment Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stats-number">₹45,000</div>
                            <div class="stats-label">Total Paid</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stats-number">₹15,000</div>
                            <div class="stats-label">Pending Payment</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="stats-number">75%</div>
                            <div class="stats-label">Payment Complete</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stats-number">2</div>
                            <div class="stats-label">Overdue Payments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₹)</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select class="form-control" id="paymentMethod" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="net_banking">Net Banking</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processPayment()">Process Payment</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function makePayment(childId) {
        // Set child ID for payment
        document.getElementById('paymentForm').setAttribute('data-child-id', childId);
        
        // Show payment modal
        new bootstrap.Modal(document.getElementById('paymentModal')).show();
    }
    
    function processPayment() {
        const form = document.getElementById('paymentForm');
        const childId = form.getAttribute('data-child-id');
        const amount = document.getElementById('amount').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        
        if (!amount || !paymentMethod) {
            alert('Please fill in all required fields.');
            return;
        }
        
        // Process payment via AJAX
        fetch('{{ route("parent.fees.payment") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                child_id: childId,
                amount: amount,
                payment_method: paymentMethod,
                notes: document.getElementById('notes').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment processed successfully!');
                location.reload();
            } else {
                alert('Payment failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Payment failed. Please try again.');
        });
    }
</script>
@endpush

@extends('student.layout.app')

@section('title', 'Transport Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-user-cog me-2"></i>Transport Profile
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.transport.index') }}">Transport</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Transport Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.transport.profile.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Route Name</label>
                                    <input type="text" class="form-control" value="{{ $transportProfile['route_name'] ?? 'Not Assigned' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pickup Point <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pickup_point') is-invalid @enderror" 
                                           name="pickup_point" value="{{ old('pickup_point', $transportProfile['pickup_point'] ?? '') }}" required>
                                    @error('pickup_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Drop Point <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('drop_point') is-invalid @enderror" 
                                           name="drop_point" value="{{ old('drop_point', $transportProfile['drop_point'] ?? '') }}" required>
                                    @error('drop_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Monthly Fee</label>
                                    <input type="text" class="form-control" value="${{ number_format($transportProfile['monthly_fee'] ?? 0, 2) }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Emergency Contact <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                           name="emergency_contact" value="{{ old('emergency_contact', $transportProfile['emergency_contact'] ?? '') }}" required>
                                    @error('emergency_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <input type="text" class="form-control" value="{{ $transportProfile['status'] ?? 'Unknown' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Transport Notes</label>
                                    <textarea class="form-control @error('transport_notes') is-invalid @enderror" 
                                              name="transport_notes" rows="3" 
                                              placeholder="Any special instructions or notes for transport...">{{ old('transport_notes', $transportProfile['transport_notes'] ?? '') }}</textarea>
                                    @error('transport_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2"></i>Payment History
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($paymentHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment Date</th>
                                        <th>Method</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentHistory as $payment)
                                    <tr>
                                        <td class="fw-bold">{{ $payment['month'] }}</td>
                                        <td class="fw-bold text-success">${{ number_format($payment['amount'], 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment['status'] === 'Paid' ? 'success' : 'warning' }}">
                                                {{ $payment['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment['payment_date'])
                                                {{ \Carbon\Carbon::parse($payment['payment_date'])->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment['method'] }}</td>
                                        <td>
                                            @if($payment['status'] === 'Paid')
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewReceipt('{{ $payment['month'] }}')">
                                                    <i class="fas fa-receipt"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline-warning" onclick="makePayment('{{ $payment['month'] }}')">
                                                    <i class="fas fa-credit-card"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No payment history found</h5>
                            <p class="text-muted">Your payment history will appear here once you make payments.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Enrollment Date:</label>
                        <p class="mb-0">{{ $transportProfile['enrollment_date'] ? \Carbon\Carbon::parse($transportProfile['enrollment_date'])->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Method:</label>
                        <p class="mb-0">{{ $transportProfile['payment_method'] ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Account Status:</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $transportProfile['status'] === 'Active' ? 'success' : 'warning' }}">
                                {{ $transportProfile['status'] ?? 'Unknown' }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Last Updated:</label>
                        <p class="mb-0">{{ now()->format('M d, Y H:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.transport.routes') }}" class="btn btn-outline-primary">
                            <i class="fas fa-route me-2"></i>View Available Routes
                        </a>
                        <a href="{{ route('student.transport.schedule') }}" class="btn btn-outline-success">
                            <i class="fas fa-calendar-alt me-2"></i>View Schedule
                        </a>
                        <a href="{{ route('student.transport.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>View Trip History
                        </a>
                        <button class="btn btn-outline-warning" onclick="contactSupport()">
                            <i class="fas fa-headset me-2"></i>Contact Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-2"></i>Important Information
                </h6>
                <ul class="mb-0">
                    <li>Please keep your pickup and drop points updated for accurate service</li>
                    <li>Emergency contact should be a reliable number that can be reached during transport hours</li>
                    <li>Transport notes help drivers provide better service (e.g., special needs, allergies)</li>
                    <li>Changes to your profile may take 24-48 hours to take effect</li>
                    <li>Contact the transport office for any urgent changes or issues</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <div class="mb-3">
                        <label class="form-label">Month</label>
                        <input type="text" id="paymentMonth" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" id="paymentAmount" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="paymentMethod">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number (if applicable)</label>
                        <input type="text" class="form-control" placeholder="Enter reference number">
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

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h4>Transport Payment Receipt</h4>
                    <p class="text-muted">Payment Confirmation</p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Month:</strong> <span id="receiptMonth">-</span></p>
                        <p><strong>Amount:</strong> <span id="receiptAmount">-</span></p>
                        <p><strong>Payment Date:</strong> <span id="receiptDate">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Method:</strong> <span id="receiptMethod">-</span></p>
                        <p><strong>Reference:</strong> <span id="receiptReference">-</span></p>
                        <p><strong>Status:</strong> <span class="badge bg-success">Paid</span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
        document.querySelector('form').reset();
    }
}

function makePayment(month) {
    document.getElementById('paymentMonth').value = month;
    document.getElementById('paymentAmount').value = '$150.00';
    
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

function processPayment() {
    // Here you would typically integrate with a payment gateway
    alert('Payment processing would be implemented here. This would integrate with a payment gateway like Stripe or PayPal.');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
    modal.hide();
}

function viewReceipt(month) {
    // Mock receipt data
    document.getElementById('receiptMonth').textContent = month;
    document.getElementById('receiptAmount').textContent = '$150.00';
    document.getElementById('receiptDate').textContent = 'March 15, 2024';
    document.getElementById('receiptMethod').textContent = 'Bank Transfer';
    document.getElementById('receiptReference').textContent = 'TXN123456789';
    
    const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
    modal.show();
}

function printReceipt() {
    const printContent = document.querySelector('#receiptModal .modal-body').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Payment Receipt</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .text-center { text-align: center; }
                    .mb-4 { margin-bottom: 20px; }
                    .row { display: flex; }
                    .col-md-6 { flex: 1; padding: 0 10px; }
                    p { margin: 10px 0; }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function contactSupport() {
    alert('Contact support functionality would be implemented here. This could open a support ticket system or provide contact information.');
}
</script>
@endsection

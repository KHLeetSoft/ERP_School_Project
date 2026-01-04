@extends('student.layout.app')

@section('title', 'Hostel Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-user-cog me-2"></i>Hostel Profile
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.hostel.index') }}">Hostel</a></li>
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
                        <i class="fas fa-user me-2"></i>Hostel Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.hostel.profile.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hostel Name</label>
                                    <input type="text" class="form-control" value="{{ $hostelProfile['hostel_name'] ?? 'Not Assigned' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Room Number</label>
                                    <input type="text" class="form-control" value="{{ $hostelProfile['room_number'] ?? 'Not Assigned' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Check-in Date</label>
                                    <input type="text" class="form-control" value="{{ $hostelProfile['check_in_date'] ? \Carbon\Carbon::parse($hostelProfile['check_in_date'])->format('M d, Y') : 'N/A' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Expected Check-out</label>
                                    <input type="text" class="form-control" value="{{ $hostelProfile['expected_check_out'] ? \Carbon\Carbon::parse($hostelProfile['expected_check_out'])->format('M d, Y') : 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Monthly Fee</label>
                                    <input type="text" class="form-control" value="${{ number_format($hostelProfile['monthly_fee'] ?? 0, 2) }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Emergency Contact <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                           name="emergency_contact" value="{{ old('emergency_contact', $hostelProfile['emergency_contact'] ?? '') }}" required>
                                    @error('emergency_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <input type="text" class="form-control" value="{{ $hostelProfile['status'] ?? 'Unknown' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Medical Conditions</label>
                                    <textarea class="form-control @error('medical_conditions') is-invalid @enderror" 
                                              name="medical_conditions" rows="3" 
                                              placeholder="Any medical conditions or allergies...">{{ old('medical_conditions', $hostelProfile['medical_conditions'] ?? '') }}</textarea>
                                    @error('medical_conditions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Dietary Restrictions</label>
                                    <textarea class="form-control @error('dietary_restrictions') is-invalid @enderror" 
                                              name="dietary_restrictions" rows="3" 
                                              placeholder="Any dietary restrictions or preferences...">{{ old('dietary_restrictions', $hostelProfile['dietary_restrictions'] ?? '') }}</textarea>
                                    @error('dietary_restrictions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Hostel Notes</label>
                                    <textarea class="form-control @error('hostel_notes') is-invalid @enderror" 
                                              name="hostel_notes" rows="3" 
                                              placeholder="Any special notes or requirements...">{{ old('hostel_notes', $hostelProfile['hostel_notes'] ?? '') }}</textarea>
                                    @error('hostel_notes')
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

    <!-- Visitor Log -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Visitor Log
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($visitorLog) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Visitor Name</th>
                                        <th>Relation</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visitorLog as $visitor)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($visitor['date'])->format('M d, Y') }}</td>
                                        <td class="fw-bold">{{ $visitor['visitor_name'] }}</td>
                                        <td>{{ $visitor['relation'] }}</td>
                                        <td>{{ $visitor['check_in'] }}</td>
                                        <td>{{ $visitor['check_out'] }}</td>
                                        <td>{{ $visitor['purpose'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $visitor['status'] === 'Completed' ? 'success' : 'warning' }}">
                                                {{ $visitor['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No visitor records found</h5>
                            <p class="text-muted">Visitor records will appear here once visitors are registered.</p>
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
                        <label class="form-label fw-bold">Hostel:</label>
                        <p class="mb-0">{{ $hostelProfile['hostel_name'] ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Room:</label>
                        <p class="mb-0">{{ $hostelProfile['room_number'] ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Enrollment Date:</label>
                        <p class="mb-0">{{ $hostelProfile['check_in_date'] ? \Carbon\Carbon::parse($hostelProfile['check_in_date'])->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Expected Duration:</label>
                        <p class="mb-0">
                            @if($hostelProfile['check_in_date'] && $hostelProfile['expected_check_out'])
                                {{ \Carbon\Carbon::parse($hostelProfile['check_in_date'])->diffInDays(\Carbon\Carbon::parse($hostelProfile['expected_check_out'])) }} days
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Account Status:</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $hostelProfile['status'] === 'Active' ? 'success' : 'warning' }}">
                                {{ $hostelProfile['status'] ?? 'Unknown' }}
                            </span>
                        </p>
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
                        <a href="{{ route('student.hostel.rooms') }}" class="btn btn-outline-primary">
                            <i class="fas fa-bed me-2"></i>View Room Details
                        </a>
                        <a href="{{ route('student.hostel.meals') }}" class="btn btn-outline-success">
                            <i class="fas fa-utensils me-2"></i>View Meal Plan
                        </a>
                        <a href="{{ route('student.hostel.complaints') }}" class="btn btn-outline-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Submit Complaint
                        </a>
                        <button class="btn btn-outline-info" onclick="contactHostelOffice()">
                            <i class="fas fa-phone me-2"></i>Contact Hostel Office
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
                    <li>Keep your emergency contact information updated for safety</li>
                    <li>Medical conditions and dietary restrictions help us provide better care</li>
                    <li>Hostel notes help staff understand your preferences and needs</li>
                    <li>Changes to your profile may take 24-48 hours to take effect</li>
                    <li>Contact the hostel office for any urgent changes or issues</li>
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
                    <h4>Hostel Payment Receipt</h4>
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
    document.getElementById('paymentAmount').value = '$500.00';
    
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
    document.getElementById('receiptAmount').textContent = '$500.00';
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

function contactHostelOffice() {
    alert('Contact hostel office functionality would be implemented here. This could open a contact form or provide contact information.');
}
</script>
@endsection

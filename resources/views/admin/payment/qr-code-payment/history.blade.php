@extends('admin.layout.app')

@section('title', 'Payment History')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payment.qr-code-payment.index') }}">QR Code Payment</a></li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
                <h4 class="page-title">Payment History</h4>
            </div>
        </div>
    </div>

    <!-- Current Status Card -->
    <div class="row">
        <div class="col-12">
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
    </div>

    <!-- Payment History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="header-title">Payment History</h4>
                            <p class="text-muted mb-0">All your QR code payments</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.payment.qr-code-payment.index') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i> Make Payment
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Payment ID</th>
                                        <th>QR Codes</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Payment Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $index => $payment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <code>{{ $payment->payment_id }}</code>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $payment->qr_codes_purchased }}</span>
                                            </td>
                                            <td>
                                                <strong>₹{{ number_format($payment->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($payment->payment_method) }}</span>
                                            </td>
                                            <td>
                                                @if($payment->status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                @elseif($payment->status === 'cancelled')
                                                    <span class="badge bg-secondary">Cancelled</span>
                                                @elseif($payment->status === 'refunded')
                                                    <span class="badge bg-info">Refunded</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->paid_at)
                                                    {{ $payment->paid_at->format('d M Y H:i A') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($payment->status === 'completed')
                                                        <a href="{{ route('admin.payment.qr-code-payment.success', $payment) }}" 
                                                           class="btn btn-sm btn-outline-success" title="View Details">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                    @elseif($payment->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-primary" 
                                                                onclick="simulatePayment({{ $payment->id }})" title="Simulate Payment">
                                                            <i class="mdi mdi-play"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                                    <i class="mdi mdi-credit-card"></i>
                                </div>
                            </div>
                            <h5 class="text-muted">No Payments Found</h5>
                            <p class="text-muted">You haven't made any QR code payments yet.</p>
                            <a href="{{ route('admin.payment.qr-code-payment.index') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i> Make Your First Payment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Statistics -->
    @if($payments->count() > 0)
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $payments->where('status', 'completed')->count() }}</h3>
                        <p class="text-muted mb-0">Successful Payments</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-success">₹{{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}</h3>
                        <p class="text-muted mb-0">Total Amount Paid</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-info">{{ $payments->where('status', 'completed')->sum('qr_codes_purchased') }}</h3>
                        <p class="text-muted mb-0">QR Codes Purchased</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function simulatePayment(paymentId) {
    if (confirm('Are you sure you want to simulate this payment? This will mark it as completed.')) {
        $.ajax({
            url: "{{ route('admin.payment.qr-code-payment.simulate', ':id') }}".replace(':id', paymentId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON.error) {
                    toastr.error(xhr.responseJSON.error);
                }
            }
        });
    }
}
</script>
@endsection

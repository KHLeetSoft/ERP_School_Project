@extends('accountant.layouts.app')

@section('title', 'QR Code Scanner')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">QR Code Scanner</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Scan QR Code</h4>
                </div>
                <div class="card-body">
                    <div id="qr-reader" style="width: 100%"></div>
                    <div id="qr-reader-results"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Manual QR Selection</h4>
                </div>
                <div class="card-body">
                    <form id="manual-qr-form">
                        @csrf
                        <div class="mb-3">
                            <label for="qr_code_id" class="form-label">Select QR Code</label>
                            <select class="form-select" id="qr_code_id" name="qr_code_id" required>
                                <option value="">Choose QR Code</option>
                                @foreach($activeQrCodes as $qrCode)
                                    <option value="{{ $qrCode->id }}" data-amount="{{ $qrCode->amount }}" data-type="{{ $qrCode->qr_type }}">
                                        {{ $qrCode->title }} 
                                        @if($qrCode->amount)
                                            - ₹{{ number_format($qrCode->amount, 2) }}
                                        @else
                                            - Variable Amount
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Select Student</label>
                            <select class="form-select" id="student_id" name="student_id" required>
                                <option value="">Choose Student</option>
                            </select>
                        </div>
                        <div class="mb-3" id="amount-field" style="display: none;">
                            <label for="amount" class="form-label">Amount (₹)</label>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Process Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Success Modal -->
    <div class="modal fade" id="paymentSuccessModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="mdi mdi-check-circle text-success font-size-48"></i>
                        <h4 class="mt-3">Payment Processed Successfully!</h4>
                        <p class="text-muted">Payment has been recorded in the system.</p>
                        <div id="payment-details"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('accountant.payment.payments.index') }}" class="btn btn-primary">View All Payments</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
$(document).ready(function() {
    // Load students when QR code is selected
    $('#qr_code_id').change(function() {
        var qrCodeId = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var amount = selectedOption.data('amount');
        var type = selectedOption.data('type');
        
        // Show/hide amount field based on QR code type
        if (type === 'upi' && !amount) {
            $('#amount-field').show();
            $('#amount').attr('required', true);
        } else {
            $('#amount-field').hide();
            $('#amount').attr('required', false);
            $('#amount').val(amount);
        }
        
        // Load students
        loadStudents();
    });
    
    function loadStudents() {
        $.ajax({
            url: "{{ route('accountant.payment.qr-codes.available') }}",
            type: 'GET',
            success: function(response) {
                // This would typically load students from an API
                // For now, we'll use a placeholder
                $('#student_id').html('<option value="">Choose Student</option>');
            }
        });
    }
    
    // Manual QR form submission
    $('#manual-qr-form').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('accountant.payment.qr-scan') }}",
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showPaymentSuccess(response.payment);
                    $('#manual-qr-form')[0].reset();
                    $('#amount-field').hide();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    });
    
    function showPaymentSuccess(payment) {
        var details = `
            <div class="mt-3">
                <p><strong>Student:</strong> ${payment.student.first_name} ${payment.student.last_name}</p>
                <p><strong>Amount:</strong> ₹${parseFloat(payment.amount).toFixed(2)}</p>
                <p><strong>Method:</strong> ${payment.method}</p>
                <p><strong>Reference:</strong> ${payment.reference}</p>
            </div>
        `;
        
        $('#payment-details').html(details);
        $('#paymentSuccessModal').modal('show');
    }
    
    // QR Code Scanner
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Code scanned = ${decodedText}`, decodedResult);
        
        // Process the scanned QR code
        processScannedQR(decodedText);
    }
    
    function processScannedQR(qrData) {
        // Try to find matching QR code
        var qrCodeId = null;
        var amount = null;
        
        // Simple UPI QR parsing
        if (qrData.includes('upi://pay')) {
            var url = new URL(qrData);
            var pa = url.searchParams.get('pa');
            var am = url.searchParams.get('am');
            
            if (am) {
                amount = parseFloat(am);
            }
            
            // Find matching QR code by UPI ID
            $('#qr_code_id option').each(function() {
                var option = $(this);
                if (option.text().includes(pa)) {
                    qrCodeId = option.val();
                    return false;
                }
            });
        }
        
        if (qrCodeId) {
            $('#qr_code_id').val(qrCodeId).trigger('change');
            if (amount) {
                $('#amount').val(amount);
            }
            toastr.success('QR Code detected! Please select student and process payment.');
        } else {
            toastr.warning('QR Code detected but no matching configuration found.');
        }
    }
    
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader",
        { fps: 10, qrbox: { width: 250, height: 250 } },
        false
    );
    
    html5QrcodeScanner.render(onScanSuccess);
});
</script>
@endpush

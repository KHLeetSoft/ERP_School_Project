<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Access - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .qr-access-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .qr-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        
        .qr-header {
            background: linear-gradient(135deg, #10b981, #3b82f6);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .qr-body {
            padding: 2rem;
        }
        
        .qr-code-display {
            text-align: center;
            margin: 2rem 0;
        }
        
        .qr-code-display img {
            max-width: 200px;
            height: auto;
            border: 3px solid #e9ecef;
            border-radius: 10px;
        }
        
        .qr-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        
        .qr-info h6 {
            color: #495057;
            margin-bottom: 1rem;
        }
        
        .qr-info p {
            margin-bottom: 0.5rem;
            color: #6c757d;
        }
        
        .qr-actions {
            margin-top: 2rem;
        }
        
        .btn-qr {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-qr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .error-container {
            text-align: center;
            color: #dc3545;
        }
        
        .error-container i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #10b981;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .scan-stats {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
        }
        
        .scan-stats h6 {
            color: #1976d2;
            margin-bottom: 0.5rem;
        }
        
        .scan-stats p {
            color: #7b1fa2;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="qr-access-container">
        <div class="qr-card">
            @if($qrCode)
                <div class="qr-header">
                    <i class="fas fa-qrcode fa-3x mb-3"></i>
                    <h3>{{ $qrCode->title }}</h3>
                    @if($qrCode->description)
                        <p class="mb-0">{{ $qrCode->description }}</p>
                    @endif
                </div>
                
                <div class="qr-body">
                    @if($qrCode->qr_image_path)
                        <div class="qr-code-display">
                            <img src="{{ $qrCode->qr_image_url }}" alt="QR Code" class="img-fluid">
                        </div>
                    @endif
                    
                    <div class="qr-info">
                        <h6><i class="fas fa-info-circle me-2"></i>QR Code Information</h6>
                        <p><strong>Type:</strong> {{ ucfirst($qrCode->type) }}</p>
                        <p><strong>Code:</strong> {{ $qrCode->code }}</p>
                        <p><strong>Created:</strong> {{ $qrCode->created_at->format('M d, Y H:i') }}</p>
                        @if($qrCode->expires_at)
                            <p><strong>Expires:</strong> {{ $qrCode->expires_at->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                    
                    @if($qrCode->type === 'payment')
                        <div class="payment-form">
                            <h6><i class="fas fa-credit-card me-2"></i>Process Payment</h6>
                            <form id="paymentForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Amount</label>
                                            <input type="number" class="form-control" name="amount" 
                                                   value="{{ $qrCode->data['amount'] ?? '' }}" 
                                                   step="0.01" min="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Method</label>
                                            <select class="form-select" name="payment_method" required>
                                                <option value="cash">Cash</option>
                                                <option value="card">Card</option>
                                                <option value="upi">UPI</option>
                                                <option value="netbanking">Net Banking</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Transaction ID (Optional)</label>
                                    <input type="text" class="form-control" name="transaction_id" 
                                           placeholder="Enter transaction ID...">
                                </div>
                                <button type="submit" class="btn btn-success btn-qr w-100">
                                    <i class="fas fa-credit-card me-2"></i>Process Payment
                                </button>
                            </form>
                        </div>
                    @elseif($qrCode->type === 'student')
                        <div class="student-info">
                            <h6><i class="fas fa-user me-2"></i>Student Information</h6>
                            <div class="alert alert-info">
                                <p><strong>Student:</strong> {{ $qrCode->data['student_name'] ?? 'N/A' }}</p>
                                <p><strong>Admission Number:</strong> {{ $qrCode->data['admission_number'] ?? 'N/A' }}</p>
                                <p><strong>Class:</strong> {{ $qrCode->data['class'] ?? 'N/A' }}</p>
                            </div>
                            <a href="{{ $qrCode->url }}" class="btn btn-primary btn-qr w-100">
                                <i class="fas fa-eye me-2"></i>View Student Profile
                            </a>
                        </div>
                    @elseif($qrCode->type === 'link')
                        <div class="link-info">
                            <h6><i class="fas fa-link me-2"></i>Link Information</h6>
                            <div class="alert alert-info">
                                <p><strong>URL:</strong> {{ $qrCode->url }}</p>
                            </div>
                            <a href="{{ $qrCode->url }}" class="btn btn-primary btn-qr w-100" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Visit Link
                            </a>
                        </div>
                    @else
                        <div class="general-info">
                            <h6><i class="fas fa-info-circle me-2"></i>QR Code Data</h6>
                            <div class="alert alert-info">
                                <pre class="mb-0">{{ json_encode($qrCode->data, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                    
                    <div class="scan-stats">
                        <h6><i class="fas fa-chart-line me-2"></i>Scan Statistics</h6>
                        <p>This QR code has been scanned <strong>{{ $qrCode->scan_count }}</strong> times</p>
                    </div>
                    
                    <div class="qr-actions">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-qr w-100" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>Print
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-success btn-qr w-100" onclick="downloadQR()">
                                    <i class="fas fa-download me-2"></i>Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="qr-header">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h3>QR Code Not Found</h3>
                </div>
                
                <div class="qr-body">
                    <div class="error-container">
                        <i class="fas fa-times-circle"></i>
                        <h5>{{ $error ?? 'QR Code not found or expired' }}</h5>
                        <p class="text-muted">The QR code you're looking for doesn't exist or has expired.</p>
                        <a href="{{ url('/') }}" class="btn btn-primary btn-qr">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Payment form submission
        document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading
            submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>Processing...';
            submitBtn.disabled = true;
            
            fetch('{{ route("accountant.qr.process", $qrCode->code ?? "dummy") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.success);
                    // Reset form
                    this.reset();
                } else {
                    showAlert('error', data.error || 'Payment processing failed');
                }
            })
            .catch(error => {
                showAlert('error', 'An error occurred while processing payment');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Download QR code
        function downloadQR() {
            @if($qrCode && $qrCode->qr_image_path)
                const link = document.createElement('a');
                link.href = '{{ $qrCode->qr_image_url }}';
                link.download = 'qr_code_{{ $qrCode->code }}.png';
                link.click();
            @endif
        }
        
        // Show alert
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            document.querySelector('.qr-body').insertAdjacentHTML('afterbegin', alertHtml);
            
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>

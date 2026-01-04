@extends('accountant.layout.app')

@section('title', 'Generate QR Code')
@section('page-title', 'Generate QR Code')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Generate New QR Code</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('accountant.qr-codes.store') }}" method="POST" id="qrCodeForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">QR Code Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            name="type" id="qrType" required onchange="toggleTypeFields()">
                                        <option value="">Select Type</option>
                                        <option value="student" {{ old('type') == 'student' ? 'selected' : '' }}>Student QR</option>
                                        <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>Payment QR</option>
                                        <option value="fee" {{ old('type') == 'fee' ? 'selected' : '' }}>Fee QR</option>
                                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General QR</option>
                                        <option value="link" {{ old('type') == 'link' ? 'selected' : '' }}>Link QR</option>
                                        <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document QR</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="3" placeholder="Enter description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Student Selection (for student type) -->
                        <div id="studentFields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Select Student</label>
                                <select class="form-select @error('student_id') is-invalid @enderror" 
                                        name="student_id" id="studentSelect">
                                    <option value="">Choose Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->admission_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Payment Fields (for payment type) -->
                        <div id="paymentFields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Amount</label>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                               name="amount" value="{{ old('amount') }}" step="0.01" min="0">
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                                name="payment_method">
                                            <option value="any">Any Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="upi">UPI</option>
                                            <option value="netbanking">Net Banking</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Description</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                       name="payment_description" value="{{ old('payment_description') }}" 
                                       placeholder="Enter payment description...">
                                @error('payment_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Link Fields (for link type) -->
                        <div id="linkFields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">URL <span class="text-danger">*</span></label>
                                <input type="url" class="form-control @error('url') is-invalid @enderror" 
                                       name="url" value="{{ old('url') }}" placeholder="https://example.com">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Expiration -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                           name="expires_at" value="{{ old('expires_at') }}">
                                    <small class="text-muted">Leave empty for no expiration</small>
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-qrcode me-2"></i>Generate QR Code
                            </button>
                            <a href="{{ route('accountant.qr-codes') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">QR Code Preview</h5>
                </div>
                <div class="card-body text-center">
                    <div id="qrPreview" class="mb-3">
                        <div class="qr-placeholder">
                            <i class="fas fa-qrcode fa-3x text-muted"></i>
                            <p class="text-muted mt-2">QR Code will appear here</p>
                        </div>
                    </div>
                    <div id="qrInfo" class="text-start">
                        <h6>QR Code Information:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Type:</strong> <span id="previewType">-</span></li>
                            <li><strong>Title:</strong> <span id="previewTitle">-</span></li>
                            <li><strong>Description:</strong> <span id="previewDescription">-</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- QR Code Types Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">QR Code Types</h6>
                </div>
                <div class="card-body">
                    <div class="qr-type-info">
                        <div class="mb-2">
                            <span class="badge bg-primary me-2">Student</span>
                            <small>Links to student profile</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-success me-2">Payment</span>
                            <small>Quick payment processing</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-warning me-2">Fee</span>
                            <small>Fee information and payment</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-info me-2">Link</span>
                            <small>Custom URL redirection</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-secondary me-2">General</span>
                            <small>Custom data storage</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-dark me-2">Document</span>
                            <small>Document information</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleTypeFields() {
    const qrType = document.getElementById('qrType').value;
    
    // Hide all type-specific fields
    document.getElementById('studentFields').style.display = 'none';
    document.getElementById('paymentFields').style.display = 'none';
    document.getElementById('linkFields').style.display = 'none';
    
    // Show relevant fields based on type
    switch(qrType) {
        case 'student':
            document.getElementById('studentFields').style.display = 'block';
            break;
        case 'payment':
            document.getElementById('paymentFields').style.display = 'block';
            break;
        case 'link':
            document.getElementById('linkFields').style.display = 'block';
            break;
    }
    
    updatePreview();
}

function updatePreview() {
    const qrType = document.getElementById('qrType').value;
    const title = document.querySelector('input[name="title"]').value;
    const description = document.querySelector('textarea[name="description"]').value;
    
    document.getElementById('previewType').textContent = qrType || '-';
    document.getElementById('previewTitle').textContent = title || '-';
    document.getElementById('previewDescription').textContent = description || '-';
}

// Update preview on input change
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection

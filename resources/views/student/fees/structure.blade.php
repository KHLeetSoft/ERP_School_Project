@extends('student.layout.app')

@section('title', 'Fee Structure')
@section('page-title', 'Fee Structure')

@section('content')
<div class="row">
    <!-- Fee Structure Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">Fee Structure</h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-1"></i>
                            Academic Year: {{ $academicYear }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Class: {{ $student->class_name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="total-fees">
                            <h3 class="mb-0 text-success">₹{{ number_format($totalFees, 2) }}</h3>
                            <small class="text-muted">Total Fees</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Structure Details -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Fee Breakdown</h5>
            </div>
            <div class="card-body">
                @if($feeStructure->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fee Type</th>
                                    <th>Frequency</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Late Fee</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feeStructure as $fee)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="fee-icon me-3">
                                                    <i class="fas fa-{{ $this->getFeeIcon($fee->fee_type) }} text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $fee->fee_type }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $this->getFrequencyColor($fee->frequency) }}">
                                                {{ ucfirst($fee->frequency) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">₹{{ number_format($fee->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($fee->due_date)
                                                {{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fee->late_fee > 0)
                                                <span class="text-danger">₹{{ number_format($fee->late_fee, 2) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fee->description)
                                                <small class="text-muted">{{ $fee->description }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-list-alt text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No Fee Structure Found</h4>
                        <p class="text-muted">No fee structure found for the selected academic year.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Fee Summary -->
    @if($feeStructure->count() > 0)
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Fee Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="feeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Schedule -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Payment Schedule</h5>
                </div>
                <div class="card-body">
                    @php
                        $monthlyFees = $feeStructure->where('frequency', 'monthly');
                        $quarterlyFees = $feeStructure->where('frequency', 'quarterly');
                        $yearlyFees = $feeStructure->where('frequency', 'yearly');
                    @endphp
                    
                    @if($monthlyFees->count() > 0)
                        <div class="schedule-item mb-3">
                            <h6 class="text-primary">Monthly Fees</h6>
                            <p class="mb-1">Due: 1st of every month</p>
                            <p class="mb-0"><strong>₹{{ number_format($monthlyFees->sum('amount'), 2) }}</strong></p>
                        </div>
                    @endif
                    
                    @if($quarterlyFees->count() > 0)
                        <div class="schedule-item mb-3">
                            <h6 class="text-info">Quarterly Fees</h6>
                            <p class="mb-1">Due: Start of each quarter</p>
                            <p class="mb-0"><strong>₹{{ number_format($quarterlyFees->sum('amount'), 2) }}</strong></p>
                        </div>
                    @endif
                    
                    @if($yearlyFees->count() > 0)
                        <div class="schedule-item mb-3">
                            <h6 class="text-success">Yearly Fees</h6>
                            <p class="mb-1">Due: Start of academic year</p>
                            <p class="mb-0"><strong>₹{{ number_format($yearlyFees->sum('amount'), 2) }}</strong></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Fee Policies -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Fee Policies</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Payment Methods</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Online Payment (Credit/Debit Card)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Bank Transfer</li>
                            <li><i class="fas fa-check text-success me-2"></i>Cash Payment at Office</li>
                            <li><i class="fas fa-check text-success me-2"></i>Cheque Payment</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Important Notes</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Late fees may apply after due date</li>
                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Payment receipts must be kept safely</li>
                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Contact office for payment issues</li>
                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Refunds subject to school policy</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.fees.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Fees
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.fees.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Payment History
                        </a>
                        <button type="button" class="btn btn-outline-success" onclick="printStructure()">
                            <i class="fas fa-print me-2"></i>Print Structure
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .fee-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .schedule-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 4px solid #007bff;
    }

    .schedule-item h6 {
        margin-bottom: 0.5rem;
    }

    .schedule-item p {
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    @media print {
        .btn, .card-header, .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Fee Distribution Chart
    @if($feeStructure->count() > 0)
        const ctx = document.getElementById('feeChart').getContext('2d');
        const feeChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($feeStructure->pluck('fee_type')) !!},
                datasets: [{
                    data: {!! json_encode($feeStructure->pluck('amount')) !!},
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#e83e8c'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    @endif

    // Print Structure
    function printStructure() {
        window.print();
    }
</script>
@endsection

@php
    function getFeeIcon($feeType) {
        switch(strtolower($feeType)) {
            case 'tuition':
            case 'academic':
                return 'graduation-cap';
            case 'transport':
            case 'bus':
                return 'bus';
            case 'hostel':
            case 'boarding':
                return 'bed';
            case 'library':
                return 'book';
            case 'laboratory':
            case 'lab':
                return 'flask';
            case 'sports':
                return 'futbol';
            case 'examination':
            case 'exam':
                return 'clipboard-check';
            case 'development':
                return 'building';
            default:
                return 'money-bill';
        }
    }

    function getFrequencyColor($frequency) {
        switch(strtolower($frequency)) {
            case 'monthly':
                return 'primary';
            case 'quarterly':
                return 'info';
            case 'half_yearly':
                return 'warning';
            case 'yearly':
                return 'success';
            case 'one_time':
                return 'secondary';
            default:
                return 'light';
        }
    }
@endphp

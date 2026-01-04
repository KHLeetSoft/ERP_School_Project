@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Fee Structure Details</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.finance.fee-structure.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bx bx-left-arrow-alt"></i> Back to List
                </a>
                <a href="{{ route('admin.finance.fee-structure.edit', $feeStructure) }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-edit"></i> Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <h6 class="text-primary mb-3">Basic Information</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">ID:</label>
                            <div class="text-muted">{{ $feeStructure->id }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Status:</label>
                            <div>{!! $feeStructure->status_badge !!}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Class:</label>
                            <div class="text-muted">{{ $feeStructure->class->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Academic Year:</label>
                            <div class="text-muted">{{ $feeStructure->academic_year }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Fee Type:</label>
                            <div class="text-muted">{{ $feeStructure->fee_type }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Frequency:</label>
                            <div class="text-muted">{{ $feeStructure->frequency_label }}</div>
                        </div>
                    </div>
                </div>

                <!-- Financial Details -->
                <div class="col-md-6">
                    <h6 class="text-success mb-3">Financial Details</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Amount:</label>
                            <div class="text-success fw-bold fs-5">{{ $feeStructure->formatted_amount }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Late Fee:</label>
                            <div class="text-warning">{{ $feeStructure->formatted_late_fee }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Discount Applicable:</label>
                            <div class="text-muted">
                                @if($feeStructure->discount_applicable)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Max Discount:</label>
                            <div class="text-info">{{ $feeStructure->formatted_max_discount }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Due Date:</label>
                            <div class="text-muted">
                                @if($feeStructure->due_date)
                                    {{ $feeStructure->due_date->format('d M Y') }}
                                @else
                                    <span class="text-muted">Not Set</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Created:</label>
                            <div class="text-muted">{{ $feeStructure->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($feeStructure->description)
                <div class="col-12">
                    <h6 class="text-info mb-3">Description</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $feeStructure->description }}
                    </div>
                </div>
                @endif

                <!-- System Information -->
                <div class="col-12">
                    <h6 class="text-secondary mb-3">System Information</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">School:</label>
                            <div class="text-muted">{{ $feeStructure->school->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Created By:</label>
                            <div class="text-muted">{{ $feeStructure->createdBy->name ?? 'System' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Updated By:</label>
                            <div class="text-muted">{{ $feeStructure->updatedBy->name ?? 'System' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Last Updated:</label>
                            <div class="text-muted">{{ $feeStructure->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Information -->
    <div class="row g-3 mt-3">
        <!-- Fee Collections -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Recent Fee Collections</h6>
                </div>
                <div class="card-body">
                    @if($feeStructure->feeCollections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feeStructure->feeCollections->take(5) as $collection)
                                    <tr>
                                        <td>{{ $collection->student->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($collection->amount, 2) }}</td>
                                        <td>{{ $collection->payment_date?->format('d M Y') ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No fee collections found for this structure.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Quick Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-muted small">Total Collections</div>
                                <div class="fs-5 fw-semibold text-primary">{{ $feeStructure->feeCollections->count() }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-muted small">Total Collected</div>
                                <div class="fs-5 fw-semibold text-success">₹{{ number_format($feeStructure->feeCollections->sum('amount'), 2) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-muted small">Pending Amount</div>
                                <div class="fs-6 fw-semibold text-warning">₹{{ number_format($feeStructure->amount - $feeStructure->feeCollections->sum('amount'), 2) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-muted small">Collection Rate</div>
                                <div class="fs-6 fw-semibold text-info">
                                    @php
                                        $rate = $feeStructure->amount > 0 ? ($feeStructure->feeCollections->sum('amount') / $feeStructure->amount) * 100 : 0;
                                    @endphp
                                    {{ number_format($rate, 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

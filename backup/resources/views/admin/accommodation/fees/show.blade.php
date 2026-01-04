@extends('admin.layout.app')

@section('title', 'Fee Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.fees.edit', $fee->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.accommodation.fees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Fees
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Student Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $fee->allocation->student->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Admission No:</th>
                                    <td>{{ $fee->allocation->student->admission_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Class:</th>
                                    <td>{{ $fee->allocation->student->class ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Section:</th>
                                    <td>{{ $fee->allocation->student->section ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Accommodation Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Hostel:</th>
                                    <td>{{ $fee->allocation->hostel->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Room:</th>
                                    <td>{{ $fee->allocation->room->room_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bed No:</th>
                                    <td>{{ $fee->allocation->bed_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Monthly Fee:</th>
                                    <td>₹{{ number_format($fee->allocation->monthly_fee, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Fee Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Month/Year:</th>
                                    <td>{{ $fee->getMonthName() }} {{ $fee->year }}</td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td>₹{{ number_format($fee->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Due Date:</th>
                                    <td>{{ $fee->due_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $fee->status == 'paid' ? 'success' : ($fee->status == 'overdue' ? 'danger' : ($fee->status == 'waived' ? 'info' : 'warning')) }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Payment Date:</th>
                                    <td>{{ $fee->payment_date ? $fee->payment_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>{{ $fee->payment_method ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Transaction ID:</th>
                                    <td>{{ $fee->transaction_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $fee->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($fee->remarks)
                    <div class="row">
                        <div class="col-12">
                            <h5>Remarks:</h5>
                            <p class="text-muted">{{ $fee->remarks }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Payment Actions -->
                    @if($fee->status == 'pending')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <button class="btn btn-success" onclick="markAsPaid({{ $fee->id }})">
                                        <i class="fas fa-check"></i> Mark as Paid
                                    </button>
                                    <button class="btn btn-info" onclick="markAsWaived({{ $fee->id }})">
                                        <i class="fas fa-gift"></i> Mark as Waived
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsPaid(id) {
    if (confirm('Are you sure you want to mark this fee as paid?')) {
        fetch(`/admin/accommodation/fees/${id}/mark-paid`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error marking fee as paid');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking fee as paid');
        });
    }
}

function markAsWaived(id) {
    if (confirm('Are you sure you want to mark this fee as waived?')) {
        // Implementation for marking as waived
        alert('Mark as waived functionality will be implemented soon');
    }
}
</script>
@endsection

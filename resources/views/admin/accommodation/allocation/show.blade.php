@extends('admin.layout.app')

@section('title', 'Allocation Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Allocation Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.allocation.edit', $allocation->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.accommodation.allocation.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Allocations
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
                                    <td>{{ $allocation->student->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Admission No:</th>
                                    <td>{{ $allocation->student->admission_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Class:</th>
                                    <td>{{ $allocation->student->class ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Section:</th>
                                    <td>{{ $allocation->student->section ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Accommodation Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Hostel:</th>
                                    <td>{{ $allocation->hostel->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Room:</th>
                                    <td>{{ $allocation->room->room_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bed No:</th>
                                    <td>{{ $allocation->bed_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $allocation->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($allocation->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Dates</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Join Date:</th>
                                    <td>{{ $allocation->join_date ? $allocation->join_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Leave Date:</th>
                                    <td>{{ $allocation->leave_date ? $allocation->leave_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Duration:</th>
                                    <td>{{ $allocation->getDurationInDays() }} days</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Financial Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Monthly Fee:</th>
                                    <td>₹{{ number_format($allocation->monthly_fee, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Security Deposit:</th>
                                    <td>₹{{ number_format($allocation->security_deposit ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total Fees Paid:</th>
                                    <td>₹{{ number_format($allocation->getTotalFeesPaid(), 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Pending Fees:</th>
                                    <td>₹{{ number_format($allocation->getPendingFees(), 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($allocation->remarks)
                    <div class="row">
                        <div class="col-12">
                            <h5>Remarks:</h5>
                            <p class="text-muted">{{ $allocation->remarks }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Recent Fees -->
                    @if($allocation->fees->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Recent Fees</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Month/Year</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allocation->fees->take(5) as $fee)
                                        <tr>
                                            <td>{{ $fee->getMonthName() }} {{ $fee->year }}</td>
                                            <td>₹{{ number_format($fee->amount, 2) }}</td>
                                            <td>{{ $fee->due_date->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $fee->status == 'paid' ? 'success' : ($fee->status == 'overdue' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($fee->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $fee->payment_date ? $fee->payment_date->format('d M Y') : 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Recent Attendance -->
                    @if($allocation->attendances->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Recent Attendance</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allocation->attendances->take(5) as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $attendance->getStatusBadgeClass() }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('h:i A') : 'N/A' }}</td>
                                            <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('h:i A') : 'N/A' }}</td>
                                            <td>{{ $attendance->getDuration() ? $attendance->getDuration() . ' hours' : 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

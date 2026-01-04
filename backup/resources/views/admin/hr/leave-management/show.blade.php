@extends('admin.layout.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Leave Request Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.hr.dashboard') }}">HR</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.hr.leave-management.index') }}">Leave Management</a></li>
                    <li class="breadcrumb-item active">View Leave</li>
                </ul>
            </div>
            <div class="col-auto float-right ml-auto">
                <a href="{{ route('admin.hr.leave-management.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                @if($leave->status === 'pending')
                    <a href="{{ route('admin.hr.leave-management.edit', $leave->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Leave Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leave Request Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Leave ID:</label>
                                <p class="form-control-static">#{{ $leave->id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status:</label>
                                <p class="form-control-static">{!! $leave->status_badge !!}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Leave Type:</label>
                                <p class="form-control-static">{{ $leave->leave_type_label }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Half Day Type:</label>
                                <p class="form-control-static">
                                    @if($leave->half_day_type)
                                        {{ ucfirst($leave->half_day_type) }}
                                    @else
                                        Full Day
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Start Date:</label>
                                <p class="form-control-static">{{ $leave->start_date->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">End Date:</label>
                                <p class="form-control-static">{{ $leave->end_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Total Days:</label>
                                <p class="form-control-static">{{ $leave->total_days }} days</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Applied On:</label>
                                <p class="form-control-static">{{ $leave->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Reason:</label>
                                <p class="form-control-static">{{ $leave->reason }}</p>
                            </div>
                        </div>
                    </div>

                    @if($leave->contact_during_leave)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Contact During Leave:</label>
                                <p class="form-control-static">{{ $leave->contact_during_leave }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($leave->address_during_leave)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Address During Leave:</label>
                                <p class="form-control-static">{{ $leave->address_during_leave }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($leave->status === 'rejected' && $leave->rejection_reason)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold text-danger">Rejection Reason:</label>
                                <p class="form-control-static text-danger">{{ $leave->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Staff Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Staff Information</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($leave->staff->profile_photo)
                            <img src="{{ asset('storage/' . $leave->staff->profile_photo) }}" 
                                 alt="Profile Photo" class="rounded-circle" width="80">
                        @else
                            <div class="avatar-placeholder rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background-color: #e9ecef;">
                                <i class="fas fa-user fa-2x text-muted"></i>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Employee ID:</label>
                        <p class="form-control-static">{{ $leave->staff->employee_id }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Name:</label>
                        <p class="form-control-static">{{ $leave->staff->full_name }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Department:</label>
                        <p class="form-control-static">{{ $leave->staff->department }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Designation:</label>
                        <p class="form-control-static">{{ $leave->staff->designation }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Email:</label>
                        <p class="form-control-static">{{ $leave->staff->email }}</p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Phone:</label>
                        <p class="form-control-static">{{ $leave->staff->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Approval Information -->
            @if($leave->status !== 'pending')
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Approval Information</h4>
                </div>
                <div class="card-body">
                    @if($leave->status === 'approved')
                        <div class="form-group">
                            <label class="font-weight-bold text-success">Approved By:</label>
                            <p class="form-control-static">{{ $leave->approvedBy->name ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-success">Approved On:</label>
                            <p class="form-control-static">{{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('d M Y, h:i A') : 'N/A' }}</p>
                        </div>
                    @endif

                    @if($leave->status === 'rejected')
                        <div class="form-group">
                            <label class="font-weight-bold text-danger">Rejected By:</label>
                            <p class="form-control-static">{{ $leave->rejectedBy->name ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-danger">Rejected On:</label>
                            <p class="form-control-static">{{ $leave->rejected_at ? \Carbon\Carbon::parse($leave->rejected_at)->format('d M Y, h:i A') : 'N/A' }}</p>
                        </div>
                    @endif

                    @if($leave->status === 'cancelled')
                        <div class="form-group">
                            <label class="font-weight-bold text-warning">Cancelled By:</label>
                            <p class="form-control-static">{{ $leave->cancelledBy->name ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-warning">Cancelled On:</label>
                            <p class="form-control-static">{{ $leave->cancelled_at ? \Carbon\Carbon::parse($leave->cancelled_at)->format('d M Y, h:i A') : 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    @if($leave->status === 'pending')
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Actions</h5>
                    <div class="btn-group" role="group">
                        <form action="{{ route('admin.hr.leave-management.toggle-status', $leave->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success me-2" 
                                    onclick="return confirm('Are you sure you want to approve this leave request?')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>

                        <form action="{{ route('admin.hr.leave-management.toggle-status', $leave->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="btn btn-danger me-2" 
                                    onclick="return confirm('Are you sure you want to reject this leave request?')">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>

                        <form action="{{ route('admin.hr.leave-management.toggle-status', $leave->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="cancel">
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('Are you sure you want to cancel this leave request?')">
                                <i class="fas fa-ban"></i> Cancel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
.avatar-placeholder {
    background-color: #e9ecef;
    color: #6c757d;
}
</style>
@endsection

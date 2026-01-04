@extends('admin.layout.app')

@section('title', 'Edit Leave Request')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Edit Leave Request</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.hr.dashboard') }}">HR</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.hr.leave-management.index') }}">Leave Management</a></li>
                    <li class="breadcrumb-item active">Edit Leave</li>
                </ul>
            </div>
            <div class="col-auto float-right ml-auto">
                <a href="{{ route('admin.hr.leave-management.show', $leave->id) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> View Details
                </a>
                <a href="{{ route('admin.hr.leave-management.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Leave Request #{{ $leave->id }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.hr.leave-management.update', $leave->id) }}" method="POST" id="leaveForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Staff Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="staff_id" class="form-label">Staff Member <span class="text-danger">*</span></label>
                                    <select name="staff_id" id="staff_id" class="form-control select2 @error('staff_id') is-invalid @enderror" required>
                                        <option value="">Select Staff Member</option>
                                        @foreach($staff as $s)
                                            <option value="{{ $s->id }}" {{ old('staff_id', $leave->staff_id) == $s->id ? 'selected' : '' }}>
                                                {{ $s->employee_id }} - {{ $s->full_name }} ({{ $s->department }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('staff_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Leave Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_type" class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <select name="leave_type" id="leave_type" class="form-control @error('leave_type') is-invalid @enderror" required>
                                        <option value="">Select Leave Type</option>
                                        <option value="annual" {{ old('leave_type', $leave->leave_type) == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                                        <option value="sick" {{ old('leave_type', $leave->leave_type) == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                        <option value="casual" {{ old('leave_type', $leave->leave_type) == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                                        <option value="maternity" {{ old('leave_type', $leave->leave_type) == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                        <option value="paternity" {{ old('leave_type', $leave->leave_type) == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                                        <option value="study" {{ old('leave_type', $leave->leave_type) == 'study' ? 'selected' : '' }}>Study Leave</option>
                                        <option value="other" {{ old('leave_type', $leave->leave_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('leave_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Start Date -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Half Day Type -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="half_day_type" class="form-label">Half Day Type</label>
                                    <select name="half_day_type" id="half_day_type" class="form-control @error('half_day_type') is-invalid @enderror">
                                        <option value="">Full Day</option>
                                        <option value="first_half" {{ old('half_day_type', $leave->half_day_type) == 'first_half' ? 'selected' : '' }}>First Half</option>
                                        <option value="second_half" {{ old('half_day_type', $leave->half_day_type) == 'second_half' ? 'selected' : '' }}>Second Half</option>
                                    </select>
                                    @error('half_day_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Total Days (Auto-calculated) -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_days" class="form-label">Total Days</label>
                                    <input type="number" name="total_days" id="total_days" class="form-control" 
                                           value="{{ old('total_days', $leave->total_days) }}" readonly step="0.5" min="0.5">
                                    <small class="form-text text-muted">Auto-calculated based on dates</small>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="pending" {{ old('status', $leave->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $leave->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status', $leave->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="cancelled" {{ old('status', $leave->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- School ID (Hidden) -->
                            <div class="col-md-4">
                                <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Reason -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reason" class="form-label">Reason for Leave <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" 
                                              placeholder="Please provide a detailed reason for your leave request..." required>{{ old('reason', $leave->reason) }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Contact During Leave -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_during_leave" class="form-label">Contact During Leave</label>
                                    <input type="text" name="contact_during_leave" id="contact_during_leave" class="form-control @error('contact_during_leave') is-invalid @enderror" 
                                           value="{{ old('contact_during_leave', $leave->contact_during_leave) }}" placeholder="Emergency contact number">
                                    @error('contact_during_leave')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address During Leave -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_during_leave" class="form-label">Address During Leave</label>
                                    <input type="text" name="address_during_leave" id="address_during_leave" class="form-control @error('address_during_leave') is-invalid @enderror" 
                                           value="{{ old('address_during_leave', $leave->address_during_leave) }}" placeholder="Address where you'll be during leave">
                                    @error('address_during_leave')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Conditional Fields for Rejected Status -->
                        <div class="row" id="rejectionFields" style="display: {{ $leave->status === 'rejected' ? 'block' : 'none' }};">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                    <textarea name="rejection_reason" id="rejection_reason" rows="3" class="form-control @error('rejection_reason') is-invalid @enderror" 
                                              placeholder="Please provide a reason for rejection...">{{ old('rejection_reason', $leave->rejection_reason) }}</textarea>
                                    @error('rejection_reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Leave Request
                                    </button>
                                    <a href="{{ route('admin.hr.leave-management.show', $leave->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Select an option',
        allowClear: true
    });

    // Calculate total days when dates change
    $('#start_date, #end_date').on('change', function() {
        calculateTotalDays();
    });

    // Show/hide rejection reason field based on status
    $('#status').on('change', function() {
        if ($(this).val() === 'rejected') {
            $('#rejectionFields').show();
            $('#rejection_reason').prop('required', true);
        } else {
            $('#rejectionFields').hide();
            $('#rejection_reason').prop('required', false);
        }
    });

    // Form validation
    $('#leaveForm').on('submit', function(e) {
        var startDate = new Date($('#start_date').val());
        var endDate = new Date($('#end_date').val());
        
        if (endDate < startDate) {
            e.preventDefault();
            alert('End date cannot be earlier than start date.');
            return false;
        }
        
        if ($('#total_days').val() <= 0) {
            e.preventDefault();
            alert('Please select valid start and end dates.');
            return false;
        }
    });

    // Calculate total days function
    function calculateTotalDays() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var halfDayType = $('#half_day_type').val();
        
        if (startDate && endDate) {
            var start = new Date(startDate);
            var end = new Date(endDate);
            
            if (end >= start) {
                var timeDiff = end.getTime() - start.getTime();
                var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both start and end dates
                
                // Adjust for half days
                if (halfDayType && startDate === endDate) {
                    daysDiff = 0.5;
                }
                
                $('#total_days').val(daysDiff);
            } else {
                $('#total_days').val(0);
            }
        } else {
            $('#total_days').val(0);
        }
    }

    // Initial calculation
    calculateTotalDays();
});
</script>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endsection

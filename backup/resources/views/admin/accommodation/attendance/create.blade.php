@extends('admin.layout.app')

@section('title', 'Create Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Attendance Record</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.attendance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Attendance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.accommodation.attendance.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="allocation_id">Student Allocation <span class="text-danger">*</span></label>
                                    <select name="allocation_id" id="allocation_id" class="form-control @error('allocation_id') is-invalid @enderror" required>
                                        <option value="">Select Student Allocation</option>
                                        @foreach($allocations as $allocation)
                                        <option value="{{ $allocation->id }}" {{ old('allocation_id') == $allocation->id ? 'selected' : '' }}>
                                            {{ $allocation->student->user->name ?? 'N/A' }} - {{ $allocation->hostel->name ?? 'N/A' }} (Room: {{ $allocation->room->room_no ?? 'N/A' }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('allocation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" 
                                           value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required onchange="toggleTimeFields()">
                                        <option value="">Select Status</option>
                                        <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_in_time">Check In Time</label>
                                    <input type="time" name="check_in_time" id="check_in_time" class="form-control @error('check_in_time') is-invalid @enderror" 
                                           value="{{ old('check_in_time') }}">
                                    @error('check_in_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_out_time">Check Out Time</label>
                                    <input type="time" name="check_out_time" id="check_out_time" class="form-control @error('check_out_time') is-invalid @enderror" 
                                           value="{{ old('check_out_time') }}">
                                    @error('check_out_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" 
                                           value="{{ old('remarks') }}" placeholder="Any additional remarks">
                                    @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Attendance
                            </button>
                            <a href="{{ route('admin.accommodation.attendance.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleTimeFields() {
    const status = document.getElementById('status').value;
    const checkInField = document.getElementById('check_in_time');
    const checkOutField = document.getElementById('check_out_time');
    
    if (status === 'present' || status === 'late') {
        checkInField.disabled = false;
        checkOutField.disabled = false;
        checkInField.required = true;
    } else {
        checkInField.disabled = true;
        checkOutField.disabled = true;
        checkInField.required = false;
        checkInField.value = '';
        checkOutField.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleTimeFields();
});
</script>
@endsection

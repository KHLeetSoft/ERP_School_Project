@extends('admin.layout.app')

@section('title', 'Edit Allocation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Allocation</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.allocation.show', $allocation->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.accommodation.allocation.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Allocations
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.accommodation.allocation.update', $allocation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Student <span class="text-danger">*</span></label>
                                    <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ (old('student_id', $allocation->student_id) == $student->id) ? 'selected' : '' }}>
                                            {{ $student->user->name ?? 'N/A' }} ({{ $student->admission_no ?? 'N/A' }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hostel_id">Hostel <span class="text-danger">*</span></label>
                                    <select name="hostel_id" id="hostel_id" class="form-control @error('hostel_id') is-invalid @enderror" required onchange="loadRooms()">
                                        <option value="">Select Hostel</option>
                                        @foreach($hostels as $hostel)
                                        <option value="{{ $hostel->id }}" {{ (old('hostel_id', $allocation->hostel_id) == $hostel->id) ? 'selected' : '' }}>
                                            {{ $hostel->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('hostel_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_id">Room <span class="text-danger">*</span></label>
                                    <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                                        <option value="">Select Room</option>
                                        @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ (old('room_id', $allocation->room_id) == $room->id) ? 'selected' : '' }}>
                                            {{ $room->room_no }} (Capacity: {{ $room->capacity }}, Status: {{ $room->status }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bed_no">Bed Number</label>
                                    <input type="text" name="bed_no" id="bed_no" class="form-control @error('bed_no') is-invalid @enderror" 
                                           value="{{ old('bed_no', $allocation->bed_no) }}" placeholder="e.g., A1, B2">
                                    @error('bed_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="join_date">Join Date <span class="text-danger">*</span></label>
                                    <input type="date" name="join_date" id="join_date" class="form-control @error('join_date') is-invalid @enderror" 
                                           value="{{ old('join_date', $allocation->join_date ? $allocation->join_date->format('Y-m-d') : '') }}" required>
                                    @error('join_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="leave_date">Leave Date</label>
                                    <input type="date" name="leave_date" id="leave_date" class="form-control @error('leave_date') is-invalid @enderror" 
                                           value="{{ old('leave_date', $allocation->leave_date ? $allocation->leave_date->format('Y-m-d') : '') }}">
                                    @error('leave_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', $allocation->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="left" {{ old('status', $allocation->status) == 'left' ? 'selected' : '' }}>Left</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="monthly_fee">Monthly Fee <span class="text-danger">*</span></label>
                                    <input type="number" name="monthly_fee" id="monthly_fee" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                           value="{{ old('monthly_fee', $allocation->monthly_fee) }}" min="0" step="0.01" required>
                                    @error('monthly_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="security_deposit">Security Deposit</label>
                                    <input type="number" name="security_deposit" id="security_deposit" class="form-control @error('security_deposit') is-invalid @enderror" 
                                           value="{{ old('security_deposit', $allocation->security_deposit) }}" min="0" step="0.01">
                                    @error('security_deposit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" 
                                      rows="3" placeholder="Any additional remarks">{{ old('remarks', $allocation->remarks) }}</textarea>
                            @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Allocation
                            </button>
                            <a href="{{ route('admin.accommodation.allocation.show', $allocation->id) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.accommodation.allocation.index') }}" class="btn btn-secondary">
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
function loadRooms() {
    const hostelId = document.getElementById('hostel_id').value;
    const roomSelect = document.getElementById('room_id');
    
    roomSelect.innerHTML = '<option value="">Loading rooms...</option>';
    
    if (hostelId) {
        fetch(`/admin/accommodation/rooms/get-by-hostel?hostel_id=${hostelId}`)
            .then(response => response.json())
            .then(rooms => {
                roomSelect.innerHTML = '<option value="">Select Room</option>';
                rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = `${room.room_no} (Capacity: ${room.capacity}, Status: ${room.status})`;
                    roomSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading rooms:', error);
                roomSelect.innerHTML = '<option value="">Error loading rooms</option>';
            });
    } else {
        roomSelect.innerHTML = '<option value="">Select Room</option>';
    }
}

// Load rooms on page load if hostel is already selected
document.addEventListener('DOMContentLoaded', function() {
    const hostelId = document.getElementById('hostel_id').value;
    if (hostelId) {
        loadRooms();
    }
});
</script>
@endsection

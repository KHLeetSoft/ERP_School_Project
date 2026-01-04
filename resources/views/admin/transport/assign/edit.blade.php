@extends('admin.layout.app')

@section('title', 'Edit Transport Assignment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Transport Assignment #{{ $assignment->id }}</h4>
                    <a href="{{ route('admin.transport.assign.index') }}" class="btn btn-secondary btn-sm float-end">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form id="editAssignmentForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Vehicle</label>
                                    <select name="vehicle_id" class="form-select" required>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ $assignment->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->vehicle_number }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Route</label>
                                    <select name="route_id" class="form-select" required>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}" {{ $assignment->route_id == $route->id ? 'selected' : '' }}>
                                                {{ $route->route_name }} ({{ $route->route_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Driver</label>
                                    <select name="driver_id" class="form-select">
                                        <option value="">Select Driver</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ $assignment->driver_id == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Conductor</label>
                                    <select name="conductor_id" class="form-select">
                                        <option value="">Select Conductor</option>
                                        @foreach($conductors as $conductor)
                                            <option value="{{ $conductor->id }}" {{ $assignment->conductor_id == $conductor->id ? 'selected' : '' }}>
                                                {{ $conductor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Assignment Date</label>
                                    <input type="date" name="assignment_date" class="form-control" 
                                           value="{{ $assignment->assignment_date->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" name="start_time" class="form-control" 
                                           value="{{ $assignment->start_time->format('H:i') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="time" name="end_time" class="form-control" 
                                           value="{{ $assignment->end_time->format('H:i') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Shift Type</label>
                                    <select name="shift_type" class="form-select" required>
                                        <option value="morning" {{ $assignment->shift_type == 'morning' ? 'selected' : '' }}>Morning</option>
                                        <option value="afternoon" {{ $assignment->shift_type == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                                        <option value="evening" {{ $assignment->shift_type == 'evening' ? 'selected' : '' }}>Evening</option>
                                        <option value="night" {{ $assignment->shift_type == 'night' ? 'selected' : '' }}>Night</option>
                                        <option value="full_day" {{ $assignment->shift_type == 'full_day' ? 'selected' : '' }}>Full Day</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="pending" {{ $assignment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="active" {{ $assignment->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ $assignment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $assignment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="delayed" {{ $assignment->status == 'delayed' ? 'selected' : '' }}>Delayed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $assignment->notes }}</textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Assignment
                            </button>
                            <a href="{{ route('admin.transport.assign.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#editAssignmentForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.transport.assign.update", $assignment->id) }}',
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("admin.transport.assign.index") }}';
                    });
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Something went wrong!', 'error');
            }
        });
    });
});
</script>
@endsection

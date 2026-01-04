@extends('admin.layout.app')

@section('title', 'Edit Call Log')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Call Log</h1>
        <div class="d-none d-sm-inline-block">
            <a href="{{ route('admin.office.calllogs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Call Logs
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Call Log Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.office.calllogs.update', $log->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="caller_name" class="form-label">Caller Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('caller_name') is-invalid @enderror" 
                                   id="caller_name" name="caller_name" value="{{ old('caller_name', $log->caller_name) }}" required>
                            @error('caller_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $log->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <select class="form-select @error('purpose') is-invalid @enderror" id="purpose" name="purpose">
                                <option value="">Select Purpose</option>
                                <option value="Inquiry" {{ old('purpose', $log->purpose) == 'Inquiry' ? 'selected' : '' }}>Inquiry</option>
                                <option value="Complaint" {{ old('purpose', $log->purpose) == 'Complaint' ? 'selected' : '' }}>Complaint</option>
                                <option value="Support" {{ old('purpose', $log->purpose) == 'Support' ? 'selected' : '' }}>Support</option>
                                <option value="General" {{ old('purpose', $log->purpose) == 'General' ? 'selected' : '' }}>General</option>
                                <option value="Admission" {{ old('purpose', $log->purpose) == 'Admission' ? 'selected' : '' }}>Admission</option>
                                <option value="Fee" {{ old('purpose', $log->purpose) == 'Fee' ? 'selected' : '' }}>Fee</option>
                                <option value="Other" {{ old('purpose', $log->purpose) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <input type="text" class="form-control @error('duration') is-invalid @enderror" 
                                   id="duration" name="duration" value="{{ old('duration', $log->duration) }}" 
                                   placeholder="e.g., 5 minutes, 10:30">
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', $log->date ? $log->date->format('Y-m-d') : '') }}">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" class="form-control @error('time') is-invalid @enderror" 
                                   id="time" name="time" value="{{ old('time', $log->time) }}">
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Notes</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" 
                              id="note" name="note" rows="4" 
                              placeholder="Enter any additional notes about the call...">{{ old('note', $log->note) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.office.calllogs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Call Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Phone number formatting
    $('#phone').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 10) {
                $(this).val(value);
            } else {
                $(this).val(value.substring(0, 10));
            }
        }
    });

    // Form validation
    $('form').on('submit', function(e) {
        var callerName = $('#caller_name').val().trim();
        if (!callerName) {
            e.preventDefault();
            $('#caller_name').addClass('is-invalid');
            toastr.error('Caller name is required');
            return false;
        }
    });
});
</script>
@endpush
@endsection
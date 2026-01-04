@extends('admin.layout.app')

@section('title', 'Edit Communication')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Communication</h4>
                    <a href="{{ route('admin.parents.communication.show', $communication->id) }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Details
                    </a>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.parents.communication.update', $communication->id) }}" method="POST" id="communication-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Parent Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="parent_detail_id" class="form-label">Parent <span class="text-danger">*</span></label>
                                <select name="parent_detail_id" id="parent_detail_id" class="form-select @error('parent_detail_id') is-invalid @enderror" required>
                                    <option value="">Select Parent</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_detail_id', $communication->parent_detail_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->primary_contact_name ?? $parent->user->name ?? 'Parent #' . $parent->id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_detail_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Student Selection (Optional) -->
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student (Optional)</label>
                                <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror">
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id', $communication->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Communication Type -->
                            <div class="col-md-6 mb-3">
                                <label for="communication_type" class="form-label">Communication Type <span class="text-danger">*</span></label>
                                <select name="communication_type" id="communication_type" class="form-select @error('communication_type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    @foreach($communicationTypes as $type)
                                        <option value="{{ $type }}" {{ old('communication_type', $communication->communication_type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('communication_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="">Select Status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status', $communication->status) == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="">Select Priority</option>
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority }}" {{ old('priority', $communication->priority) == $priority ? 'selected' : '' }}>
                                            {{ ucfirst($priority) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select @error('category') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category', $communication->category) == $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Admin (Optional) -->
                            <div class="col-md-6 mb-3">
                                <label for="admin_id" class="form-label">Admin (Optional)</label>
                                <select name="admin_id" id="admin_id" class="form-select @error('admin_id') is-invalid @enderror">
                                    <option value="">Select Admin</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ old('admin_id', $communication->admin_id) == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('admin_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div class="col-md-12 mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" 
                                       value="{{ old('subject', $communication->subject) }}" placeholder="Enter subject (optional)">
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div class="col-md-12 mb-3">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" rows="6" class="form-control @error('message') is-invalid @enderror" 
                                          placeholder="Enter your message here..." required>{{ old('message', $communication->message) }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Response -->
                            <div class="col-md-12 mb-3">
                                <label for="response" class="form-label">Parent Response (Optional)</label>
                                <textarea name="response" id="response" rows="4" class="form-control @error('response') is-invalid @enderror" 
                                          placeholder="Enter parent response if any...">{{ old('response', $communication->response) }}</textarea>
                                @error('response')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Add parent's response to this communication if received</small>
                            </div>

                            <!-- Communication Channel -->
                            <div class="col-md-6 mb-3">
                                <label for="communication_channel" class="form-label">Communication Channel</label>
                                <input type="text" name="communication_channel" id="communication_channel" class="form-control @error('communication_channel') is-invalid @enderror" 
                                       value="{{ old('communication_channel', $communication->communication_channel) }}" placeholder="e.g., Gmail, Twilio, etc.">
                                @error('communication_channel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cost -->
                            <div class="col-md-6 mb-3">
                                <label for="cost" class="form-label">Cost (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="cost" id="cost" class="form-control @error('cost') is-invalid @enderror" 
                                           value="{{ old('cost', $communication->cost) }}" step="0.01" min="0" placeholder="0.00">
                                </div>
                                @error('cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">For SMS, phone calls, or other paid services</small>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Additional notes or instructions...">{{ old('notes', $communication->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Timestamps Information (Read-only) -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bx bx-time"></i> Timestamps (Read-only)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Sent:</label>
                                                <p class="form-control-plaintext">{{ $communication->sent_at ? $communication->sent_at->format('M d, Y H:i') : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Delivered:</label>
                                                <p class="form-control-plaintext">{{ $communication->delivered_at ? $communication->delivered_at->format('M d, Y H:i') : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Read:</label>
                                                <p class="form-control-plaintext">{{ $communication->read_at ? $communication->read_at->format('M d, Y H:i') : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold">Response:</label>
                                                <p class="form-control-plaintext">{{ $communication->response_at ? $communication->response_at->format('M d, Y H:i') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Update Communication
                                </button>
                                <a href="{{ route('admin.parents.communication.show', $communication->id) }}" class="btn btn-secondary">
                                    Cancel
                                </a>
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
<script>
$(document).ready(function() {
    // Auto-populate student dropdown when parent is selected
    $('#parent_detail_id').on('change', function() {
        const parentId = $(this).val();
        if (parentId) {
            loadParentStudents(parentId);
        } else {
            $('#student_id').html('<option value="">Select Student</option>');
        }
    });

    // Form validation
    $('#communication-form').on('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });

    // Initialize parent students if parent is pre-selected
    const selectedParentId = $('#parent_detail_id').val();
    if (selectedParentId) {
        loadParentStudents(selectedParentId);
    }
});

function loadParentStudents(parentId) {
    $.ajax({
        url: '{{ route("admin.parents.communication.get-parent-students", ":id") }}'.replace(':id', parentId),
        method: 'GET',
        success: function(students) {
            let options = '<option value="">Select Student</option>';
            students.forEach(function(student) {
                const selected = student.id == '{{ $communication->student_id }}' ? 'selected' : '';
                options += `<option value="${student.id}" ${selected}>${student.name}</option>`;
            });
            $('#student_id').html(options);
        },
        error: function() {
            $('#student_id').html('<option value="">Error loading students</option>');
        }
    });
}

function validateForm() {
    let isValid = true;
    
    // Check required fields
    const requiredFields = ['parent_detail_id', 'communication_type', 'message', 'priority', 'status'];
    requiredFields.forEach(function(field) {
        const value = $('#' + field).val();
        if (!value) {
            $('#' + field).addClass('is-invalid');
            isValid = false;
        } else {
            $('#' + field).removeClass('is-invalid');
        }
    });

    return isValid;
}
</script>
@endsection

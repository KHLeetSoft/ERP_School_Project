@extends('admin.layout.app')

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">✏️ Edit Student Health Record</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.students.health.update', $health->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Student -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Student</label>
                    <select name="student_id" class="form-select" required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" 
                                {{ $student->id == $health->student_id ? 'selected' : '' }}>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Blood Group -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Blood Group</label>
                    <select name="blood_group" class="form-select" required>
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $group)
                            <option value="{{ $group }}" {{ $health->blood_group == $group ? 'selected' : '' }}>
                                {{ $group }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Height -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Height (cm)</label>
                    <input type="number" name="height" value="{{ $health->height }}" class="form-control" min="0" step="0.1" required>
                </div>

                <!-- Weight -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Weight (kg)</label>
                    <input type="number" name="weight" value="{{ $health->weight }}" class="form-control" min="0" step="0.1" required>
                </div>

                <!-- Allergies -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Allergies</label>
                    <textarea name="allergies" class="form-control" rows="2" placeholder="List allergies">{{ $health->allergies }}</textarea>
                </div>

                <!-- Medical Conditions -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Medical Conditions</label>
                    <textarea name="medical_conditions" class="form-control" rows="2" placeholder="List medical conditions">{{ $health->medical_conditions }}</textarea>
                </div>

                <!-- Immunizations -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Immunizations</label>
                    <textarea name="immunizations" class="form-control" rows="2" placeholder="List immunizations">{{ $health->immunizations }}</textarea>
                </div>

                <!-- Last Checkup Date -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Last Checkup Date</label>
                    <input type="date" name="last_checkup_date" value="{{ $health->last_checkup_date }}" class="form-control">
                </div>

                <!-- Health Notes -->
                <div class="col-12">
                    <label class="form-label fw-bold">Health Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes">{{ $health->notes }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-4 text-end">
                <a href="{{ route('admin.students.health.index') }}" class="btn btn-secondary me-2">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <button class="btn btn-success">
                    <i class="fa fa-save"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layout.app')

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add Student Health Record</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.students.health.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <!-- Student -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Student</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
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
                        <option>A+</option>
                        <option>A-</option>
                        <option>B+</option>
                        <option>B-</option>
                        <option>O+</option>
                        <option>O-</option>
                        <option>AB+</option>
                        <option>AB-</option>
                    </select>
                </div>

                <!-- Height -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Height (cm)</label>
                    <input type="number" name="height" class="form-control" min="0" step="0.1" placeholder="e.g. 170" required>
                </div>

                <!-- Weight -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Weight (kg)</label>
                    <input type="number" name="weight" class="form-control" min="0" step="0.1" placeholder="e.g. 65" required>
                </div>

                <!-- Allergies -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Allergies</label>
                    <textarea name="allergies" class="form-control" rows="2" placeholder="List any allergies"></textarea>
                </div>

                <!-- Medical Conditions -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Medical Conditions</label>
                    <textarea name="medical_conditions" class="form-control" rows="2" placeholder="List any conditions"></textarea>
                </div>

                <!-- Immunizations -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Immunizations</label>
                    <textarea name="immunizations" class="form-control" rows="2" placeholder="List immunizations"></textarea>
                </div>

                <!-- Last Checkup Date -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Last Checkup Date</label>
                    <input type="date" name="last_checkup_date" class="form-control">
                </div>

                <!-- Notes -->
                <div class="col-12">
                    <label class="form-label fw-bold">Health Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-4 text-end">
                <a href="{{ route('admin.students.health.index') }}" class="btn btn-secondary me-2">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <button class="btn btn-success">
                    <i class="fa fa-save"></i> Save Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layout.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-random me-2"></i> Create Substitution</h5>
            <a href="{{ route('admin.academic.substitution.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.academic.substitution.store') }}">
                @csrf

                <!-- Teacher Selection -->
                <div class="mb-3">
                    <label for="teacher_id" class="form-label fw-bold">Teacher</label>
                    <select class="form-select" id="teacher_id" name="teacher_id" required>
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Substitute Selection -->
                <div class="mb-3">
                    <label for="substitute_id" class="form-label fw-bold">Substitute</label>
                    <select class="form-select" id="substitute_id" name="substitute_id" required>
                        <option value="">-- Select Substitute --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Picker -->
                <div class="mb-3">
                    <label for="date" class="form-label fw-bold">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Substitution
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

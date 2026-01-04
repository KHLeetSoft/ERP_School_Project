@extends('admin.layout.app')
@section('content')
<div class="container">
    <h4>Edit Assignment</h4>
    <form action="{{ route('admin.academic.assignments.update', $assignment) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $assignment->title) }}" required>
            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $assignment->description) }}</textarea>
            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $assignment->due_date) }}">
            @error('due_date')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ old('status', $assignment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status', $assignment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.academic.assignments.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold"><i class="bx bxs-edit"></i> Edit Assignment</h1>
        <a href="{{ route('admin.academic.assignments.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to List
        </a>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Assignment Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.academic.assignments.update', $assignment) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $assignment->title) }}" required>
                    @error('title')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="due_date" class="form-label fw-bold">Due Date</label>
                    <input type="date" name="due_date" class="form-control" id="due_date" value="{{ old('due_date', $assignment->due_date) }}">
                    @error('due_date')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="4">{{ old('description', $assignment->description) }}</textarea>
                    @error('description')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" id="status">
                        <option value="pending" {{ old('status', $assignment->status) == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="submitted" {{ old('status', $assignment->status) == 'submitted' ? 'selected' : '' }}>
                            Submitted
                        </option>
                        <option value="checked" {{ old('status', $assignment->status) == 'checked' ? 'selected' : '' }}>
                            Checked
                        </option>
                        <option value="completed" {{ old('status', $assignment->status) == 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                    @error('status')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="priority" class="form-label fw-bold">Priority</label>
                    <select name="priority" class="form-select" id="priority">
                        <option value="low" {{ old('priority', $assignment->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $assignment->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $assignment->priority) == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="max_marks" class="form-label fw-bold">Maximum Marks</label>
                    <input type="number" name="max_marks" class="form-control" id="max_marks" value="{{ old('max_marks', $assignment->max_marks) }}">
                </div>

                <div class="col-md-6">
                    <label for="passing_marks" class="form-label fw-bold">Passing Marks</label>
                    <input type="number" name="passing_marks" class="form-control" id="passing_marks" value="{{ old('passing_marks', $assignment->passing_marks) }}">
                </div>

                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bx bxs-save"></i> Update Assignment
                    </button>
                    <a href="{{ route('admin.academic.assignments.index') }}" class="btn btn-secondary btn-lg">
                        <i class="bx bx-arrow-back"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

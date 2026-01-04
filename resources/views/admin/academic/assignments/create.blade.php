@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><i class="bx bx-plus-circle"></i> Create Assignment</h4>
        <a href="{{ route('admin.academic.assignments.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to List
        </a>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Assignment Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.academic.assignments.store') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                @csrf

                <div class="col-md-6">
                    <label for="title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" required>
                    @error('title')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="due_date" class="form-label fw-bold">Due Date</label>
                    <input type="date" name="due_date" class="form-control" id="due_date" value="{{ old('due_date') }}">
                    @error('due_date')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="class_id" class="form-label fw-bold">Class</label>
                    <select name="class_id" class="form-select" id="class_id" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="section_id" class="form-label fw-bold">Section</label>
                    <select name="section_id" class="form-select" id="section_id" required>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="subject_id" class="form-label fw-bold">Subject</label>
                    <select name="subject_id" class="form-select" id="subject_id" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="teacher_id" class="form-label fw-bold">Teacher</label>
                    <select name="teacher_id" class="form-select" id="teacher_id" required>
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="priority" class="form-label fw-bold">Priority</label>
                    <select name="priority" class="form-select" id="priority">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" id="status">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="checked" {{ old('status') == 'checked' ? 'selected' : '' }}>Checked</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="max_marks" class="form-label fw-bold">Maximum Marks</label>
                    <input type="number" name="max_marks" class="form-control" id="max_marks" value="{{ old('max_marks') }}">
                </div>

                <div class="col-md-6">
                    <label for="passing_marks" class="form-label fw-bold">Passing Marks</label>
                    <input type="number" name="passing_marks" class="form-control" id="passing_marks" value="{{ old('passing_marks') }}">
                </div>

                <div class="col-12">
                    <label for="file" class="form-label fw-bold">Attachment</label>
                    <input type="file" name="file" class="form-control" id="file">
                </div>

                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bx bxs-save"></i> Save Assignment
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

@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Edit Coverage</h4>
        <a href="{{ route('admin.academic.coverage.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back
        </a>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Update Coverage Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.academic.coverage.update', $coverage->id) }}" method="POST">
                @csrf 
                @method('PUT')

                {{-- Title --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ $coverage->title }}" required>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $coverage->description }}</textarea>
                </div>

                {{-- Date --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $coverage->date }}">
                </div>

                <div class="row">
                    {{-- Class --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Class</label>
                        <select name="class_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" @if($coverage->class_id==$class->id) selected @endif>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Subject --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Subject</label>
                        <select name="subject_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @if($coverage->subject_id==$subject->id) selected @endif>
                                    {{ $subject->subject_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" @if($coverage->status=='pending') selected @endif>Pending</option>
                        <option value="completed" @if($coverage->status=='completed') selected @endif>Completed</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bx bx-save"></i> Update Coverage
                    </button>
                    <a href="{{ route('admin.academic.coverage.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

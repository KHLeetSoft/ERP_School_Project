@extends('admin.layout.app')
@section('content')
<div class="container">
    <h1>Add Coverage</h1>
    <form action="{{ route('admin.academic.coverage.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control">
        </div>
        <div class="mb-3">
            <label>Class</label>
            <select name="class_id" class="form-control">
                <option value="">Select</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Subject</label>
            <select name="subject_id" class="form-control">
                <option value="">Select</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection

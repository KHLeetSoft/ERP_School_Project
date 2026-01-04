@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Substitution</h1>
    <form method="POST" action="{{ route('admin.academic.substitution.update', $substitution->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="teacher_id" class="form-label">Teacher ID</label>
            <input type="number" class="form-control" id="teacher_id" name="teacher_id" value="{{ $substitution->teacher_id }}" required>
        </div>
        <div class="mb-3">
            <label for="substitute_id" class="form-label">Substitute ID</label>
            <input type="number" class="form-control" id="substitute_id" name="substitute_id" value="{{ $substitution->substitute_id }}" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $substitution->date }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

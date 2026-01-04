@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Substitution Details</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $substitution->id }}</p>
            <p><strong>Teacher ID:</strong> {{ $substitution->teacher_id }}</p>
            <p><strong>Substitute ID:</strong> {{ $substitution->substitute_id }}</p>
            <p><strong>Date:</strong> {{ $substitution->date }}</p>
        </div>
    </div>
    <a href="{{ route('admin.academic.substitution.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection

@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">✏️ Edit Student Result</h4>

    <form method="POST" action="{{ route('results.update', $result->id) }}">
        @csrf
        @method('PUT')

        @include('admin.results.partials.form', ['result' => $result])

        <button type="submit" class="btn btn-success rounded-pill">✅ Update</button>
        <a href="{{ route('results.index') }}" class="btn btn-secondary rounded-pill">🔙 Back</a>
    </form>
</div>
@endsection

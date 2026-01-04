@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Book</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.library.books.update', $book) }}">
        @csrf
        @method('PUT')
        @include('admin.library.books.partials.form')
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.library.books.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection



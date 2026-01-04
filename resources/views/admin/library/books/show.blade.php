@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Book Details</h4>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Title</dt>
                <dd class="col-sm-9">{{ $book->title }}</dd>

                <dt class="col-sm-3">Author</dt>
                <dd class="col-sm-9">{{ $book->author }}</dd>

                <dt class="col-sm-3">Genre</dt>
                <dd class="col-sm-9">{{ $book->genre }}</dd>

                <dt class="col-sm-3">Published Year</dt>
                <dd class="col-sm-9">{{ $book->published_year }}</dd>

                <dt class="col-sm-3">ISBN</dt>
                <dd class="col-sm-9">{{ $book->isbn }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ ucfirst($book->status) }}</dd>

                <dt class="col-sm-3">Stock</dt>
                <dd class="col-sm-9">{{ $book->stock_quantity }}</dd>

                <dt class="col-sm-3">Shelf Location</dt>
                <dd class="col-sm-9">{{ $book->shelf_location }}</dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9">{{ $book->description }}</dd>
            </dl>
            <a href="{{ route('admin.library.books.edit', $book) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.library.books.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection



@extends('superadmin.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">➕ Add Purchase</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.purchases.store') }}" method="POST">
        @csrf

        @include('superadmin.purchases.form')

        <button type="submit" class="btn btn-success">
            <i class="bx bx-save"></i> Save Purchase
        </button>
    </form>
</div>
@endsection

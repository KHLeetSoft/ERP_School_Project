@extends('superadmin.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">✏️ Edit Purchase</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.purchases.update', $purchase->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('superadmin.purchases.form', ['purchase' => $purchase])

        <button type="submit" class="btn btn-primary">
            <i class="bx bx-edit"></i> Update Purchase
        </button>
    </form>
</div>
@endsection

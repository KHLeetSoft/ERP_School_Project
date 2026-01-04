@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Sale #{{ $sale->id }}</h4>
        <a href="{{ route('admin.canteen.sales.index') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.canteen.sales.update', $sale) }}">
                @method('PUT')
                @include('admin.canteen.sales.form', ['sale' => $sale])
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Item #{{ $item->id }}</h4>
        <div>
            <a href="{{ route('admin.canteen.items.edit', $item) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.canteen.items.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9">{{ $item->name }}</dd>

                <dt class="col-sm-3">Price</dt>
                <dd class="col-sm-9">{{ number_format($item->price, 2) }}</dd>

                <dt class="col-sm-3">Stock Quantity</dt>
                <dd class="col-sm-9">{{ $item->stock_quantity }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ $item->is_active ? 'Active' : 'Inactive' }}</dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9">{{ $item->description }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection




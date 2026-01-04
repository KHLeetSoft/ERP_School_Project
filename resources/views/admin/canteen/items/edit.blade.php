@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Canteen Item</h4>
        <a href="{{ route('admin.canteen.items.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.canteen.items.update', $item) }}" method="POST">
                @method('PUT')
                @include('admin.canteen.items.form', ['item' => $item])
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection




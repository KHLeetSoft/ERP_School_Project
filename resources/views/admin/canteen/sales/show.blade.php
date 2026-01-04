@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Sale #{{ $sale->id }}</h4>
        <a href="{{ route('admin.canteen.sales.index') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Sold At</dt>
                <dd class="col-sm-9">{{ optional($sale->sold_at)->format('Y-m-d H:i') }}</dd>

                <dt class="col-sm-3">Item</dt>
                <dd class="col-sm-9">{{ optional($sale->item)->name }}</dd>

                <dt class="col-sm-3">Quantity</dt>
                <dd class="col-sm-9">{{ $sale->quantity }}</dd>

                <dt class="col-sm-3">Unit Price</dt>
                <dd class="col-sm-9">{{ number_format($sale->unit_price, 2) }}</dd>

                <dt class="col-sm-3">Total</dt>
                <dd class="col-sm-9">{{ number_format($sale->total_amount, 2) }}</dd>

                <dt class="col-sm-3">Buyer</dt>
                <dd class="col-sm-9">{{ $sale->buyer_type ?? '-' }} {{ $sale->buyer_id ? '(#'.$sale->buyer_id.')' : '' }}</dd>

                <dt class="col-sm-3">Notes</dt>
                <dd class="col-sm-9">{{ $sale->notes }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection



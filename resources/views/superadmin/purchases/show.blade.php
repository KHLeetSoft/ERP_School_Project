@extends('superadmin.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📄 Purchase Details</h2>

    <div class="card p-3 shadow">
        <h5><strong>Item:</strong> {{ $purchase->item_name }}</h5>
        <p><strong>Vendor:</strong> {{ $purchase->vendor ?? '-' }}</p>
        <p><strong>Quantity:</strong> {{ $purchase->quantity }}</p>
        <p><strong>Price:</strong> ₹{{ number_format($purchase->price, 2) }}</p>
        <p><strong>Date:</strong> {{ $purchase->purchase_date }}</p>
        <p><strong>Notes:</strong> {{ $purchase->notes ?? '—' }}</p>

        <a href="{{ route('superadmin.purchases.index') }}" class="btn btn-secondary mt-3">
            ⬅️ Back to List
        </a>
    </div>
</div>
@endsection

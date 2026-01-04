@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Edit Invoice</h6>
                    <a href="{{ route('admin.finance.invoice.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.finance.invoice.update', $invoice->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Invoice #</label>
                                <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" disabled>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Bill To *</label>
                                <input type="text" name="bill_to" class="form-control" value="{{ $invoice->bill_to }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Issue Date *</label>
                                <input type="date" name="issue_date" class="form-control" value="{{ $invoice->issue_date->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control" value="{{ optional($invoice->due_date)->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    @foreach(['draft','sent','paid','overdue','cancelled'] as $s)
                                        <option value="{{ $s }}" @selected($invoice->status==$s)>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Items (JSON)</label>
                                <textarea name="items" rows="4" class="form-control">{{ json_encode($invoice->items, JSON_PRETTY_PRINT) }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Subtotal *</label>
                                <input type="number" step="0.01" name="subtotal" class="form-control" value="{{ $invoice->subtotal }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tax</label>
                                <input type="number" step="0.01" name="tax" class="form-control" value="{{ $invoice->tax }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Discount</label>
                                <input type="number" step="0.01" name="discount" class="form-control" value="{{ $invoice->discount }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total *</label>
                                <input type="number" step="0.01" name="total" class="form-control" value="{{ $invoice->total }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" rows="3" class="form-control">{{ $invoice->notes }}</textarea>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



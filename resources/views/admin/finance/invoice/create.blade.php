@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Create Invoice</h6>
                    <a href="{{ route('admin.finance.invoice.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.finance.invoice.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Invoice # *</label>
                                <input type="text" name="invoice_number" class="form-control" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Bill To *</label>
                                <input type="text" name="bill_to" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Issue Date *</label>
                                <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    @foreach(['draft','sent','paid','overdue','cancelled'] as $s)
                                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Items (JSON)</label>
                                <textarea name="items" rows="4" class="form-control" placeholder='[{"description":"Item 1","qty":1,"price":100,"amount":100}]'></textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Subtotal *</label>
                                <input type="number" step="0.01" name="subtotal" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tax</label>
                                <input type="number" step="0.01" name="tax" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Discount</label>
                                <input type="number" step="0.01" name="discount" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total *</label>
                                <input type="number" step="0.01" name="total" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



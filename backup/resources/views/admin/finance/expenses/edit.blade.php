@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Edit Expense</h6>
                    <a href="{{ route('admin.finance.expenses.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.finance.expenses.update', $expense->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Date *</label>
                                <input type="date" name="expense_date" class="form-control" value="{{ $expense->expense_date->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control" value="{{ $expense->category }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vendor</label>
                                <input type="text" name="vendor" class="form-control" value="{{ $expense->vendor }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Amount *</label>
                                <input type="number" step="0.01" name="amount" class="form-control" value="{{ $expense->amount }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Method *</label>
                                <select name="method" class="form-select" required>
                                    @foreach(['cash','card','bank','online','cheque'] as $m)
                                        <option value="{{ $m }}" @selected($expense->method==$m)>{{ ucfirst($m) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    @foreach(['pending','approved','paid','void'] as $s)
                                        <option value="{{ $s }}" @selected($expense->status==$s)>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Reference</label>
                                <input type="text" name="reference" class="form-control" value="{{ $expense->reference }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="3" class="form-control">{{ $expense->description }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" rows="2" class="form-control">{{ $expense->notes }}</textarea>
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



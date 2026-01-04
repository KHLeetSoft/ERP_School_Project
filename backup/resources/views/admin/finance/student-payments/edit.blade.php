@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Edit Payment</h6>
                    <a href="{{ route('admin.finance.student-payments.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.finance.student-payments.update', $payment->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Student *</label>
                                <select name="student_id" class="form-select" required>
                                    @foreach($students as $s)
                                        <option value="{{ $s->id }}" @selected($payment->student_id==$s->id)>{{ $s->full_name }} ({{ $s->admission_no }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date *</label>
                                <input type="date" name="payment_date" class="form-control" value="{{ $payment->payment_date->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Amount *</label>
                                <input type="number" step="0.01" name="amount" class="form-control" value="{{ $payment->amount }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Method *</label>
                                <select name="method" class="form-select" required>
                                    @foreach(['cash','card','bank','online'] as $m)
                                        <option value="{{ $m }}" @selected($payment->method==$m)>{{ ucfirst($m) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    @foreach(['pending','completed','failed','refunded'] as $s)
                                        <option value="{{ $s }}" @selected($payment->status==$s)>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Reference</label>
                                <input type="text" name="reference" class="form-control" value="{{ $payment->reference }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" rows="3" class="form-control">{{ $payment->notes }}</textarea>
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



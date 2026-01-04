@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Fee Details</h4>
    <div>
      <a href="{{ route('admin.students.fees.edit', $fee->id) }}" class="btn btn-sm btn-primary me-2">Edit</a>
      <a href="{{ route('admin.students.fees.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Student</label>
        <div class="form-control bg-light">{{ trim(($fee->student->first_name ?? '') . ' ' . ($fee->student->last_name ?? '')) ?: ($fee->student->user->name ?? '-') }}</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Class</label>
        <div class="form-control bg-light">{{ $fee->schoolClass->name ?? '-' }}</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Amount</label>
        <div class="form-control bg-light">{{ number_format($fee->amount, 2) }}</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Fee Date</label>
        <div class="form-control bg-light">{{ optional($fee->fee_date)->format('Y-m-d') }}</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Payment Mode</label>
        <div class="form-control bg-light">{{ $fee->payment_mode ?? '-' }}</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Transaction ID</label>
        <div class="form-control bg-light">{{ $fee->transaction_id ?? '-' }}</div>
      </div>
      <div class="col-md-12">
        <label class="form-label">Remarks</label>
        <div class="form-control bg-light">{{ $fee->remarks ?? '-' }}</div>
      </div>
    </div>
  </div>
</div>
@endsection



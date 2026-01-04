@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header"><h4 class="mb-0">Edit Fee</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.students.fees.update', $fee->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Class</label>
          <select name="class_id" class="form-select" required>
            @foreach($classes as $class)
              <option value="{{ $class->id }}" {{ $fee->class_id == $class->id ? 'selected' : '' }}>{{ $class->name ?? $class->class_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Student</label>
          <select name="student_id" class="form-select" required>
            @foreach($students as $student)
              <option value="{{ $student->id }}" {{ $fee->student_id == $student->id ? 'selected' : '' }}>{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->name ?? 'Student') }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Amount</label>
          <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ $fee->amount }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Fee Date</label>
          <input type="date" name="fee_date" class="form-control" value="{{ \Illuminate\Support\Carbon::parse($fee->fee_date)->format('Y-m-d') }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Payment Mode</label>
          <select name="payment_mode" class="form-select">
            <option value="" {{ $fee->payment_mode ? '' : 'selected' }}>Select</option>
            @foreach(['Cash','Card','Online','UPI','Bank'] as $mode)
              <option value="{{ $mode }}" {{ $fee->payment_mode === $mode ? 'selected' : '' }}>{{ $mode }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Transaction ID</label>
          <input type="text" name="transaction_id" class="form-control" value="{{ $fee->transaction_id }}">
        </div>
        <div class="col-md-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="3">{{ $fee->remarks }}</textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.students.fees.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection



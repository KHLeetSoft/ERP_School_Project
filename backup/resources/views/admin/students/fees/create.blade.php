@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header"><h4 class="mb-0">Add Student Fee</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.students.fees.store') }}" method="POST">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Class</label>
          <select name="class_id" class="form-select" required>
            <option value="">Select Class</option>
            @foreach($classes as $class)
              <option value="{{ $class->id }}">{{ $class->name ?? $class->class_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Student</label>
          <select name="student_id" class="form-select" required>
            <option value="">Select Student</option>
            @foreach($students as $student)
              <option value="{{ $student->id }}">{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->name ?? 'Student') }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Amount</label>
          <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Fee Date</label>
          <input type="date" name="fee_date" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Payment Mode</label>
          <select name="payment_mode" class="form-select">
            <option value="">Select</option>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
            <option value="Online">Online</option>
            <option value="UPI">UPI</option>
            <option value="Bank">Bank</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Transaction ID</label>
          <input type="text" name="transaction_id" class="form-control">
        </div>
        <div class="col-md-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.students.fees.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection



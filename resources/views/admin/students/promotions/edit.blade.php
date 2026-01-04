@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header"><h4 class="mb-0">Edit Promotion</h4></div>
  <div class="card-body">
    <form action="{{ route('admin.students.promotions.update', $promotion->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Student</label>
          <select name="student_id" class="form-select" required>
            @foreach($students as $s)
              <option value="{{ $s->id }}" {{ $promotion->student_id == $s->id ? 'selected' : '' }}>{{ trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? '')) ?: ($s->user->name ?? 'Student') }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">From Class</label>
          <select name="from_class_id" class="form-select" required>
            @foreach($classes as $c)
              <option value="{{ $c->id }}" {{ $promotion->from_class_id == $c->id ? 'selected' : '' }}>{{ $c->name ?? $c->class_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">From Section</label>
          <select name="from_section_id" class="form-select">
            <option value="">Select</option>
            @foreach($sections as $sec)
              <option value="{{ $sec->id }}" {{ $promotion->from_section_id == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">To Class</label>
          <select name="to_class_id" class="form-select" required>
            @foreach($classes as $c)
              <option value="{{ $c->id }}" {{ $promotion->to_class_id == $c->id ? 'selected' : '' }}>{{ $c->name ?? $c->class_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">To Section</label>
          <select name="to_section_id" class="form-select">
            <option value="">Select</option>
            @foreach($sections as $sec)
              <option value="{{ $sec->id }}" {{ $promotion->to_section_id == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Promoted At</label>
          <input type="date" name="promoted_at" class="form-control" value="{{ optional($promotion->promoted_at)->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            @foreach(['promoted','retained','transferred'] as $st)
              <option value="{{ $st }}" {{ $promotion->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="3">{{ $promotion->remarks }}</textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.students.promotions.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection



@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Create Communication</h4>
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.students.communication.store') }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Class (optional)</label>
            <select name="class_id" class="form-control">
              <option value="">Select</option>
              @foreach($classes as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Student (optional)</label>
            <select name="student_id" class="form-control">
              <option value="">Select</option>
              @foreach($students as $s)
                <option value="{{ $s->id }}">{{ trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student #'.$s->id) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Channel</label>
            <select name="channel" class="form-control" required>
              <option value="notice">Notice</option>
              <option value="sms">SMS</option>
              <option value="email">Email</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" placeholder="Subject (optional)" />
          </div>
          <div class="col-md-12">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="6" required></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
              <option value="draft">Draft</option>
              <option value="scheduled">Scheduled</option>
              <option value="sent">Sent</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Send At</label>
            <input type="datetime-local" name="sent_at" class="form-control" />
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Save</button>
          <a href="{{ route('admin.students.communication.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection



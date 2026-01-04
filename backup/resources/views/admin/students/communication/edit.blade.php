@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Edit Communication</h4>
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.students.communication.update', $record->id) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Class (optional)</label>
            <select name="class_id" class="form-control">
              <option value="">Select</option>
              @foreach($classes as $c)
                <option value="{{ $c->id }}" {{ $record->class_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Student (optional)</label>
            <select name="student_id" class="form-control">
              <option value="">Select</option>
              @foreach($students as $s)
                <option value="{{ $s->id }}" {{ $record->student_id == $s->id ? 'selected' : '' }}>{{ trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student #'.$s->id) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Channel</label>
            <select name="channel" class="form-control" required>
              <option value="notice" {{ $record->channel=='notice' ? 'selected' : '' }}>Notice</option>
              <option value="sms" {{ $record->channel=='sms' ? 'selected' : '' }}>SMS</option>
              <option value="email" {{ $record->channel=='email' ? 'selected' : '' }}>Email</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" value="{{ $record->subject }}" />
          </div>
          <div class="col-md-12">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="6" required>{{ $record->message }}</textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
              <option value="draft" {{ $record->status=='draft' ? 'selected' : '' }}>Draft</option>
              <option value="scheduled" {{ $record->status=='scheduled' ? 'selected' : '' }}>Scheduled</option>
              <option value="sent" {{ $record->status=='sent' ? 'selected' : '' }}>Sent</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Send At</label>
            <input type="datetime-local" name="sent_at" class="form-control" value="{{ optional($record->sent_at)->format('Y-m-d\TH:i') }}" />
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Update</button>
          <a href="{{ route('admin.students.communication.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection



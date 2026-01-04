@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
  <h4>Assign Hostel</h4>
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.students.hostel.store') }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-control" required>
              <option value="">Select</option>
              @foreach($students as $s)
                <option value="{{ $s->id }}">{{ trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student #'.$s->id) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Hostel</label>
            <select id="hostelSelect" name="hostel_id" class="form-control" required>
              <option value="">Select</option>
              @foreach($hostels as $h)
                <option value="{{ $h->id }}">{{ $h->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Room</label>
            <select id="roomSelect" name="room_id" class="form-control" required>
              <option value="">Select</option>
              @foreach($rooms as $r)
                <option value="{{ $r->id }}">{{ $r->room_no }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Bed No</label>
            <input type="text" name="bed_no" class="form-control" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Join Date</label>
            <input type="date" name="join_date" class="form-control" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Leave Date</label>
            <input type="date" name="leave_date" class="form-control" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="left">Left</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Save</button>
          <a href="{{ route('admin.students.hostel.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $('#hostelSelect').on('change', function(){
    const hostelId = this.value;
    if (!hostelId) return;
    $.post("{{ route('admin.students.hostel.getRoomsByHostel') }}", { _token: '{{ csrf_token() }}', hostel_id: hostelId })
      .done(function(list){
        const select = $('#roomSelect');
        select.empty().append('<option value="">Select</option>');
        list.forEach(r => select.append(`<option value="${r.id}">${r.room_no}</option>`));
      });
  });
</script>
@endsection



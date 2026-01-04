<form action="{{ route('admin.office.enquiry.followup.store', $enquiry->id) }}" method="POST">
  @csrf
  <div class="mb-3">
    <label for="note" class="form-label">Note</label>
    <textarea name="note" id="note" class="form-control" required></textarea>
  </div>
  <div class="mb-3">
    <label for="next_follow_up" class="form-label">Next Follow-Up Date</label>
    <input type="date" name="next_follow_up" id="next_follow_up" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Save</button>
</form> 
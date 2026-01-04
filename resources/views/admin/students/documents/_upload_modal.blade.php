<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload Student Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.students.documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Student</label>
              <select name="student_id" class="form-select" required>
                @foreach($students as $s)
                  <option value="{{ $s->id }}">{{ trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? '')) ?: ($s->user->name ?? 'Student') }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">File</label>
              <input type="file" name="file" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Title</label>
              <input type="text" name="title" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Type</label>
              <input type="text" name="document_type" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>



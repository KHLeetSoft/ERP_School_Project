@extends('teacher.layout.app')

@section('title', 'New Diary Entry')
@section('page-title', 'New Diary Entry')
@section('page-description', 'Write your thoughts and manage them easily')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-pen me-2"></i>Create Entry</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('teacher.diary.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Optional title">
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Date *</label>
                                <input type="date" class="form-control" name="entry_date" value="{{ old('entry_date', now()->toDateString()) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Mood</label>
                                <select class="form-select" name="mood">
                                    <option value="">Select mood</option>
                                    @foreach(['happy','neutral','sad','excited','tired','stressed','grateful'] as $mood)
                                        <option value="{{ $mood }}" @selected(old('mood')===$mood)>{{ ucfirst($mood) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mt-4 pt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="is_pinned" name="is_pinned" @checked(old('is_pinned'))>
                                <label class="form-check-label" for="is_pinned">
                                    Pin this entry
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tagsInput" placeholder="Comma separated e.g. goal,idea,reminder">
                        <input type="hidden" name="tags[]" id="tagsHidden">
                        <div class="form-text">Add tags to organize your entries</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content *</label>
                        <textarea class="form-control" name="content" rows="10" required placeholder="Write your thoughts here...">{{ old('content') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.diary.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card modern-card">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i> Tips</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Write in Hindi, English, or both.</li>
                    <li>Use tags to find entries later.</li>
                    <li>Pin important entries to keep them on top.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('tagsInput');
  const hidden = document.getElementById('tagsHidden');
  function sync() {
    const tags = input.value.split(',').map(t => t.trim()).filter(Boolean);
    hidden.name = 'tags[]';
    hidden.value = '';
    // create hidden inputs per tag
    const existing = Array.from(document.querySelectorAll('input[name="tags[]"]')).filter(el => el !== hidden);
    existing.forEach(el => el.remove());
    tags.forEach(tag => {
      const el = document.createElement('input');
      el.type = 'hidden';
      el.name = 'tags[]';
      el.value = tag;
      hidden.parentNode.appendChild(el);
    });
  }
  input.addEventListener('blur', sync);
  input.addEventListener('change', sync);
});
</script>
@endsection



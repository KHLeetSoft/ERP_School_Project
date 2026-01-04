@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Subject</label>
        <select name="subject_id" class="form-select" required>
            @foreach($subjects as $sub)
                <option value="{{ $sub->id }}" @selected(old('subject_id', $syllabus->subject_id ?? null) == $sub->id)>{{ $sub->name }} ({{ $sub->code }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Term</label>
        <input type="text" name="term" class="form-control" value="{{ old('term', $syllabus->term ?? '') }}" />
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        @php($s = old('status', ($syllabus->status ?? true) ? 1 : 0))
        <select name="status" class="form-select">
            <option value="1" @selected($s==1)>Active</option>
            <option value="0" @selected($s==0)>Inactive</option>
        </select>
    </div>

    <div class="col-md-12">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $syllabus->title ?? '') }}" required />
    </div>

    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $syllabus->description ?? '') }}</textarea>
    </div>

    <div class="col-md-3">
        <label class="form-label">Total Units</label>
        <input type="number" name="total_units" class="form-control" min="0" value="{{ old('total_units', $syllabus->total_units ?? '') }}" />
    </div>
    <div class="col-md-3">
        <label class="form-label">Completed Units</label>
        <input type="number" name="completed_units" class="form-control" min="0" value="{{ old('completed_units', $syllabus->completed_units ?? '') }}" />
    </div>
    <div class="col-md-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', isset($syllabus->start_date) ? $syllabus->start_date->format('Y-m-d') : '') }}" />
    </div>
    <div class="col-md-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', isset($syllabus->end_date) ? $syllabus->end_date->format('Y-m-d') : '') }}" />
    </div>

    <div class="col-12 text-end">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.academic.syllabus.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</div>


